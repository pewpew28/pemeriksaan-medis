<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;

// Xendit SDK Imports
use Xendit\Xendit; // Untuk inisialisasi API Key
use Xendit\Invoice; // Untuk membuat Invoice (Pay-by-Link)
use Xendit\QrCode; // Untuk membuat QRIS
use Xendit\VirtualAccounts; // Untuk membuat Virtual Account
use Xendit\Exceptions\ApiException; // <-- TAMBAHKAN ATAU PERBAIKI BARIS INI UNTUK EXCEPTION XENDIT

class PaymentController extends Controller
{
    private $xenditSecretKey;

    public function __construct()
    {
        $this->xenditSecretKey = env('XENDIT_SECRET_KEY');

        if (empty($this->xenditSecretKey)) {
            Log::error('Xendit secret key not configured in environment. Please set XENDIT_SECRET_KEY.');
        } else {
            Xendit::setApiKey($this->xenditSecretKey);
            Log::info('Xendit SDK initialized with provided API Key.');
        }
    }

    /**
     * Creates a Xendit Invoice (Pay-by-Link) and redirects the user to it.
     * This method will also save the payment record to your database.
     *
     * @param int $examinationId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function createInvoicePayment($examinationId)
    {
        try {
            $examination = Examination::with('patient', 'serviceItem')->find($examinationId);

            if (!$examination) {
                Log::warning('Attempted to create invoice payment for non-existent examination', ['examination_id' => $examinationId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Examination not found.'
                ], 404);
            }

            if (!in_array($examination->status, ['pending', 'created', 'pending_cash_payment', 'pending_payment'])) {
                Log::warning('Attempted to create invoice payment for examination in invalid status', [
                    'examination_id' => $examinationId,
                    'current_status' => $examination->status
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot be created for this examination status: ' . $examination->status
                ], 400);
            }

            $existingPayment = Payment::where('examination_id', $examination->id)
                ->where('method', 'invoice')
                ->whereIn('status', ['PENDING', 'ACTIVE'])
                ->where('expiry_time', '>', now())
                ->first();

            if ($existingPayment) {
                Log::info('Active Xendit Invoice already exists, redirecting to existing link.', [
                    'payment_id' => $existingPayment->id,
                    'checkout_link' => $existingPayment->checkout_link
                ]);
                return redirect()->away($existingPayment->checkout_link);
            }

            $externalId = 'EXAM-' . $examination->id . '-INV-' . Carbon::now()->format('YmdHis') . '-' . uniqid();

            $params = [
                'external_id' => $externalId,
                'amount' => (int) $examination->serviceItem->price,
                'description' => 'Pembayaran Pemeriksaan Medis: ' . $examination->serviceItem->name,
                'payer_email' => $examination->patient->email ?? 'noreply@klinik.com',
                'customer' => [
                    'given_names' => $examination->patient->name,
                    'mobile_number' => $examination->patient->phone_number ?? null,
                ],
                'invoice_duration' => 86400, // Durasi invoice 24 jam dalam detik
                'callback_url' => url('/api/xendit/webhook'),
                'success_redirect_url' => url('/pasien/pembayaran/' . $examination->id . '/success'),
                'failure_redirect_url' => url('/pasien/pembayaran/' . $examination->id . '/failed'),
            ];

            Log::info('Attempting to create Xendit Invoice (Pay-by-Link) via SDK', ['params' => $params]);

            $createInvoice = Invoice::create($params);
            $payment = Payment::create([
                'examination_id' => $examination->id,
                'xendit_id' => $createInvoice['id'],
                'method' => 'invoice',
                'status' => $createInvoice['status'] ?? 'PENDING',
                'amount' => (int) $createInvoice['amount'],
                'currency' => $createInvoice['currency'] ?? 'IDR',
                'checkout_link' => $createInvoice['invoice_url'],
                'reference_code' => $externalId,
                'expiry_time' => Carbon::parse($createInvoice['expiry_date']),
            ]);

            $examination->update(['status' => 'pending']);

            Log::info('Xendit Invoice created successfully and payment saved to DB.', [
                'payment_id' => $payment->id,
                'xendit_id' => $createInvoice['id'],
                'checkout_link' => $createInvoice['invoice_url'],
                'examination_new_status' => $examination->status
            ]);

            return redirect()->away($createInvoice['invoice_url']);
        } catch (ApiException $e) { // <-- PERBAIKI DARI 'XenditException' MENJADI 'ApiException'
            Log::error("Xendit Invoice API Error: " . $e->getMessage(), [
                'code' => $e->getCode(),
                'errors' => $e->getErrorData(),
                'examination_id' => $examinationId,
                'request_params' => $params ?? []
            ]);
            return redirect()->route('pasien.payment.show', ['examination' => $examinationId])->with('error', 'Gagal membuat link pembayaran: ' . ($e->getErrorData()['message'] ?? $e->getMessage()));
        } catch (\Exception $e) {
            Log::error("Unexpected error creating Xendit Invoice: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'examination_id' => $examinationId
            ]);
            return redirect()->route('pasien.payment.show', ['examination' => $examinationId])->with('error', 'Terjadi kesalahan internal saat membuat link pembayaran.');
        }
    }

    public function paymentSuccess($examinationId, Request $request)
    {
        try {
            $examination = Examination::with('patient', 'serviceItem')->find($examinationId);

            if (!$examination) {
                Log::warning('Payment success callback for non-existent examination', ['examination_id' => $examinationId]);
                return redirect()->route('pasien.dashboard')->with('error', 'Pemeriksaan tidak ditemukan.');
            }

            // Find the most recent payment for this examination
            $payment = Payment::where('examination_id', $examination->id)
                ->whereIn('method', ['invoice', 'qris', 'virtual_account'])
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$payment) {
                Log::warning('Payment success callback but no payment record found', ['examination_id' => $examinationId]);
                return redirect()->route('pasien.dashboard')->with('error', 'Data pembayaran tidak ditemukan.');
            }

            // Update payment status to PAID if not already
            if ($payment->status !== 'PAID') {
                $payment->update([
                    'status' => 'PAID',
                    'paid_at' => Carbon::now()
                ]);

                Log::info('Payment status updated to PAID', [
                    'payment_id' => $payment->id,
                    'examination_id' => $examinationId
                ]);
            }

            // Update examination status to completed
            if (!in_array($examination->status, ['completed', 'paid'])) {
                $examination->update(['status' => 'completed', 'payment_status' => 'paid']);

                Log::info('Examination status updated to completed', [
                    'examination_id' => $examinationId,
                    'previous_status' => $examination->status
                ]);
            }

            // Return success view or redirect with success message
            return view('patient.examination.payment', [
                'examination' => $examination,
                'payment' => $payment,
                'message' => 'Pembayaran berhasil! Pemeriksaan Anda telah dikonfirmasi.'
            ]);
        } catch (\Exception $e) {
            Log::error("Error processing payment success callback: " . $e->getMessage(), [
                'examination_id' => $examinationId,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('pasien.dashboard')->with('error', 'Terjadi kesalahan saat memproses konfirmasi pembayaran.');
        }
    }

    /**
     * Handle failed payment callback
     * Updates examination and payment status to cancelled
     *
     * @param int $examinationId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function paymentFailed($examinationId)
    {
        try {
            $examination = Examination::with('patient', 'serviceItem')->find($examinationId);

            if (!$examination) {
                Log::warning('Payment failed callback for non-existent examination', ['examination_id' => $examinationId]);
                return redirect()->route('pasien.dashboard')->with('error', 'Pemeriksaan tidak ditemukan.');
            }

            // Find the most recent payment for this examination
            $payment = Payment::where('examination_id', $examination->id)
                ->whereIn('method', ['invoice', 'qris', 'virtual_account'])
                ->orderBy('created_at', 'desc')
                ->first();

            if ($payment) {
                // Update payment status to FAILED/CANCELLED if not already
                if (!in_array($payment->status, ['FAILED', 'CANCELLED', 'EXPIRED'])) {
                    $payment->update([
                        'status' => 'CANCELLED',
                        'cancelled_at' => Carbon::now()
                    ]);

                    Log::info('Payment status updated to CANCELLED', [
                        'payment_id' => $payment->id,
                        'examination_id' => $examinationId
                    ]);
                }
            }

            // Update examination status to cancelled
            if (!in_array($examination->status, ['cancelled', 'failed'])) {
                $examination->update(['status' => 'cancelled']);

                Log::info('Examination status updated to cancelled', [
                    'examination_id' => $examinationId,
                    'previous_status' => $examination->status
                ]);
            }

            // Return failed view or redirect with error message
            return view('patient.examination.payment', [
                'examination' => $examination,
                'payment' => $payment,
                'message' => 'Pembayaran gagal atau dibatalkan. Silakan coba lagi atau hubungi customer service.'
            ]);
        } catch (\Exception $e) {
            Log::error("Error processing payment failed callback: " . $e->getMessage(), [
                'examination_id' => $examinationId,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('pasien.dashboard')->with('error', 'Terjadi kesalahan saat memproses pembayaran yang gagal.');
        }
    }

    public function updatePaymentMethod(Request $request)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'payment_method' => 'required|in:online,cash'
        ]);

        $examination = Examination::findOrFail($request->examination_id);
        $examination->update([
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'cash' ? 'pending' : 'pending'
        ]);

        return response()->json(['success' => true, 'message' => 'Payment method updated']);
    }

    public function confirmCashPayment(Examination $examination)
    {
        $examination->update([
            'payment_method' => 'cash',
            'payment_status' => 'pending' // atau 'confirmed' jika langsung dikonfirmasi
        ]);

        return redirect()->back()->with('success', 'Pilihan pembayaran tunai telah dicatat. Silakan bayar di kasir klinik.');
    }
}

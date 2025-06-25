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
use Xendit\Exceptions\ApiException; // Pastikan ini sudah benar
use Xendit\Exceptions\XenditException; // Kadang beberapa error SDK menggunakan ini juga

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
     * Handles the POST request for processing online payments.
     * This method will create a Xendit Invoice (Pay-by-Link) and redirect the user.
     * It also saves the payment record to the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $examinationId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function createInvoicePayment(Request $request, $examinationId)
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

            // Validate the current examination status to ensure payment can be initiated
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

            // Check for an existing active Xendit Invoice to prevent duplicates
            $existingPayment = Payment::where('examination_id', $examination->id)
                ->where('method', 'invoice')
                ->whereIn('status', ['PENDING', 'ACTIVE']) // Statuses indicating an open invoice
                ->where('expiry_time', '>', now()) // Ensure the existing invoice is still valid
                ->first();

            if ($existingPayment) {
                Log::info('Active Xendit Invoice already exists, redirecting to existing link.', [
                    'payment_id' => $existingPayment->id,
                    'checkout_link' => $existingPayment->checkout_link
                ]);
                // Redirect user to the existing invoice link
                return redirect()->away($existingPayment->checkout_link);
            }

            // Generate a unique external ID for the Xendit Invoice
            $externalId = 'EXAM-' . $examination->id . '-INV-' . Carbon::now()->format('YmdHis') . '-' . uniqid();

            // Prepare parameters for Xendit Invoice creation
            $params = [
                'external_id' => $externalId,
                'amount' => (int) $examination->serviceItem->price, // Ensure amount is an integer
                'description' => 'Pembayaran Pemeriksaan Medis: ' . $examination->serviceItem->name,
                // Provide a fallback email if patient email is not available
                'payer_email' => $examination->patient->email ?? 'noreply@klinik.com',
                'customer' => [
                    'given_names' => $examination->patient->name,
                    'mobile_number' => $examination->patient->phone_number ?? null,
                ],
                'invoice_duration' => 86400, // 24 hours in seconds (can be adjusted)
                'callback_url' => url('/api/xendit/webhook'), // Xendit will send payment notifications here
                // Redirect URLs for user after payment success/failure
                'success_redirect_url' => url('/pasien/pembayaran/' . $examination->id . '/success'),
                'failure_redirect_url' => url('/pasien/pembayaran/' . $examination->id . '/failed'),
            ];

            Log::info('Attempting to create Xendit Invoice (Pay-by-Link) via SDK', ['params' => $params]);

            // Create the invoice using Xendit SDK
            $createInvoice = Invoice::create($params);

            // Save the payment record in your local database
            $payment = Payment::create([
                'examination_id' => $examination->id,
                'xendit_id' => $createInvoice['id'],
                'method' => 'invoice', // or 'online' if preferred for internal tracking
                'status' => $createInvoice['status'] ?? 'PENDING', // Initial status from Xendit
                'amount' => (int) $createInvoice['amount'],
                'currency' => $createInvoice['currency'] ?? 'IDR',
                'checkout_link' => $createInvoice['invoice_url'],
                'reference_code' => $externalId,
                'expiry_time' => Carbon::parse($createInvoice['expiry_date']),
            ]);

            // Update examination status to indicate pending payment
            // Assuming 'pending_payment' is a suitable status for online payments
            $examination->update(['status' => 'pending_payment']);

            Log::info('Xendit Invoice created successfully and payment saved to DB.', [
                'payment_id' => $payment->id,
                'xendit_id' => $createInvoice['id'],
                'checkout_link' => $createInvoice['invoice_url'],
                'examination_new_status' => $examination->status
            ]);

            // Redirect the user to the Xendit Invoice URL
            return redirect()->away($createInvoice['invoice_url']);
        } catch (ApiException $e) {
            // Handle Xendit API specific exceptions
            Log::error("Xendit Invoice API Error: " . $e->getMessage(), [
                'code' => $e->getCode(),
                // 'errors' => $e->getErrorData(), // This method does not exist on ApiException
                'examination_id' => $examinationId,
                'request_params' => $params ?? [],
                'xendit_error_message' => $e->getMessage() // Use getMessage() for error details
            ]);
            return redirect()->route('pasien.payment.show', ['examination' => $examinationId])->with('error', 'Gagal membuat link pembayaran: ' . $e->getMessage());
        } catch (XenditException $e) {
            // Catch broader Xendit SDK exceptions if any specific ones are not caught by ApiException
            Log::error("Xendit SDK Error (General): " . $e->getMessage(), [
                'code' => $e->getCode(),
                'examination_id' => $examinationId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('pasien.payment.show', ['examination' => $examinationId])->with('error', 'Terjadi kesalahan pada layanan pembayaran: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Catch any other unexpected exceptions
            Log::error("Unexpected error processing online payment: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'examination_id' => $examinationId
            ]);
            return redirect()->route('pasien.payment.show', ['examination' => $examinationId])->with('error', 'Terjadi kesalahan internal saat membuat link pembayaran.');
        }
    }

    /**
     * Handles successful payment callbacks from Xendit.
     * Updates examination and payment status to completed/paid.
     *
     * @param int $examinationId
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
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
                // If payment record is not found, it might be due to a race condition or direct redirect
                // Ideally, webhook should update first. For user experience, we can still show success.
                return view('patient.examination.payment', [
                    'examination' => $examination,
                    'payment' => null, // No specific payment record to show
                    'message' => 'Pembayaran Anda mungkin sudah berhasil diproses, namun detail transaksi belum terdaftar. Mohon cek status pemeriksaan Anda. '
                ])->with('warning', 'Data pembayaran tidak ditemukan, namun pemeriksaan mungkin sudah terbayar.');
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

            // Update examination status to completed if not already in a final state
            if (!in_array($examination->status, ['completed', 'paid'])) {
                $examination->update(['status' => 'scheduled', 'payment_status' => 'paid']);

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

    /**
     * This method is generally for updating payment method choice via AJAX,
     * but not for initiating actual payment processing.
     * Left for reference if needed elsewhere.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePaymentMethod(Request $request)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'payment_method' => 'required|in:online,cash'
        ]);

        $examination = Examination::findOrFail($request->examination_id);
        $examination->update([
            'payment_method' => $request->payment_method,
            // 'pending' for cash will be changed to 'pending_cash_payment' by confirmCashPayment
            'payment_status' => $request->payment_method === 'cash' ? 'pending' : 'pending'
        ]);

        return response()->json(['success' => true, 'message' => 'Payment method updated']);
    }

    /**
     * Handles the POST request for confirming cash payment.
     * This method updates the examination status to reflect cash payment choice.
     *
     * @param Request $request
     * @param int $examinationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmCashPayment(Request $request, $examinationId)
    {
        // Validate incoming request data, though examination_id is already in route param
        $validator = Validator::make(['examination_id' => $examinationId], [
            'examination_id' => 'required|exists:examinations,id',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for cash payment confirmation', ['errors' => $validator->errors()->all()]);
            return redirect()->back()->with('error', 'Data pemeriksaan tidak valid.');
        }

        try {
            $examination = Examination::find($examinationId);

            if (!$examination) {
                Log::warning('Attempted to confirm cash payment for non-existent examination', ['examination_id' => $examinationId]);
                return redirect()->back()->with('error', 'Pemeriksaan tidak ditemukan.');
            }

            // Update examination status. Use 'pending_cash_payment' to distinguish from other pending states.
            $examination->update([
                'payment_method' => 'cash',
                'payment_status' => 'pending', // Set specific status for cash payment confirmation
                'status' => 'pending_cash_payment' // Also update main status if it reflects payment state
            ]);

            // Optionally, create a Payment record for cash, if you track these.
            // This can be useful for comprehensive payment history.
            // Example:
            // Payment::create([
            //     'examination_id' => $examination->id,
            //     'method' => 'cash',
            //     'status' => 'PENDING', // Initial status for cash payment
            //     'amount' => $examination->serviceItem->price,
            //     'currency' => 'IDR',
            //     'reference_code' => 'CASH-' . $examination->id . '-' . Carbon::now()->format('YmdHis'),
            //     'checkout_link' => null, // No checkout link for cash
            //     'paid_at' => null,
            //     'expiry_time' => null,
            // ]);


            Log::info('Cash payment confirmed for examination.', [
                'examination_id' => $examinationId,
                'new_status' => $examination->status,
                'new_payment_status' => $examination->payment_status
            ]);

            return redirect()->route('pasien.examinations.index', ['examination' => $examinationId])->with('success', 'Pilihan pembayaran tunai telah dicatat. Silakan bayar di kasir klinik.');
        } catch (\Exception $e) {
            Log::error("Error confirming cash payment: " . $e->getMessage(), [
                'examination_id' => $examinationId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengkonfirmasi pembayaran tunai.');
        }
    }

    public function processCashPayment($examination, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount_received' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for cash payment processing', [
                'errors' => $validator->errors()->all(),
                'examination_id' => $examination
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $examination = Examination::with(['patient', 'serviceItem'])->find($examination);

            if (!$examination) {
                Log::warning('Attempted to process cash payment for non-existent examination', [
                    'examination_id' => $examination
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Pemeriksaan tidak ditemukan.'
                ], 404);
            }

            // Validate examination status - only allow cash payment for pending_cash_payment or created status
            if (!in_array($examination->status, ['pending_cash_payment', 'created'])) {
                Log::warning('Attempted to process cash payment for examination with invalid status', [
                    'examination_id' => $examination,
                    'current_status' => $examination->status
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tunai tidak dapat diproses untuk status pemeriksaan: ' . $examination->status
                ], 400);
            }

            // Validate amount received
            $expectedAmount = (float) $examination->serviceItem->price;
            $amountReceived = (float) $request->amount_received;

            if ($amountReceived < $expectedAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pembayaran kurang dari yang seharusnya. Expected: Rp ' . number_format($expectedAmount, 0, ',', '.') . ', Received: Rp ' . number_format($amountReceived, 0, ',', '.')
                ], 400);
            }

            // Check if there's already a completed payment for this examination
            $existingPayment = Payment::where('examination_id', $examination->id)
                ->where('status', 'PAID')
                ->first();

            if ($existingPayment) {
                Log::warning('Attempted to process cash payment for already paid examination', [
                    'examination_id' => $examination,
                    'existing_payment_id' => $existingPayment->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Pemeriksaan ini sudah terbayar sebelumnya.'
                ], 400);
            }

            // Create or update payment record
            $payment = Payment::updateOrCreate(
                [
                    'examination_id' => $examination->id,
                    'method' => 'cash'
                ],
                [
                    'status' => 'PAID',
                    'amount' => $expectedAmount,
                    'amount_received' => $amountReceived,
                    'currency' => 'IDR',
                    'reference_code' => 'CASH-' . $examination->id . '-' . Carbon::now()->format('YmdHis'),
                    'paid_at' => Carbon::now(),
                    'processed_by' => auth()->id(), // Store who processed the payment
                    'notes' => $request->notes,
                    'checkout_link' => null,
                    'xendit_id' => null,
                    'expiry_time' => null,
                ]
            );

            // Calculate change if any
            $change = $amountReceived - $expectedAmount;

            // Update examination status
            $examination->update([
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'status' => 'scheduled', // Move to scheduled after payment
                'final_price' => $expectedAmount, // Ensure final price is set
                'paid_at' => Carbon::now()
            ]);

            Log::info('Cash payment processed successfully', [
                'examination_id' => $examination,
                'payment_id' => $payment->id,
                'amount_expected' => $expectedAmount,
                'amount_received' => $amountReceived,
                'change' => $change,
                'processed_by' => auth()->id(),
                'examination_new_status' => $examination->status
            ]);

            // Return success response with payment details
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Pembayaran tunai berhasil diproses.',
            //     'data' => [
            //         'examination_id' => $examination->id,
            //         'patient_name' => $examination->patient->name,
            //         'service_name' => $examination->serviceItem->name,
            //         'amount_expected' => $expectedAmount,
            //         'amount_received' => $amountReceived,
            //         'change' => $change,
            //         'payment_id' => $payment->id,
            //         'reference_code' => $payment->reference_code,
            //         'paid_at' => $payment->paid_at->format('d/m/Y H:i:s'),
            //         'examination_status' => $examination->status
            //     ]
            // ]);
            return redirect()->route('staff.payments.receipt', ['examination' => $examination->id]);
        } catch (\Exception $e) {
            Log::error("Error processing cash payment: " . $e->getMessage(), [
                'examination_id' => $examination,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran tunai.'
            ], 500);
        }
    }

    public function getCashPaymentReceipt($examinationId)
    {
        try {
            $examination = Examination::with(['patient', 'serviceItem'])->find($examinationId);

            if (!$examination) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemeriksaan tidak ditemukan.'
                ], 404);
            }

            $payment = Payment::where('examination_id', $examination->id)
                ->where('method', 'cash')
                ->where('status', 'PAID')
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pembayaran tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'examination_id' => $examination->id,
                    'patient_name' => $examination->patient->name,
                    'patient_phone' => $examination->patient->phone_number,
                    'service_name' => $examination->serviceItem->name,
                    'service_category' => $examination->serviceItem->category->name ?? '',
                    'amount' => $payment->amount,
                    'amount_received' => $payment->amount_received,
                    'change' => $payment->amount_received - $payment->amount,
                    'reference_code' => $payment->reference_code,
                    'paid_at' => $payment->paid_at->format('d/m/Y H:i:s'),
                    'processed_by_name' => $payment->processedBy->name ?? 'System', // Assuming User relationship
                    'notes' => $payment->notes
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting cash payment receipt: " . $e->getMessage(), [
                'examination_id' => $examinationId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data receipt.'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Payment;
use App\Services\XenditService;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\CashPaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class PaymentController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Create Xendit Invoice payment
     */
    public function createInvoicePayment(Request $request, $examinationId)
    {
        try {
            $examination = $this->findExaminationWithRelations($examinationId);
            
            $this->validateExaminationForPayment($examination);
            
            $existingPayment = $this->findActivePayment($examination->id);
            if ($existingPayment) {
                Log::info('Redirecting to existing active invoice', [
                    'payment_id' => $existingPayment->id,
                    'examination_id' => $examinationId
                ]);
                return redirect()->away($existingPayment->checkout_link);
            }

            return DB::transaction(function () use ($examination) {
                $invoice = $this->xenditService->createInvoice($examination);
                
                $payment = $this->createPaymentRecord($examination, $invoice);
                
                $examination->update(['status' => 'pending_payment']);

                Log::info('Xendit Invoice created successfully', [
                    'payment_id' => $payment->id,
                    'examination_id' => $examination->id
                ]);

                return redirect()->away($invoice['invoice_url']);
            });

        } catch (Exception $e) {
            Log::error('Error creating invoice payment', [
                'examination_id' => $examinationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('pasien.payment.show', ['examination' => $examinationId])
                ->with('error', 'Gagal membuat link pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment callback
     */
    public function paymentSuccess($examinationId, Request $request)
    {
        try {
            $examination = $this->findExaminationWithRelations($examinationId);
            
            $payment = $this->findLatestPayment($examination->id);
            if (!$payment) {
                return $this->handleMissingPayment($examination);
            }

            return DB::transaction(function () use ($examination, $payment) {
                $this->updatePaymentToPaid($payment);
                $this->updateExaminationToScheduled($examination);

                return view('patient.examination.payment', [
                    'examination' => $examination,
                    'payment' => $payment,
                    'message' => 'Pembayaran berhasil! Pemeriksaan Anda telah dikonfirmasi.'
                ]);
            });

        } catch (Exception $e) {
            Log::error('Error processing payment success', [
                'examination_id' => $examinationId,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('pasien.dashboard')
                ->with('error', 'Terjadi kesalahan saat memproses konfirmasi pembayaran.');
        }
    }

    /**
     * Handle failed payment callback
     */
    public function paymentFailed($examinationId)
    {
        try {
            $examination = $this->findExaminationWithRelations($examinationId);
            
            $payment = $this->findLatestPayment($examination->id);

            return DB::transaction(function () use ($examination, $payment) {
                if ($payment && !in_array($payment->status, ['FAILED', 'CANCELLED', 'EXPIRED'])) {
                    $this->updatePaymentToCancelled($payment);
                }

                if (!in_array($examination->status, ['cancelled', 'failed'])) {
                    $examination->update(['status' => 'cancelled']);
                    Log::info('Examination cancelled due to payment failure', [
                        'examination_id' => $examination->id
                    ]);
                }

                return view('patient.examination.payment', [
                    'examination' => $examination,
                    'payment' => $payment,
                    'message' => 'Pembayaran gagal atau dibatalkan. Silakan coba lagi atau hubungi customer service.'
                ]);
            });

        } catch (Exception $e) {
            Log::error('Error processing payment failure', [
                'examination_id' => $examinationId,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('pasien.dashboard')
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran yang gagal.');
        }
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod(PaymentRequest $request)
    {
        try {
            $examination = Examination::findOrFail($request->examination_id);
            
            $examination->update([
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending'
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Payment method updated'
            ]);

        } catch (Exception $e) {
            Log::error('Error updating payment method', [
                'examination_id' => $request->examination_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment method'
            ], 500);
        }
    }

    /**
     * Confirm cash payment
     */
    public function confirmCashPayment(Request $request, $examinationId)
    {
        try {
            $examination = Examination::findOrFail($examinationId);

            $examination->update([
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'status' => 'pending_cash_payment'
            ]);

            Log::info('Cash payment confirmed', [
                'examination_id' => $examinationId,
                'new_status' => $examination->status
            ]);

            return redirect()
                ->route('pasien.examinations.index', ['examination' => $examinationId])
                ->with('success', 'Pilihan pembayaran tunai telah dicatat. Silakan bayar di kasir klinik.');

        } catch (Exception $e) {
            Log::error('Error confirming cash payment', [
                'examination_id' => $examinationId,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mengkonfirmasi pembayaran tunai.');
        }
    }

    /**
     * Process cash payment
     */
    public function processCashPayment($examinationId, CashPaymentRequest $request)
    {
        try {
            $examination = $this->findExaminationWithRelations($examinationId);
            
            $this->validateCashPayment($examination, $request->amount_received);

            return DB::transaction(function () use ($examination, $request) {
                $payment = $this->createCashPaymentRecord($examination, $request);
                
                $this->updateExaminationAfterCashPayment($examination);

                Log::info('Cash payment processed successfully', [
                    'examination_id' => $examination->id,
                    'payment_id' => $payment->id,
                    'processed_by' => auth()->id()
                ]);

                return redirect()->route('staff.payments.receipt', ['examination' => $examination->id]);
            });

        } catch (Exception $e) {
            Log::error('Error processing cash payment', [
                'examination_id' => $examinationId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran tunai.'
            ], 500);
        }
    }

    /**
     * Get cash payment receipt
     */
    public function getCashPaymentReceipt($examinationId)
    {
        try {
            $examination = Examination::with(['patient', 'serviceItem.category'])->find($examinationId);
            
            if (!$examination) {
                return view('errors.404')->with('message', 'Pemeriksaan tidak ditemukan.');
            }

            $payment = Payment::with('user')
                ->where('examination_id', $examination->id)
                ->where('status', 'PAID')
                ->first();

            if (!$payment) {
                return view('errors.404')->with('message', 'Data pembayaran tidak ditemukan.');
            }

            $receiptData = $this->prepareReceiptData($examination, $payment);
            
            return view('layouts.invoice', compact('receiptData'));

        } catch (Exception $e) {
            Log::error('Error getting cash payment receipt', [
                'examination_id' => $examinationId,
                'error' => $e->getMessage()
            ]);

            return view('errors.500')->with('message', 'Terjadi kesalahan saat mengambil data receipt.');
        }
    }

    // Private helper methods

    private function findExaminationWithRelations($examinationId)
    {
        $examination = Examination::with('patient', 'serviceItem')->find($examinationId);
        
        if (!$examination) {
            throw new Exception('Examination not found');
        }
        
        return $examination;
    }

    private function validateExaminationForPayment($examination)
    {
        $validStatuses = ['pending', 'created', 'pending_cash_payment', 'pending_payment'];
        
        if (!in_array($examination->status, $validStatuses)) {
            throw new Exception('Payment cannot be created for this examination status: ' . $examination->status);
        }
    }

    private function findActivePayment($examinationId)
    {
        return Payment::where('examination_id', $examinationId)
            ->where('method', 'invoice')
            ->whereIn('status', ['PENDING', 'ACTIVE'])
            ->where('expiry_time', '>', now())
            ->first();
    }

    private function findLatestPayment($examinationId)
    {
        return Payment::where('examination_id', $examinationId)
            ->whereIn('method', ['invoice', 'qris', 'virtual_account'])
            ->latest()
            ->first();
    }

    private function createPaymentRecord($examination, $invoice)
    {
        return Payment::create([
            'examination_id' => $examination->id,
            'xendit_id' => $invoice['id'],
            'method' => 'invoice',
            'status' => $invoice['status'] ?? 'PENDING',
            'amount' => (int) $invoice['amount'],
            'currency' => $invoice['currency'] ?? 'IDR',
            'checkout_link' => $invoice['invoice_url'],
            'reference_code' => $invoice['external_id'],
            'expiry_time' => Carbon::parse($invoice['expiry_date']),
        ]);
    }

    private function updatePaymentToPaid($payment)
    {
        if ($payment->status !== 'PAID') {
            $payment->update([
                'status' => 'PAID',
                'paid_at' => Carbon::now(),
                'amount_received' => $payment->amount,
                'user_id' => auth()->id(),
            ]);
        }
    }

    private function updatePaymentToCancelled($payment)
    {
        $payment->update([
            'status' => 'CANCELLED',
            'cancelled_at' => Carbon::now()
        ]);
    }

    private function updateExaminationToScheduled($examination)
    {
        if (!in_array($examination->status, ['completed', 'paid'])) {
            $examination->update([
                'status' => 'scheduled',
                'payment_status' => 'paid'
            ]);
        }
    }

    private function validateCashPayment($examination, $amountReceived)
    {
        $validStatuses = ['pending_cash_payment', 'created'];
        if (!in_array($examination->status, $validStatuses)) {
            throw new Exception('Cash payment cannot be processed for status: ' . $examination->status);
        }

        $expectedAmount = (float) $examination->serviceItem->price;
        if ($amountReceived < $expectedAmount) {
            throw new Exception('Payment amount is insufficient');
        }

        $existingPayment = Payment::where('examination_id', $examination->id)
            ->where('status', 'PAID')
            ->exists();

        if ($existingPayment) {
            throw new Exception('Examination is already paid');
        }
    }

    private function createCashPaymentRecord($examination, $request)
    {
        $expectedAmount = (float) $examination->serviceItem->price;
        
        return Payment::updateOrCreate(
            [
                'examination_id' => $examination->id,
                'method' => 'cash'
            ],
            [
                'status' => 'PAID',
                'amount' => $expectedAmount,
                'amount_received' => $request->amount_received,
                'currency' => 'IDR',
                'reference_code' => 'CASH-' . $examination->id . '-' . Carbon::now()->format('YmdHis'),
                'paid_at' => Carbon::now(),
                'user_id' => auth()->id(),
                'notes' => $request->notes,
                'checkout_link' => null,
                'xendit_id' => null,
                'expiry_time' => null,
            ]
        );
    }

    private function updateExaminationAfterCashPayment($examination)
    {
        $examination->update([
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'status' => 'scheduled',
            'final_price' => $examination->serviceItem->price,
            'paid_at' => Carbon::now()
        ]);
    }

    private function prepareReceiptData($examination, $payment)
    {
        return [
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
            'processed_by_name' => $payment->user->name ?? 'System',
            'notes' => $payment->notes
        ];
    }

    private function handleMissingPayment($examination)
    {
        Log::warning('Payment success callback but no payment record found', [
            'examination_id' => $examination->id
        ]);

        return view('patient.examination.payment', [
            'examination' => $examination,
            'payment' => null,
            'message' => 'Pembayaran Anda mungkin sudah berhasil diproses, namun detail transaksi belum terdaftar.'
        ])->with('warning', 'Data pembayaran tidak ditemukan, namun pemeriksaan mungkin sudah terbayar.');
    }
}
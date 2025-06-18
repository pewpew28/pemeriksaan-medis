<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment; // Sesuaikan dengan lokasi model Payment Anda
use App\Models\Examination; // Sesuaikan dengan lokasi model Examination Anda
use Carbon\Carbon;

class XenditWebhookController extends Controller
{
    /**
     * Handle Xendit webhook notifications.
     * This method receives the callback payload from Xendit and processes it.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        // 1. Log the incoming request for debugging purposes
        Log::info('Xendit Webhook received', [
            'headers' => $request->headers->all(),
            'payload' => $request->json()->all(),
            'ip_address' => $request->ip()
        ]);

        // 2. Validate X-Callback-Token for security
        // Get the Xendit secret key from environment variables
        $xenditCallbackToken = env('XENDIT_WEBHOOK_TOKEN') ?? env('XENDIT_SECRET_KEY');
        $requestCallbackToken = $request->header('X-Callback-Token');

        if (empty($xenditCallbackToken)) {
            Log::error('Xendit webhook token not configured in environment (XENDIT_WEBHOOK_TOKEN or XENDIT_SECRET_KEY).');
            return response()->json(['message' => 'Internal Server Error: Webhook token not configured.'], 500);
        }

        if ($requestCallbackToken !== $xenditCallbackToken) {
            Log::warning('Unauthorized Xendit Webhook call: Invalid X-Callback-Token.', [
                'expected_token' => $xenditCallbackToken,
                'received_token' => $requestCallbackToken,
                'ip_address' => $request->ip()
            ]);
            return response()->json(['message' => 'Unauthorized: Invalid X-Callback-Token.'], 401);
        }

        // 3. Extract necessary data from the JSON payload
        $event = $request->json()->all();

        // Log the full event payload for detailed debugging
        Log::info('Full Xendit event payload for processing:', $event);

        // Extract key fields from the webhook payload.
        // The 'id' here typically refers to the invoice ID in Xendit's system.
        $xenditId = $event['id'] ?? null;
        $externalId = $event['external_id'] ?? null;
        $status = $event['status'] ?? null;
        $amount = $event['amount'] ?? null; // The amount associated with the invoice/payment
        $paidAmount = $event['paid_amount'] ?? null; // The actual amount paid (might be different from amount for partial payments/overpayments)
        $paidAt = $event['paid_at'] ?? null;
        $paymentChannel = $event['payment_channel'] ?? null; // e.g., 'QRIS', 'BNI', 'OVO'
        $objectType = $event['object'] ?? null; // The object type, e.g., 'invoice' for invoice callbacks

        // Fields from webhook to potentially update in Payment model
        $webhookUserId = $event['user_id'] ?? null;
        $webhookMerchantName = $event['merchant_name'] ?? null;
        $webhookPayerEmail = $event['payer_email'] ?? null;
        $webhookTransactionId = $event['payment_id'] ?? null; // Use 'payment_id' from webhook for 'transaction_id' in your model


        // Log the key data extracted
        Log::info('Processing Xendit Webhook event key data', [
            'object_type' => $objectType,
            'xendit_id' => $xenditId,
            'external_id' => $externalId,
            'status' => $status,
            'amount' => $amount,
            'paid_amount' => $paidAmount, // Include paid_amount in logs
            'payment_channel' => $paymentChannel, // Include payment_channel in logs
            'webhook_user_id' => $webhookUserId,
            'webhook_merchant_name' => $webhookMerchantName,
            'webhook_payer_email' => $webhookPayerEmail,
            'webhook_transaction_id' => $webhookTransactionId,
        ]);

        // Proceed only if essential identifiers and status are present.
        // For invoice webhooks, 'id' is the invoice ID, 'external_id' is your custom ID.
        if (!$xenditId || !$externalId || !$status) {
            Log::warning('Incomplete Xendit Webhook payload received. Missing id, external_id, or status.', $event);
            return response()->json(['message' => 'Bad Request: Missing required data in payload.'], 400);
        }

        // Find the corresponding Payment record in your database.
        // Prioritize xendit_id, then fallback to reference_code (which should be external_id).
        $payment = Payment::where('xendit_id', $xenditId)
                         ->orWhere('reference_code', $externalId)
                         ->first();

        if (!$payment) {
            Log::warning('No matching Payment record found for Xendit ID or External ID. Acknowledging webhook.', [
                'xendit_id' => $xenditId,
                'external_id' => $externalId
            ]);
            // Respond with 200 OK even if record not found to prevent re-delivery attempts from Xendit.
            return response()->json(['message' => 'Payment record not found, but webhook acknowledged.'], 200);
        }

        // Update the Payment status based on Xendit's status.
        $updated = false;
        $paymentUpdateData = [];

        switch ($status) {
            case 'PAID':
            case 'SETTLED': // For some payment methods (e.g., QRIS, Direct Debit), 'SETTLED' indicates completion
                if ($payment->status !== 'PAID') {
                    $paymentUpdateData = [
                        'status' => 'PAID',
                        'paid_at' => $paidAt ? Carbon::parse($paidAt) : Carbon::now(),
                        'amount' => $paidAmount ?? $amount, // Update amount with paid_amount if available, else use amount
                        'method' => $paymentChannel ?? $payment->method, // Update with specific payment channel
                        // --- Tambahan untuk mengupdate kolom yang mungkin kosong saat invoice dibuat ---
                        'user_id' => $webhookUserId ?? $payment->user_id, // Update user_id jika ada dari webhook
                        'transaction_id' => $webhookTransactionId ?? $payment->transaction_id, // Update transaction_id
                        'merchant_name' => $webhookMerchantName ?? $payment->merchant_name, // Update merchant_name
                        'payer_email' => $webhookPayerEmail ?? $payment->payer_email, // Update payer_email
                        // ---------------------------------------------------------------------------
                    ];
                    $updated = true;
                    Log::info('Payment status will be updated to PAID.', ['payment_id' => $payment->id, 'update_data' => $paymentUpdateData]);
                } else {
                    Log::info('Payment already PAID, no update needed.', ['payment_id' => $payment->id]);
                }
                break;

            case 'EXPIRED':
                if ($payment->status !== 'EXPIRED' && $payment->status !== 'CANCELLED' && $payment->status !== 'FAILED') {
                    $paymentUpdateData = [
                        'status' => 'EXPIRED',
                        'cancelled_at' => Carbon::now(), // Use cancelled_at for expiry
                    ];
                    $updated = true;
                    Log::info('Payment status will be updated to EXPIRED.', ['payment_id' => $payment->id, 'update_data' => $paymentUpdateData]);
                }
                break;

            case 'CANCELLED':
            case 'FAILED':
                if ($payment->status !== 'CANCELLED' && $payment->status !== 'FAILED' && $payment->status !== 'EXPIRED') {
                    $paymentUpdateData = [
                        'status' => 'CANCELLED', // Use 'CANCELLED' or 'FAILED' as appropriate for your system
                        'cancelled_at' => Carbon::now(),
                    ];
                    $updated = true;
                    Log::info('Payment status will be updated to CANCELLED/FAILED.', ['payment_id' => $payment->id, 'update_data' => $paymentUpdateData]);
                }
                break;

            // Add other statuses if needed (e.g., 'PENDING', 'VOID', 'REFUNDED')
            default:
                Log::info('Unhandled Xendit payment status received for known payment record.', [
                    'payment_id' => $payment->id,
                    'xendit_status' => $status
                ]);
                // Optionally, update the local status to reflect current Xendit status even if not final.
                // $payment->update(['status' => $status]);
                break;
        }

        // Apply updates to the Payment record
        if ($updated) {
            $payment->update($paymentUpdateData);
            Log::info('Payment record updated successfully.', ['payment_id' => $payment->id, 'new_status' => $payment->status]);

            // Update associated Examination status based on the *new* payment status
            if ($payment->examination) {
                $examination = $payment->examination;
                if ($payment->status === 'PAID') {
                    if (!in_array($examination->status, ['completed', 'paid'])) {
                        $examination->update([
                            'status' => 'scheduled',
                            'payment_status' => 'paid',
                            'payment_method' => $paymentChannel,
                        ]);
                        Log::info('Associated Examination status updated to completed/paid.', ['examination_id' => $examination->id, 'new_exam_status' => $examination->status]);
                    }
                } elseif (in_array($payment->status, ['EXPIRED', 'CANCELLED', 'FAILED'])) {
                    if (!in_array($examination->status, ['cancelled', 'failed'])) {
                        $examination->update([
                            'status' => 'cancelled', // Or 'payment_failed'
                            'payment_status' => 'failed',
                        ]);
                        Log::info('Associated Examination status updated to cancelled/failed due to payment status.', ['examination_id' => $examination->id, 'new_exam_status' => $examination->status]);
                    }
                }
            }
        }

        return response()->json(['message' => 'Webhook received and processed successfully.'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // For handling dates and times

class PaymentController extends Controller
{
    private $xenditSecretKey;
    private $xenditBaseUrl;
    private $apiVersion; // New property for API version

    public function __construct()
    {
        $this->xenditSecretKey = env('XENDIT_SECRET_KEY');
        $this->xenditBaseUrl = env('XENDIT_BASE_URL', 'https://api.xendit.co');
        $this->apiVersion = '2022-07-31'; // Set the API version
    }

    /**
     * Helper to make authenticated Xendit API requests.
     */
    private function xenditClient()
    {
        return Http::withBasicAuth($this->xenditSecretKey, '')
                   ->withHeaders([ // Add the API version header here
                       'api-version' => $this->apiVersion,
                   ])
                   ->baseUrl($this->xenditBaseUrl);
    }

    /**
     * Handles the creation of a QRIS payment.
     * Corresponds to frontend call to /api/payment/qris/create
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createQrisPayment(Request $request)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'amount' => 'required|numeric|min:10000', // Xendit minimum amount for QRIS
        ]);

        $examination = Examination::find($request->examination_id);

        if (!$examination) {
            return response()->json(['success' => false, 'message' => 'Examination not found.'], 404);
        }

        // Ensure examination status is appropriate for creating new payment
        if ($examination->status !== 'pending_payment' && $examination->status !== 'created') {
             // You might want to allow re-creating if payment failed/expired
             return response()->json(['success' => false, 'message' => 'Payment cannot be created for this examination status.'], 400);
        }

        $externalId = 'EXAM#' . $examination->id . '-QRIS-' . uniqid(); // Unique external ID
        $expiresAt = Carbon::now()->addHours(24); // QRIS valid for 24 hours, matching the concept of expires_at

        try {
            $response = $this->xenditClient()->post('/qr_codes', [
                'external_id' => $externalId, // Using external_id as per Xendit docs, similar to reference_id
                'type' => 'DYNAMIC',
                'currency' => 'IDR',
                'amount' => $request->amount,
                'expires_at' => $expiresAt->toIso8601String(), // Send expires_at
                'callback_url' => env('APP_URL') . '/api/xendit/webhook',
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['id'])) {
                // Store payment details in your database
                $payment = Payment::create([
                    'examination_id' => $examination->id,
                    'xendit_id' => $responseData['id'],
                    'method' => 'qris',
                    'status' => $responseData['status'] ?? 'PENDING',
                    'amount' => $responseData['amount'],
                    'currency' => $responseData['currency'],
                    'qr_string' => $responseData['qr_string'] ?? null,
                    'qr_code_url' => $responseData['qr_code_url'] ?? null,
                    'expiry_time' => Carbon::parse($responseData['expiry_date']), // Use expiry_date from response
                    'reference_code' => $externalId,
                ]);

                // Update examination status
                $examination->update(['status' => 'pending_payment']);

                return response()->json([
                    'success' => true,
                    'message' => 'QRIS payment initiated successfully.',
                    'data' => [
                        'payment_id' => $payment->id,
                        'qr_code_url' => $payment->qr_code_url,
                        'qr_string' => $payment->qr_string,
                        'expiry_time' => $payment->expiry_time->toISOString(),
                        'amount' => $payment->amount,
                    ]
                ]);
            } else {
                Log::error('Xendit QRIS creation failed: ' . json_encode($responseData));
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Failed to create QRIS payment with Xendit.'
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception during QRIS creation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while initiating QRIS payment.'], 500);
        }
    }

    /**
     * Handles the creation of a Virtual Account payment.
     * Corresponds to frontend call to /api/payment/transfer/create
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTransferPayment(Request $request)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'amount' => 'required|numeric|min:10000', // Xendit minimum amount for VA
            // 'bank_code' => 'required|string|in:BNI,BCA,MANDIRI,BRI', // Example, allow user to choose or default
        ]);

        $examination = Examination::find($request->examination_id);

        if (!$examination) {
            return response()->json(['success' => false, 'message' => 'Examination not found.'], 404);
        }

        if ($examination->status !== 'pending_payment' && $examination->status !== 'created') {
             return response()->json(['success' => false, 'message' => 'Payment cannot be created for this examination status.'], 400);
        }

        $externalId = 'EXAM#' . $examination->id . '-VA-' . uniqid(); // Unique external ID
        $bankCode = 'BNI'; // Default or get from request if frontend offers choices
        $expirationDate = Carbon::now()->addHours(24); // VA valid for 24 hours

        try {
            $response = $this->xenditClient()->post('/virtual_accounts', [
                'external_id' => $externalId,
                'bank_code' => $bankCode,
                'name' => 'Klinik Medis Sejahtera', // Your business name or patient's name
                'expected_amount' => $request->amount,
                'is_single_use' => true,
                'expiration_date' => $expirationDate->toIso8601String(), // ISO 8601 format
                'description' => 'Pembayaran Pemeriksaan ID #' . $examination->id,
                'callback_url' => env('APP_URL') . '/api/xendit/webhook',
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['id'])) {
                // Store payment details in your database
                $payment = Payment::create([
                    'examination_id' => $examination->id,
                    'xendit_id' => $responseData['id'],
                    'method' => 'transfer',
                    'status' => $responseData['status'] ?? 'PENDING',
                    'amount' => $responseData['expected_amount'],
                    'currency' => $responseData['currency'],
                    'bank_code' => $responseData['bank_code'],
                    'account_number' => $responseData['account_number'],
                    'account_name' => $responseData['name'],
                    'expiry_time' => Carbon::parse($responseData['expiration_date']),
                    'reference_code' => $externalId,
                ]);

                // Update examination status
                $examination->update(['status' => 'pending_payment']);

                // Frontend expects an array of bank accounts
                $bankAccounts = [[
                    'bank_name' => $this->getBankName($payment->bank_code),
                    'account_name' => $payment->account_name,
                    'account_number' => $payment->account_number,
                ]];

                return response()->json([
                    'success' => true,
                    'message' => 'Virtual Account payment initiated successfully.',
                    'data' => [
                        'payment_id' => $payment->id,
                        'bank_accounts' => $bankAccounts,
                        'reference_code' => $payment->reference_code,
                        'expiry_time' => $payment->expiry_time->toISOString(),
                        'amount' => $payment->amount,
                    ]
                ]);
            } else {
                Log::error('Xendit VA creation failed: ' . json_encode($responseData));
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Failed to create Virtual Account payment with Xendit.'
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception during VA creation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while initiating Virtual Account payment.'], 500);
        }
    }

    /**
     * Handles the 'Cash' payment method (no external API call).
     * Corresponds to frontend call to /api/payment/cash/create
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCashPayment(Request $request)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $examination = Examination::find($request->examination_id);

        if (!$examination) {
            return response()->json(['success' => false, 'message' => 'Examination not found.'], 404);
        }

        if ($examination->status !== 'pending_payment' && $examination->status !== 'created') {
             return response()->json(['success' => false, 'message' => 'Payment cannot be created for this examination status.'], 400);
        }

        // For cash payment, you typically just update the examination status
        // A 'payment' record might not be necessary if it's handled completely offline
        // But for consistency, you could create a record with 'CASH' method and 'PENDING' status
        $payment = Payment::create([
            'examination_id' => $examination->id,
            'method' => 'cash',
            'status' => 'PENDING', // Will be marked PAID manually by staff
            'amount' => $request->amount,
            'currency' => 'IDR',
            'reference_code' => 'CASH-EXAM#' . $examination->id,
        ]);

        $examination->update(['status' => 'pending_cash_payment']);

        return response()->json([
            'success' => true,
            'message' => 'Cash payment option selected. Please pay at the clinic.',
            'data' => [
                'payment_id' => $payment->id,
                'clinic_info' => [
                    'name' => 'Klinik Medis Sejahtera',
                    'hours' => 'Mon-Sat: 08:00 - 20:00, Sun: 08:00 - 16:00',
                ]
            ]
        ]);
    }

    /**
     * Checks the status of a payment.
     * Corresponds to frontend call to /api/payment/status/{payment_id}
     *
     * IMPORTANT: For production, relying solely on polling this endpoint for payment status
     * is not recommended. Xendit Webhooks (handleXenditWebhook method below) are the
     * preferred way to get real-time payment updates. This polling method is more for
     * frontend UX feedback.
     *
     * @param string $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentStatus($paymentId)
    {
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found.'], 404);
        }

        // Return current status from your database
        // This assumes your webhook handler (if implemented) is updating the status
        return response()->json([
            'success' => true,
            'message' => 'Payment status retrieved.',
            'data' => [
                'status' => $payment->status === 'PAID' ? 'paid' : 'pending', // Simplify for frontend
            ]
        ]);
    }


    /**
     * Handles Xendit Webhook notifications for real-time payment status updates.
     * This is CRUCIAL for robust payment processing.
     * Xendit sends events to this endpoint (e.g., when a payment is made).
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleXenditWebhook(Request $request)
    {
        // 1. Validate X-Callback-Token for security
        // Get the X-Callback-Token from the request headers
        $xCallbackToken = $request->header('X-Callback-Token');

        // Compare it with your secret key (or a specific webhook token if configured)
        if ($xCallbackToken !== $this->xenditSecretKey) { // Or a dedicated webhook secret
            Log::warning('Xendit Webhook: Invalid X-Callback-Token received.');
            return response('Unauthorized', 401);
        }

        // 2. Log the incoming webhook payload for debugging
        Log::info('Xendit Webhook Received: ' . json_encode($request->all()));

        // 3. Process the webhook event
        $event = $request->all();

        // Check if the event is for paid virtual account or QR code
        $xenditId = $event['id'] ?? null;
        $status = $event['status'] ?? null; // For VA
        $eventStatus = $event['event'] ?? null; // For QR

        if (!$xenditId) {
            Log::warning('Xendit Webhook: No Xendit ID found in payload.');
            return response('Bad Request: Missing ID', 400);
        }

        $payment = Payment::where('xendit_id', $xenditId)->first();

        if (!$payment) {
            Log::warning('Xendit Webhook: No matching internal payment found for Xendit ID: ' . $xenditId);
            return response('Not Found: Payment ID not recognized', 404);
        }

        // Handle different event types from Xendit
        if ($eventStatus === 'QR_CODE_PAID' || $status === 'PAID') { // 'status' for VA, 'event' for QR_CODE
            if ($payment->status !== 'PAID') {
                $payment->update(['status' => 'PAID']);
                $payment->examination->update(['status' => 'paid']);
                Log::info("Payment ID: {$payment->id} (Xendit ID: {$xenditId}) marked as PAID.");
            }
        } elseif ($eventStatus === 'QR_CODE_EXPIRED' || $status === 'EXPIRED') {
            if ($payment->status !== 'EXPIRED') {
                $payment->update(['status' => 'EXPIRED']);
                $payment->examination->update(['status' => 'expired_payment']);
                Log::info("Payment ID: {$payment->id} (Xendit ID: {$xenditId}) marked as EXPIRED.");
            }
        }
        // Add more conditions for other statuses (e.g., FAILED, REFUNDED) as needed

        // 4. Acknowledge receipt to Xendit
        return response('Webhook Handled', 200);
    }

    /**
     * Helper to get human-readable bank name from bank code.
     */
    private function getBankName($bankCode)
    {
        switch ($bankCode) {
            case 'BNI': return 'Bank Negara Indonesia (BNI)';
            case 'BCA': return 'Bank Central Asia (BCA)';
            case 'MANDIRI': return 'Bank Mandiri';
            case 'BRI': return 'Bank Rakyat Indonesia (BRI)';
            // Add other banks as needed
            default: return $bankCode;
        }
    }
}
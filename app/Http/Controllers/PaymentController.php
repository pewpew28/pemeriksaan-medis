<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;

class PaymentController extends Controller
{
    private $xenditSecretKey;
    private $xenditBaseUrl;
    private $apiVersion;

    public function __construct()
    {
        $this->xenditSecretKey = env('XENDIT_SECRET_KEY');
        $this->xenditBaseUrl = env('XENDIT_BASE_URL', 'https://api.xendit.co');
        $this->apiVersion = '2022-07-31';

        // Validasi konfigurasi
        if (!$this->xenditSecretKey) {
            Log::error('Xendit secret key not configured in environment');
        }
    }

    public function createQrisPayment(Request $request)
    {
        try {
            // 1. Validasi input
            $validator = Validator::make($request->all(), [
                'examination_id' => 'required|exists:examinations,id',
                'amount' => 'required|numeric|min:1000|max:50000000',
            ]);

            if ($validator->fails()) {
                Log::warning('QRIS payment validation failed', [
                    'errors' => $validator->errors(),
                    'request_data' => $request->only(['examination_id', 'amount'])
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // 2. Cari examination
            $exam = Examination::find($request->examination_id);
            if (!$exam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Examination not found'
                ], 404);
            }

            // 3. Validasi status examination
            if (!in_array($exam->status, ['pending', 'created', 'pending_cash_payment', 'pending_payment'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot be created for this examination status: ' . $exam->status
                ], 400);
            }

            // 4. Cek payment yang sudah ada dan masih aktif
            $existingPayment = Payment::where('examination_id', $exam->id)
                ->where('method', 'qris')
                ->whereIn('status', ['PENDING', 'ACTIVE'])
                ->where('expiry_time', '>', now())
                ->first();

            if ($existingPayment) {
                Log::info('Active QRIS payment already exists', [
                    'examination_id' => $exam->id,
                    'existing_payment_id' => $existingPayment->id,
                    'examination_status' => $exam->status
                ]);

                $exam->update([
                    'status' => 'pending_payment'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Active QRIS payment already exists',
                    'data' => [
                        'payment_id' => $existingPayment->id,
                        'qr_code_url' => $existingPayment->qr_code_url,
                        'qr_string' => $existingPayment->qr_string,
                        'expiry_time' => $existingPayment->expiry_time->toISOString(),
                        'amount' => $existingPayment->amount,
                    ]
                ]);
            }

            // 5. Buat external ID yang konsisten
            $externalId = 'EXAM-' . $exam->id . '-QRIS-' . time() . '-' . uniqid();

            // 6. Prepare request data
            $requestData = [
                'external_id' => $externalId,
                'type' => 'DYNAMIC',
                'amount' => (int) $request->amount,
                'currency' => 'IDR',
                'callback_url' => url('/api/xendit/webhook'),
                'reference_id' => $externalId,
            ];

            Log::info('Creating QRIS payment', [
                'examination_id' => $exam->id,
                'amount' => $request->amount,
                'external_id' => $externalId,
                'request_data' => $requestData
            ]);

            // 7. Call Xendit API
            $response = Http::timeout(30)
                ->withBasicAuth($this->xenditSecretKey, '')
                ->withHeaders([
                    'api-version' => $this->apiVersion,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->xenditBaseUrl . '/qr_codes', $requestData);

            $data = $response->json();
            // dd($data);

            Log::info('Xendit QRIS API response', [
                'status_code' => $response->status(),
                'response_data' => $data,
                'headers' => $response->headers()
            ]);

            // 8. Handle successful response
            if ($response->successful() && isset($data['id'])) {
                // Parse expiry time dengan fallback yang lebih baik
                $expiryTime = $this->parseExpiryTime($data);

                $payment = Payment::create([
                    'examination_id' => $exam->id,
                    'xendit_id' => $data['id'],
                    'method' => 'qris',
                    'status' => $data['status'] ?? 'ACTIVE',
                    'amount' => (int) $data['amount'],
                    'currency' => $data['currency'] ?? 'IDR',
                    'qr_string' => $data['qr_string'] ?? null,
                    'qr_code_url' => $data['qr_code_url'] ?? null,
                    'expiry_time' => $expiryTime,
                    'reference_code' => $externalId,
                ]);

                // Update examination status
                $exam->update(['status' => 'pending']);

                Log::info('QRIS payment created successfully', [
                    'payment_id' => $payment->id,
                    'xendit_id' => $data['id'],
                    'examination_id' => $exam->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'QRIS payment created successfully.',
                    'data' => [
                        'payment_id' => $payment->id,
                        'qr_code_url' => $payment->qr_code_url,
                        'qr_string' => $payment->qr_string,
                        'expiry_time' => $payment->expiry_time->toISOString(),
                        'amount' => $payment->amount,
                        'external_id' => $externalId,
                    ]
                ]);
            }

            // 9. Handle error response
            $this->logXenditError('QRIS', $response, $data, $requestData);

            return response()->json([
                'success' => false,
                'message' => $this->getErrorMessage($data),
                'error_code' => $response->status()
            ], $this->getResponseStatusCode($response));
        } catch (RequestException | ConnectionException $e) {
            return $this->handleNetworkError($e, 'QRIS', $request->examination_id ?? null);
        } catch (Exception $e) {
            return $this->handleGeneralException($e, 'QRIS', $request->examination_id ?? null);
        }
    }

    public function createTransferPayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'examination_id' => 'required|exists:examinations,id',
                'amount' => 'required|numeric|min:10000|max:50000000',
                'bank_code' => 'sometimes|string|in:BNI,BCA,MANDIRI,BRI'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $examination = Examination::findOrFail($request->examination_id);

            if (!in_array($examination->status, ['pending', 'created', 'pending_cash_payment', 'pending_payment'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot be created for this examination status: ' . $examination->status
                ], 400);
            }

            // Cek existing VA payment
            $existingPayment = Payment::where('examination_id', $examination->id)
                ->where('method', 'transfer')
                ->whereIn('status', ['PENDING', 'ACTIVE'])
                ->where('expiry_time', '>', now())
                ->first();

            if ($existingPayment) {
                $examination->update([
                    'status' => 'pending_payment'
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Active Virtual Account payment already exists',
                    'data' => [
                        'payment_id' => $existingPayment->id,
                        'bank_accounts' => [[
                            'bank_name' => $this->getBankName($existingPayment->bank_code),
                            'account_name' => $existingPayment->account_name,
                            'account_number' => $existingPayment->account_number,
                        ]],
                        'reference_code' => $existingPayment->reference_code,
                        'expiry_time' => $existingPayment->expiry_time->toISOString(),
                        'amount' => $existingPayment->amount,
                    ]
                ]);
            }

            $externalId = 'EXAM-' . $examination->id . '-VA-' . time() . '-' . uniqid();
            $bankCode = $request->bank_code ?? 'BNI';
            $expirationDate = Carbon::now()->addHours(24);

            $requestData = [
                'external_id' => $externalId,
                'bank_code' => $bankCode,
                'name' => 'Klinik Medis Sejahtera',
                'expected_amount' => (int) $request->amount,
                'is_single_use' => true,
                // Tambahkan baris ini:
                'is_closed' => true, // Diperlukan jika expected_amount diberikan
                'expiration_date' => $expirationDate->toIso8601String(),
                'callback_url' => url('/api/xendit/webhook'),
            ];

            Log::info('Creating Virtual Account payment', [
                'examination_id' => $examination->id,
                'bank_code' => $bankCode,
                'amount' => $request->amount,
                'external_id' => $externalId
            ]);

            $response = Http::timeout(30)
                ->withBasicAuth($this->xenditSecretKey, '')
                ->withHeaders([
                    'api-version' => $this->apiVersion,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->xenditBaseUrl . '/callback_virtual_accounts', $requestData);

            $responseData = $response->json();

            Log::info('Xendit VA API response', [
                'status_code' => $response->status(),
                'response_data' => $responseData
            ]);

            if ($response->successful() && isset($responseData['id'])) {
                $payment = Payment::create([
                    'examination_id' => $examination->id,
                    'xendit_id' => $responseData['id'],
                    'method' => 'transfer',
                    'status' => $responseData['status'] ?? 'PENDING',
                    'amount' => (int) $responseData['expected_amount'],
                    'currency' => $responseData['currency'] ?? 'IDR',
                    'bank_code' => $responseData['bank_code'],
                    'account_number' => $responseData['account_number'],
                    'account_name' => $responseData['name'],
                    'expiry_time' => Carbon::parse($responseData['expiration_date']),
                    'reference_code' => $externalId,
                ]);

                $examination->update(['status' => 'pending']);

                Log::info('Virtual Account payment created successfully', [
                    'payment_id' => $payment->id,
                    'xendit_id' => $responseData['id']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Virtual Account payment initiated successfully.',
                    'data' => [
                        'payment_id' => $payment->id,
                        'bank_accounts' => [[
                            'bank_name' => $this->getBankName($payment->bank_code),
                            'account_name' => $payment->account_name,
                            'account_number' => $payment->account_number,
                        ]],
                        'reference_code' => $payment->reference_code,
                        'expiry_time' => $payment->expiry_time->toISOString(),
                        'amount' => $payment->amount,
                    ]
                ]);
            }

            $this->logXenditError('Virtual Account', $response, $responseData, $requestData);

            return response()->json([
                'success' => false,
                'message' => $this->getErrorMessage($responseData)
            ], $this->getResponseStatusCode($response));
        } catch (RequestException | ConnectionException $e) {
            return $this->handleNetworkError($e, 'Virtual Account', $request->examination_id ?? null);
        } catch (Exception $e) {
            return $this->handleGeneralException($e, 'Virtual Account', $request->examination_id ?? null);
        }
    }

    public function createCashPayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'examination_id' => 'required|exists:examinations,id',
                'amount' => 'required|numeric|min:0|max:50000000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $examination = Examination::findOrFail($request->examination_id);

            if (!in_array($examination->status, ['pending', 'created', 'pending_cash_payment', 'pending_payment'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot be created for this examination status: ' . $examination->status
                ], 400);
            }

            // Cek existing cash payment
            $existingPayment = Payment::where('examination_id', $examination->id)
                ->where('method', 'cash')
                ->whereIn('status', ['PENDING'])
                ->first();

            if ($existingPayment) {
                $examination->update(['status' => 'pending_cash_payment']);
                return response()->json([
                    'success' => true,
                    'message' => 'Cash payment option already selected.',
                    'data' => [
                        'payment_id' => $existingPayment->id,
                        'clinic_info' => [
                            'name' => 'Klinik Medis Sejahtera',
                            'hours' => 'Mon-Sat: 08:00 - 20:00, Sun: 08:00 - 16:00',
                        ]
                    ]
                ]);
            }

            $payment = Payment::create([
                'examination_id' => $examination->id,
                'method' => 'cash',
                'status' => 'PENDING',
                'amount' => (int) $request->amount,
                'currency' => 'IDR',
                'reference_code' => 'CASH-EXAM-' . $examination->id . '-' . time(),
            ]);

            $examination->update(['status' => 'pending_cash_payment']);

            Log::info('Cash payment created', [
                'payment_id' => $payment->id,
                'examination_id' => $examination->id,
                'amount' => $request->amount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cash payment option selected. Please pay at the clinic.',
                'data' => [
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'reference_code' => $payment->reference_code,
                    'clinic_info' => [
                        'name' => 'Klinik Medis Sejahtera',
                        'hours' => 'Mon-Sat: 08:00 - 20:00, Sun: 08:00 - 16:00',
                    ]
                ]
            ]);
        } catch (Exception $e) {
            return $this->handleGeneralException($e, 'Cash Payment', $request->examination_id ?? null);
        }
    }

    public function getPaymentStatus($paymentId)
    {
        try {
            $payment = Payment::with('examination')->find($paymentId);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found.'
                ], 404);
            }

            // Sync status dengan Xendit jika diperlukan
            if ($payment->xendit_id && !in_array($payment->status, ['PAID', 'EXPIRED', 'CANCELLED'])) {
                $this->syncPaymentStatusWithXendit($payment);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment status retrieved.',
                'data' => [
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                    'method' => $payment->method,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'expiry_time' => $payment->expiry_time?->toISOString(),
                    'examination_status' => $payment->examination->status ?? null,
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error getting payment status', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving payment status'
            ], 500);
        }
    }

    public function handleXenditWebhook(Request $request)
    {
        try {
            // Validasi X-Callback-Token
            $xCallbackToken = $request->header('X-Callback-Token');

            if ($xCallbackToken !== $this->xenditSecretKey) {
                Log::warning('Xendit Webhook: Invalid X-Callback-Token', [
                    'received_token' => substr($xCallbackToken, 0, 10) . '***',
                    'ip' => $request->ip()
                ]);
                return response('Unauthorized', 401);
            }

            $event = $request->all();
            Log::info('Xendit Webhook Received', ['payload' => $event]);

            // Ekstrak data dari webhook
            $xenditId = $event['id'] ?? null;
            $status = $event['status'] ?? null;
            $eventType = $event['event'] ?? null;
            $externalId = $event['external_id'] ?? null;

            if (!$xenditId) {
                Log::warning('Xendit Webhook: Missing Xendit ID in payload');
                return response('Bad Request: Missing ID', 400);
            }

            // Cari payment berdasarkan xendit_id
            $payment = Payment::where('xendit_id', $xenditId)->first();

            if (!$payment) {
                Log::warning('Xendit Webhook: Payment not found', [
                    'xendit_id' => $xenditId,
                    'external_id' => $externalId
                ]);
                return response('Not Found: Payment not recognized', 404);
            }

            // Update status berdasarkan event
            $updated = $this->updatePaymentStatus($payment, $eventType, $status, $event);

            if ($updated) {
                Log::info('Payment status updated via webhook', [
                    'payment_id' => $payment->id,
                    'old_status' => $payment->getOriginal('status'),
                    'new_status' => $payment->status,
                    'event_type' => $eventType
                ]);
            }

            return response('Webhook processed successfully', 200);
        } catch (Exception $e) {
            Log::error('Xendit Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            return response('Internal Server Error', 500);
        }
    }

    // Helper Methods

    private function parseExpiryTime($data)
    {
        if (isset($data['expires_at'])) {
            return Carbon::parse($data['expires_at']);
        } elseif (isset($data['expiry_date'])) {
            return Carbon::parse($data['expiry_date']);
        } else {
            return now()->addHour(); // Default 1 jam
        }
    }

    private function logXenditError($type, $response, $data, $requestData)
    {
        Log::error("Xendit {$type} API error", [
            'status_code' => $response->status(),
            'response_data' => $data,
            'request_data' => $requestData,
            'headers' => $response->headers()
        ]);
    }

    private function getErrorMessage($data)
    {
        if (isset($data['message'])) {
            return $data['message'];
        } elseif (isset($data['error_code'])) {
            return $data['error_code'];
        } elseif (isset($data['errors']) && is_array($data['errors']) && count($data['errors']) > 0) {
            return $data['errors'][0]['message'] ?? 'Unknown error';
        }

        return 'Failed to process payment';
    }

    private function getResponseStatusCode($response)
    {
        $statusCode = $response->status();
        return ($statusCode >= 400 && $statusCode < 500) ? $statusCode : 500;
    }

    private function handleNetworkError($exception, $paymentType, $examinationId)
    {
        Log::error("Network error when calling Xendit API for {$paymentType}", [
            'error' => $exception->getMessage(),
            'examination_id' => $examinationId,
            'type' => get_class($exception)
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Network error. Please check your connection and try again.'
        ], 503);
    }

    private function handleGeneralException($exception, $paymentType, $examinationId)
    {
        Log::error("Unexpected error in {$paymentType} payment", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'examination_id' => $examinationId
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred. Please try again.'
        ], 500);
    }

    private function syncPaymentStatusWithXendit($payment)
    {
        try {
            $endpoint = match ($payment->method) {
                'qris' => "/qr_codes/{$payment->xendit_id}",
                'transfer' => "/callback_virtual_accounts/{$payment->xendit_id}",
                default => null
            };

            if (!$endpoint) return;

            $response = Http::timeout(15)
                ->withBasicAuth($this->xenditSecretKey, '')
                ->get($this->xenditBaseUrl . $endpoint);

            if ($response->successful()) {
                $data = $response->json();
                $newStatus = $data['status'] ?? null;

                if ($newStatus && $newStatus !== $payment->status) {
                    $payment->update(['status' => $newStatus]);

                    // Update examination status
                    if ($newStatus === 'PAID') {
                        $payment->examination->update(['status' => 'paid']);
                    } elseif ($newStatus === 'EXPIRED') {
                        $payment->examination->update(['status' => 'expired_payment']);
                    }
                }
            }
        } catch (Exception $e) {
            Log::warning('Failed to sync payment status with Xendit', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function updatePaymentStatus($payment, $eventType, $status, $eventData)
    {
        $updated = false;
        $oldStatus = $payment->status;

        // Mapping event types ke status
        if (in_array($eventType, ['QR_CODE_PAID', 'virtual_account.paid']) || $status === 'PAID') {
            if ($payment->status !== 'PAID') {
                $payment->update(['status' => 'PAID']);
                $payment->examination->update(['status' => 'paid']);
                $updated = true;
            }
        } elseif (in_array($eventType, ['QR_CODE_EXPIRED', 'virtual_account.expired']) || $status === 'EXPIRED') {
            if ($payment->status !== 'EXPIRED') {
                $payment->update(['status' => 'EXPIRED']);
                $payment->examination->update(['status' => 'expired_payment']);
                $updated = true;
            }
        } elseif ($eventType === 'virtual_account.created' || $status === 'PENDING') {
            if ($payment->status !== 'PENDING') {
                $payment->update(['status' => 'PENDING']);
                $updated = true;
            }
        }

        return $updated;
    }

    private function getBankName($bankCode)
    {
        return match ($bankCode) {
            'BNI' => 'Bank Negara Indonesia (BNI)',
            'BCA' => 'Bank Central Asia (BCA)',
            'MANDIRI' => 'Bank Mandiri',
            'BRI' => 'Bank Rakyat Indonesia (BRI)',
            'PERMATA' => 'Bank Permata',
            'SAHABAT_SAMPOERNA' => 'Bank Sahabat Sampoerna',
            default => $bankCode,
        };
    }
}

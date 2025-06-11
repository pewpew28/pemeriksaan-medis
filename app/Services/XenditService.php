<?php
// app/Services/XenditService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class XenditService
{
    protected $apiKey;
    protected $baseUrl;
    protected $webhookToken;

    public function __construct()
    {
        $this->apiKey = config('services.xendit.secret_key');
        $this->baseUrl = config('services.xendit.base_url', 'https://api.xendit.co');
        $this->webhookToken = config('services.xendit.webhook_token');
    }

    /**
     * Create QRIS payment
     */
    public function createQRISPayment(array $data): array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->post($this->baseUrl . '/qr_codes', [
                    'external_id' => $data['external_id'],
                    'type' => 'DYNAMIC',
                    'callback_url' => $data['callback_url'],
                    'amount' => $data['amount'],
                    'description' => $data['description'] ?? '',
                    'expires_at' => $data['expires_at'] ?? null
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('QRIS Payment Created', [
                    'external_id' => $data['external_id'],
                    'qr_id' => $responseData['id']
                ]);

                return $responseData;
            }

            throw new Exception('Xendit API Error: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Failed to create QRIS payment', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Create Virtual Account
     */
    public function createVirtualAccount(array $data): array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->post($this->baseUrl . '/virtual_accounts', [
                    'external_id' => $data['external_id'],
                    'bank_code' => $data['bank_code'],
                    'name' => $data['name'],
                    'expected_amount' => $data['expected_amount'],
                    'description' => $data['description'] ?? '',
                    'expiration_date' => $data['expiration_date'] ?? null,
                    'is_single_use' => true,
                    'is_closed' => true
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Virtual Account Created', [
                    'external_id' => $data['external_id'],
                    'va_id' => $responseData['id'],
                    'account_number' => $responseData['account_number'],
                    'bank_code' => $responseData['bank_code']
                ]);

                return $responseData;
            }

            throw new Exception('Xendit API Error: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Failed to create virtual account', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Create E-Wallet Payment
     */
    public function createEWalletPayment(array $data): array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->post($this->baseUrl . '/ewallets/charges', [
                    'reference_id' => $data['reference_id'],
                    'currency' => $data['currency'] ?? 'IDR',
                    'amount' => $data['amount'],
                    'checkout_method' => $data['checkout_method'] ?? 'ONE_TIME_PAYMENT',
                    'channel_code' => $data['channel_code'], // OVO, DANA, LINKAJA, SHOPEEPAY
                    'channel_properties' => $data['channel_properties'] ?? [],
                    'customer' => $data['customer'] ?? null,
                    'basket' => $data['basket'] ?? null,
                    'metadata' => $data['metadata'] ?? null
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('E-Wallet Payment Created', [
                    'reference_id' => $data['reference_id'],
                    'charge_id' => $responseData['id'],
                    'channel_code' => $data['channel_code']
                ]);

                return $responseData;
            }

            throw new Exception('Xendit API Error: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Failed to create e-wallet payment', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Create Invoice
     */
    public function createInvoice(array $data): array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->post($this->baseUrl . '/v2/invoices', [
                    'external_id' => $data['external_id'],
                    'amount' => $data['amount'],
                    'description' => $data['description'],
                    'invoice_duration' => $data['invoice_duration'] ?? 86400, // 24 hours
                    'customer' => $data['customer'] ?? null,
                    'customer_notification_preference' => $data['notification_preference'] ?? [
                        'invoice_created' => ['email'],
                        'invoice_reminder' => ['email'],
                        'invoice_paid' => ['email']
                    ],
                    'success_redirect_url' => $data['success_redirect_url'] ?? null,
                    'failure_redirect_url' => $data['failure_redirect_url'] ?? null,
                    'currency' => $data['currency'] ?? 'IDR',
                    'items' => $data['items'] ?? null,
                    'fees' => $data['fees'] ?? null
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Invoice Created', [
                    'external_id' => $data['external_id'],
                    'invoice_id' => $responseData['id'],
                    'invoice_url' => $responseData['invoice_url']
                ]);

                return $responseData;
            }

            throw new Exception('Xendit API Error: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Failed to create invoice', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Get Payment Status
     */
    public function getPaymentStatus(string $paymentId, string $type = 'invoice'): array
    {
        try {
            $endpoint = match($type) {
                'invoice' => '/v2/invoices/' . $paymentId,
                'qris' => '/qr_codes/' . $paymentId,
                'va' => '/virtual_accounts/' . $paymentId,
                'ewallet' => '/ewallets/charges/' . $paymentId,
                default => '/v2/invoices/' . $paymentId
            };

            $response = Http::withBasicAuth($this->apiKey, '')
                ->get($this->baseUrl . $endpoint);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Xendit API Error: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Failed to get payment status', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId,
                'type' => $type
            ]);
            throw $e;
        }
    }

    /**
     * Get QRIS Payment Status
     */
    public function getQRISStatus(string $qrisId): array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->get($this->baseUrl . '/qr_codes/' . $qrisId);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('QRIS Status Retrieved', [
                    'qris_id' => $qrisId,
                    'status' => $responseData['status'] ?? 'unknown'
                ]);

                return $responseData;
            }

            throw new Exception('Xendit API Error: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Failed to get QRIS status', [
                'error' => $e->getMessage(),
                'qris_id' => $qrisId
            ]);
            throw $e;
        }
    }

    /**
     * Get Virtual Account Status
     */
    public function getVirtualAccountStatus(string $vaId): array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->get($this->baseUrl . '/virtual_accounts/' . $vaId);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Virtual Account Status Retrieved', [
                    'va_id' => $vaId,
                    'status' => $responseData['status'] ?? 'unknown'
                ]);

                return $responseData;
            }

            throw new Exception('Xendit API Error: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Failed to get virtual account status', [
                'error' => $e->getMessage(),
                'va_id' => $vaId
            ]);
            throw $e;
        }
    }

    /**
     * Verify Webhook Signature
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        try {
            $computedSignature = hash_hmac('sha256', $payload, $this->webhookToken);
            return hash_equals($computedSignature, $signature);
        } catch (Exception $e) {
            Log::error('Failed to verify webhook signature', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Process Webhook
     */
    public function processWebhook(array $webhookData): array
    {
        try {
            Log::info('Processing Xendit Webhook', [
                'webhook_type' => $webhookData['event_type'] ?? 'unknown',
                'external_id' => $webhookData['external_id'] ?? null,
                'id' => $webhookData['id'] ?? null
            ]);

            // Return processed webhook data
            return [
                'status' => 'success',
                'data' => $webhookData
            ];

        } catch (Exception $e) {
            Log::error('Failed to process webhook', [
                'error' => $e->getMessage(),
                'webhook_data' => $webhookData
            ]);
            throw $e;
        }
    }

    /**
     * Get Available Banks for Virtual Account
     */
    public function getAvailableBanks(): array
    {
        return [
            'BNI' => 'Bank Negara Indonesia',
            'BRI' => 'Bank Rakyat Indonesia',
            'MANDIRI' => 'Bank Mandiri',
            'PERMATA' => 'Bank Permata',
            'BCA' => 'Bank Central Asia',
            'SAHABAT_SAMPOERNA' => 'Bank Sahabat Sampoerna'
        ];
    }

    /**
     * Get Available E-Wallet Channels
     */
    public function getAvailableEWallets(): array
    {
        return [
            'OVO' => 'OVO',
            'DANA' => 'DANA',
            'LINKAJA' => 'LinkAja',
            'SHOPEEPAY' => 'ShopeePay',
            'ASTRAPAY' => 'AstraPay',
            'JENIUSPAY' => 'Jenius Pay'
        ];
    }

    /**
     * Format amount to cents (Xendit expects amount in smallest currency unit)
     */
    public function formatAmount(float $amount): int
    {
        return (int) ($amount * 100);
    }

    /**
     * Format amount from cents to rupiah
     */
    public function formatAmountFromCents(int $cents): float
    {
        return $cents / 100;
    }
}
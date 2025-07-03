<?php

// app/Services/XenditService.php
namespace App\Services;

use Xendit\Xendit;
use Xendit\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Xendit\Exceptions\ApiException;

class XenditService
{
    public function __construct()
    {
        $secretKey = config('services.xendit.secret_key');

        if (empty($secretKey)) {
            throw new \Exception('Xendit secret key not configured');
        }

        Xendit::setApiKey($secretKey);
    }

    public function createInvoice($examination)
    {
        $externalId = 'EXAM-' . $examination->id . '-INV-' . Carbon::now()->format('YmdHis') . '-' . uniqid();

        // Get authenticated user - gunakan Auth::user() dengan huruf kapital
        $user = Auth::user();
        // Check if user is authenticated
        
        $baseUrl = $this->getBaseUrlByRole($user);
        
        $params = [
            'external_id' => $externalId,
            'amount' => (int) $examination->serviceItem->price,
            'description' => 'Pembayaran Pemeriksaan Medis: ' . $examination->serviceItem->name,
            'payer_email' => $examination->patient->email ?? 'noreply@klinik.com',
            'customer' => [
                'given_names' => $examination->patient->name,
                'mobile_number' => $examination->patient->phone_number ?? null,
            ],
            'invoice_duration' => 86400,
            'callback_url' => url('/api/xendit/webhook'),
            'success_redirect_url' => url($baseUrl . '/pembayaran/' . $examination->id . '/success'),
            'failure_redirect_url' => url($baseUrl . '/pembayaran/' . $examination->id . '/failed'),
        ];

        Log::info('Creating Xendit Invoice', ['params' => $params]);

        try {
            return Invoice::create($params);
        } catch (ApiException $e) {
            Log::error('Xendit API Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            throw $e;
        }
    }

    private function getBaseUrlByRole($user)
    {
        if (!$user) {
            return '/pasien';
        }

        return match ($user->role) {
            'admin' => '/staff',
            'cs', 'customer_service' => '/staff',
            'pasien' => '/pasien',
            default => '/pasien'
        };
    }
}
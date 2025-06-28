<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'examination_id',
        'amount',
        'amount_received',
        'currency',
        'description',
        'method',
        'status',
        'xendit_id',
        'external_id',
        'user_id',
        'transaction_id',
        'reference_code',
        'merchant_name',
        'merchant_profile_picture_url',
        'payer_email',
        'customer_given_names',
        'customer_mobile_number',
        'invoice_url',
        'checkout_link',
        'success_redirect_url',
        'failure_redirect_url',
        'should_exclude_credit_card',
        'should_send_email',
        'available_banks',
        'available_retail_outlets',
        'available_ewallets',
        'available_qr_codes',
        'available_direct_debits',
        'available_paylaters',
        'qr_string',
        'qr_code_url',
        'bank_code',
        'account_number',
        'account_name',
        'notes',
        'metadata',
        'paid_at',
        'expiry_date',
        'expiry_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'should_exclude_credit_card' => 'boolean',
        'should_send_email' => 'boolean',
        'available_banks' => 'array',
        'available_retail_outlets' => 'array',
        'available_ewallets' => 'array',
        'available_qr_codes' => 'array',
        'available_direct_debits' => 'array',
        'available_paylaters' => 'array',
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'expiry_date' => 'datetime',
        'expiry_time' => 'datetime',
    ];

    /**
     * Payment methods enum values
     */
    const METHODS = [
        'CASH' => 'cash',
        'QRIS' => 'qris',
        'TRANSFER' => 'transfer',
        'INVOICE' => 'invoice',
        'EWALLET' => 'ewallet',
        'RETAIL_OUTLET' => 'retail_outlet',
        'DIRECT_DEBIT' => 'direct_debit',
        'PAYLATER' => 'paylater',
        'CREDIT_CARD' => 'credit_card',
    ];

    /**
     * Payment status enum values
     */
    const STATUSES = [
        'PENDING' => 'pending',
        'ACTIVE' => 'active',
        'PAID' => 'paid',
        'FAILED' => 'failed',
        'REFUNDED' => 'refunded',
        'EXPIRED' => 'expired',
        'CANCELLED' => 'cancelled',
        'CANCELLED_BY_USER_CHANGE' => 'cancelled_by_user_change',
        'PENDING_CASH_PAYMENT' => 'pending_cash_payment',
    ];

    /**
     * Get the examination that owns the payment.
     */
    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class, 'examination_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo( User::class, 'user_id');
    }

    /**
     * Scope a query to only include payments with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include payments with specific method.
     */
    public function scopeMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUSES['PENDING']);
    }

    /**
     * Scope a query to only include paid payments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUSES['PAID']);
    }

    /**
     * Scope a query to only include failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUSES['FAILED']);
    }

    /**
     * Scope a query to only include expired payments.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUSES['EXPIRED']);
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUSES['PENDING'];
    }

    /**
     * Check if payment is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUSES['PAID'];
    }

    /**
     * Check if payment is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUSES['FAILED'];
    }

    /**
     * Check if payment is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUSES['EXPIRED'] ||
            ($this->expiry_date && Carbon::now()->isAfter($this->expiry_date));
    }

    /**
     * Check if payment is cancelled.
     */
    public function isCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUSES['CANCELLED'],
            self::STATUSES['CANCELLED_BY_USER_CHANGE']
        ]);
    }

    /**
     * Mark payment as paid.
     */
    public function markAsPaid(): bool
    {
        $this->status = self::STATUSES['PAID'];
        $this->paid_at = Carbon::now();
        return $this->save();
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed(): bool
    {
        $this->status = self::STATUSES['FAILED'];
        return $this->save();
    }

    /**
     * Mark payment as expired.
     */
    public function markAsExpired(): bool
    {
        $this->status = self::STATUSES['EXPIRED'];
        return $this->save();
    }

    /**
     * Mark payment as cancelled.
     */
    public function markAsCancelled(): bool
    {
        $this->status = self::STATUSES['CANCELLED'];
        return $this->save();
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->amount, 2, ',', '.');
    }

    /**
     * Get formatted amount in IDR format.
     */
    public function getFormattedAmountIdrAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get human readable status.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUSES['PENDING'] => 'Menunggu Pembayaran',
            self::STATUSES['ACTIVE'] => 'Aktif',
            self::STATUSES['PAID'] => 'Sudah Dibayar',
            self::STATUSES['FAILED'] => 'Gagal',
            self::STATUSES['REFUNDED'] => 'Dikembalikan',
            self::STATUSES['EXPIRED'] => 'Kedaluwarsa',
            self::STATUSES['CANCELLED'] => 'Dibatalkan',
            self::STATUSES['CANCELLED_BY_USER_CHANGE'] => 'Dibatalkan User',
            self::STATUSES['PENDING_CASH_PAYMENT'] => 'Menunggu Pembayaran Tunai',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get human readable method.
     */
    public function getMethodLabelAttribute(): string
    {
        $labels = [
            self::METHODS['CASH'] => 'Tunai',
            self::METHODS['QRIS'] => 'QRIS',
            self::METHODS['TRANSFER'] => 'Transfer Bank',
            self::METHODS['INVOICE'] => 'Invoice/Link Pembayaran',
            self::METHODS['EWALLET'] => 'E-Wallet',
            self::METHODS['RETAIL_OUTLET'] => 'Gerai Retail',
            self::METHODS['DIRECT_DEBIT'] => 'Direct Debit',
            self::METHODS['PAYLATER'] => 'Paylater',
            self::METHODS['CREDIT_CARD'] => 'Kartu Kredit',
        ];

        return $labels[$this->method] ?? ucfirst($this->method);
    }

    /**
     * Get days until expiry.
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        return Carbon::now()->diffInDays($this->expiry_date, false);
    }

    /**
     * Get time until expiry in human readable format.
     */
    public function getTimeUntilExpiryAttribute(): ?string
    {
        if (!$this->expiry_date) {
            return null;
        }

        $expiry = Carbon::parse($this->expiry_date);

        if ($expiry->isPast()) {
            return 'Sudah kedaluwarsa';
        }

        return $expiry->diffForHumans();
    }

    /**
     * Create payment from Xendit data.
     */
    public static function createFromXenditData(array $xenditData, string $examinationId): self
    {
        return self::create([
            'examination_id' => $examinationId,
            'amount' => $xenditData['amount'],
            'currency' => $xenditData['currency'],
            'description' => $xenditData['description'],
            'method' => self::METHODS['INVOICE'], // Default to invoice for Xendit payments
            'status' => strtolower($xenditData['status']),
            'xendit_id' => $xenditData['id'],
            'external_id' => $xenditData['external_id'],
            'user_id' => $xenditData['user_id'] ?? null,
            'merchant_name' => $xenditData['merchant_name'] ?? null,
            'merchant_profile_picture_url' => $xenditData['merchant_profile_picture_url'] ?? null,
            'payer_email' => $xenditData['payer_email'] ?? null,
            'customer_given_names' => $xenditData['customer']['given_names'] ?? null,
            'customer_mobile_number' => $xenditData['customer']['mobile_number'] ?? null,
            'invoice_url' => $xenditData['invoice_url'] ?? null,
            'success_redirect_url' => $xenditData['success_redirect_url'] ?? null,
            'failure_redirect_url' => $xenditData['failure_redirect_url'] ?? null,
            'should_exclude_credit_card' => $xenditData['should_exclude_credit_card'] ?? false,
            'should_send_email' => $xenditData['should_send_email'] ?? false,
            'available_banks' => $xenditData['available_banks'] ?? null,
            'available_retail_outlets' => $xenditData['available_retail_outlets'] ?? null,
            'available_ewallets' => $xenditData['available_ewallets'] ?? null,
            'available_qr_codes' => $xenditData['available_qr_codes'] ?? null,
            'available_direct_debits' => $xenditData['available_direct_debits'] ?? null,
            'available_paylaters' => $xenditData['available_paylaters'] ?? null,
            'metadata' => $xenditData['metadata'],
            'expiry_date' => isset($xenditData['expiry_date']) ? Carbon::parse($xenditData['expiry_date']) : null,
        ]);
    }

    /**
     * Update payment from Xendit webhook data.
     */
    public function updateFromXenditWebhook(array $webhookData): bool
    {
        $this->status = strtolower($webhookData['status']);

        if (isset($webhookData['paid_at'])) {
            $this->paid_at = Carbon::parse($webhookData['paid_at']);
        }

        if (isset($webhookData['payment_method'])) {
            $this->method = $this->mapXenditMethodToLocal($webhookData['payment_method']);
        }

        return $this->save();
    }

    /**
     * Map Xendit payment method to local method.
     */
    private function mapXenditMethodToLocal(string $xenditMethod): string
    {
        $mapping = [
            'BANK_TRANSFER' => self::METHODS['TRANSFER'],
            'EWALLET' => self::METHODS['EWALLET'],
            'RETAIL_OUTLET' => self::METHODS['RETAIL_OUTLET'],
            'QR_CODE' => self::METHODS['QRIS'],
            'DIRECT_DEBIT' => self::METHODS['DIRECT_DEBIT'],
            'PAYLATER' => self::METHODS['PAYLATER'],
            'CREDIT_CARD' => self::METHODS['CREDIT_CARD'],
        ];

        return $mapping[$xenditMethod] ?? self::METHODS['INVOICE'];
    }
}

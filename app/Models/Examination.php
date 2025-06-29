<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Examination extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'patient_id',
        'service_item_id',
        'service_item_name',
        'scheduled_date',
        'scheduled_time',
        'pickup_requested',
        'pickup_address',
        'pickup_location_map',
        'pickup_time',
        'status',
        'notes',
        'result_available',
        'payment_status',
        'payment_method',
        'final_price',
    ];

    protected $casts = [
        'pickup_requested' => 'boolean',
        'result_available' => 'boolean',
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime',
        'pickup_time' => 'datetime',
        'final_price' => 'decimal:2', // Cast sebagai decimal untuk currency
    ];

    /**
     * Status constants untuk konsistensi
     */
    const STATUS_PENDING = 'pending';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Payment status constants
     */
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    /**
     * Boot method untuk set UUID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Hanya generate UUID jika ID tidak ada atau kosong string
            $keyName = $model->getKeyName();
            if (!isset($model->{$keyName}) || $model->{$keyName} === '' || $model->{$keyName} === null) {
                $model->{$keyName} = (string) Str::uuid();
            }
        });
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('results')
            ->acceptsMimeTypes(['application/pdf'])
            ->singleFile() // Hanya satu file hasil per examination
            ->useDisk('public');

        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])
            ->useDisk('public'); // Untuk lampiran tambahan
    }

    /**
     * Register media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        // Thumbnail untuk gambar attachment
        $this->addMediaConversion('thumbnail')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('attachments')
            ->nonQueued(); // Proses langsung tanpa queue
    }

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function serviceItem()
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scopes
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeWithResults($query)
    {
        return $query->where('result_available', true);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    /**
     * Accessors & Mutators
     */
    public function getServiceItemPriceAttribute()
    {
        return $this->final_price;
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_SCHEDULED => 'Terjadwal',
            self::STATUS_IN_PROGRESS => 'Sedang Berlangsung',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            self::PAYMENT_PENDING => 'Menunggu Pembayaran',
            self::PAYMENT_PAID => 'Sudah Dibayar',
            self::PAYMENT_FAILED => 'Pembayaran Gagal',
            self::PAYMENT_REFUNDED => 'Dikembalikan',
        ];

        return $labels[$this->payment_status] ?? 'Unknown';
    }

    /**
     * Media helper methods
     */
    public function hasResult()
    {
        return $this->hasMedia('results') && $this->result_available;
    }

    public function getResultFile()
    {
        return $this->getFirstMedia('results');
    }

    public function getResultUrl()
    {
        $media = $this->getResultFile();
        return $media ? $media->getUrl() : null;
    }

    public function getResultPath()
    {
        $media = $this->getResultFile();
        return $media ? $media->getPath() : null;
    }

    public function getAttachments()
    {
        return $this->getMedia('attachments');
    }

    /**
     * Status helper methods
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function canUploadResult()
    {
        return in_array($this->status, [self::STATUS_IN_PROGRESS, self::STATUS_COMPLETED]);
    }

    public function canCancel()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_SCHEDULED]);
    }

    /**
     * Action methods
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'result_available' => $this->hasMedia('results'),
        ]);
    }

    public function markAsPaid()
    {
        $this->update(['payment_status' => self::PAYMENT_PAID]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'notes' => $reason ? $this->notes . "\nDibatalkan: " . $reason : $this->notes,
        ]);
    }

    /**
     * Static helper methods
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_SCHEDULED => 'Terjadwal',
            self::STATUS_IN_PROGRESS => 'Sedang Berlangsung',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public static function getPaymentStatusOptions()
    {
        return [
            self::PAYMENT_PENDING => 'Menunggu Pembayaran',
            self::PAYMENT_PAID => 'Sudah Dibayar',
            self::PAYMENT_FAILED => 'Pembayaran Gagal',
            self::PAYMENT_REFUNDED => 'Dikembalikan',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia; // Tambahkan ini
use Spatie\MediaLibrary\InteractsWithMedia; // Tambahkan ini

class Examination extends Model implements HasMedia // Implementasikan HasMedia
{
    use HasFactory, InteractsWithMedia, HasUuids; // Gunakan InteractsWithMedia    
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'patient_id',
        // 'examination_type',
        'service_item_id',
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
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime', // atau 'string' jika hanya menyimpan jam
        'pickup_time' => 'datetime',    // atau 'string' jika hanya menyimpan jam
        'pickup_requested' => 'boolean',
        'result_available' => 'boolean',
        'final_price' => 'decimal:2',
    ];

    // Relasi ke Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function serviceItem() // Relasi baru ke ServiceItem
    {
        return $this->belongsTo(ServiceItem::class);
    }

    // Konfigurasi Media Collection (Opsional tapi Direkomendasikan untuk Media Library)
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('results')
            ->useDisk('public'); // Pastikan ini menunjuk ke disk 'public'
    }
}

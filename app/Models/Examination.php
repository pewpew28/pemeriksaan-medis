<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk UUID

class Examination extends Model
{
    use HasFactory;

    protected $keyType = 'string'; // Penting: karena ID adalah UUID
    public $incrementing = false; // Penting: karena ID bukan auto-incrementing integer

    protected $fillable = [
        'patient_id',
        'service_item_id', // Tambahkan ini
        'service_item_name', // Tambahkan ini
        'scheduled_date',
        'scheduled_time',
        'pickup_requested',
        'pickup_address',
        'pickup_location_map',
        'pickup_time',
        'status', // Pastikan ini di-fillable
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
        'scheduled_time' => 'datetime', // Cast sebagai datetime untuk Carbon
        'pickup_time' => 'datetime', // Cast sebagai datetime untuk Carbon
    ];

    // Override the boot method to set UUID on creation
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function serviceItem() // Pastikan ada relasi ini
    {
        return $this->belongsTo(ServiceItem::class);
    }

    // Jika Anda mengakses $examination->service_item_price dan $examination->service_item_name
    // di blade, pastikan ini ada atau relasi serviceItem() di atas diatur dengan benar.
    // Jika service_item_name dan final_price disimpan di examinations, maka getter ini tidak diperlukan
    // kecuali Anda ingin membungkusnya.
    public function getServiceItemPriceAttribute()
    {
        return $this->final_price; // Mengambil dari final_price yang sudah disimpan
    }
}
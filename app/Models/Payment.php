<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // app/Models/Payment.php
    protected $fillable = [
        'examination_id',
        'amount',
        'method', // Pastikan ini 'method' bukan 'payment_method'
        'status',
        'xendit_id',
        'currency',
        'transaction_id',
        'reference_code',
        'qr_string',
        'qr_code_url',
        'bank_code',
        'account_number',
        'account_name',
        'notes',
        'paid_at',
        'expiry_time',
    ];

    // Tambahkan casting untuk kolom-kolom timestamp jika diperlukan
    protected $casts = [
        'paid_at' => 'datetime',
        'expiry_time' => 'datetime',
    ];

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }
}

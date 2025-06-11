<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination_id',
        'xendit_id',
        'method',
        'status',
        'amount',
        'currency',
        'qr_string',
        'qr_code_url',
        'bank_code',
        'account_number',
        'account_name',
        'expiry_time',
        'reference_code',
    ];

    protected $casts = [
        'expiry_time' => 'datetime',
    ];

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }
}
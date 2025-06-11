<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_number',
        'age',
        'address',
        'date_of_birth',
        'gender',
    ];

    // Relasi ke User (jika pasien terdaftar sebagai user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Examination
    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }
}

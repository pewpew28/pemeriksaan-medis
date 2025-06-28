<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'examination_id' => 'required|exists:examinations,id',
            'payment_method' => 'required|in:online,cash'
        ];
    }

    public function messages()
    {
        return [
            'examination_id.required' => 'ID pemeriksaan harus diisi.',
            'examination_id.exists' => 'Pemeriksaan tidak ditemukan.',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.'
        ];
    }
}

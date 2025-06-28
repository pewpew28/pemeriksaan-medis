<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'amount_received' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'amount_received.required' => 'Jumlah pembayaran harus diisi.',
            'amount_received.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount_received.min' => 'Jumlah pembayaran tidak boleh negatif.',
            'notes.max' => 'Catatan tidak boleh lebih dari 500 karakter.'
        ];
    }
}

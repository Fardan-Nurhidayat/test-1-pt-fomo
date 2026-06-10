<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInventory extends FormRequest
{
    public function rules(): array
    {
        return [
            'products_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'products_id.required' => 'Kolom products_id wajib diisi.',
            'products_id.exists' => 'Produk dengan ID tersebut tidak ditemukan.',
            'quantity.required' => 'Kolom quantity wajib diisi.',
            'quantity.integer' => 'Kolom quantity harus berupa angka bulat.',
            'quantity.min' => 'Kolom quantity tidak boleh kurang dari 0.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrder extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'list_of_products' => 'required|array|min:1',
            'list_of_products.*.products_id' => 'required|exists:products,id',
            'list_of_products.*.quantity' => 'required|integer|min:1',
            'flash_sale_id' => 'nullable|exists:flash_sales,id',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'kolom user_id wajib diisi.',
            'user_id.exists' => 'pengguna dengan id tersebut tidak ditemukan.',
            'list_of_products.required' => 'kolom list_of_products wajib diisi.',
            'list_of_products.array' => 'kolom list_of_products harus berupa array.',
            'list_of_products.min' => 'kolom list_of_products harus memiliki setidaknya 1 produk.',
            'list_of_products.*.products_id.required' => 'setiap item dalam list_of_products harus memiliki product_id yang valid.',
            'list_of_products.*.products_id.exists' => 'produk dengan id tersebut tidak ditemukan.',
            'flash_sale_id.exists' => 'flash sale dengan id tersebut tidak ditemukan.',
            'notes.string' => 'kolom notes harus berupa string.',
        ];
    }
}

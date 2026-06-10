<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class CreateFlashSale extends FormRequest
{
    public function rules(): array
    {
        return [
            'products_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_value' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,active,ended,cancelled',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ];
    }

    public function messages()
    {
        return [
            'products_id.required' => 'kolom products_id wajib diisi.',
            'products_id.exists' => 'produk dengan id tersebut tidak ditemukan.',
            'title.required' => 'kolom title wajib diisi.',
            'title.string' => 'kolom title harus berupa string.',
            'title.max' => 'kolom title tidak boleh lebih dari 255 karakter.',
            'description.string' => 'kolom description harus berupa string.',
            'discount_value.required' => 'kolom discount_value wajib diisi.',
            'discount_value.numeric' => 'kolom discount_value harus berupa angka.',
            'discount_value.min' => 'kolom discount_value tidak boleh kurang dari 0.',
            'status.required' => 'kolom status wajib diisi.',
            'status.in' => 'kolom status harus salah satu dari: scheduled, active, ended, cancelled.',
            'start_time.required' => 'kolom start_time wajib diisi.',
            'start_time.date' => 'kolom start_time harus berupa tanggal.',
            'start_time.after' => 'kolom start_time harus setelah sekarang.',
            'end_time.required' => 'kolom end_time wajib diisi.',
            'end_time.date' => 'kolom end_time harus berupa tanggal.',
        ];
    }
}

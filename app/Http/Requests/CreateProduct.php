<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProduct extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kolom nama wajib diisi.',
            'name.string' => 'Kolom nama harus berupa teks.',
            'name.max' => 'Kolom nama tidak boleh lebih dari 255 karakter.',
            'description.string' => 'Kolom deskripsi harus berupa teks.',
            'base_price.required' => 'Kolom harga dasar wajib diisi.',
            'base_price.numeric' => 'Kolom harga dasar harus berupa angka.',
            'base_price.min' => 'Kolom harga dasar tidak boleh kurang dari 0.',
            'status.required' => 'Kolom status wajib diisi.',
            'status.in' => 'Kolom status harus bernilai "active" atau "inactive".',
        ];
    }
}

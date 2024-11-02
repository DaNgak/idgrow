<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'code' => [
                'required',
                'string',
                // Aturan unique pada tabel products dengan pengecualian berdasarkan id
                Rule::unique('products', 'code')->ignore($this->product),
            ],
            'category' => 'required|string',
            'location' => 'required|string',
        ];
    }

    /**
     * Pesan validasi khusus untuk setiap aturan.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.string' => 'Nama produk harus berupa teks.',
            'code.required' => 'Kode produk wajib diisi.',
            'code.string' => 'Kode produk harus berupa teks.',
            'code.unique' => 'Kode produk sudah ada dalam data product.',
            'category.required' => 'Kategori produk wajib diisi.',
            'category.string' => 'Kategori produk harus berupa teks.',
            'location.required' => 'Lokasi produk wajib diisi.',
            'location.string' => 'Lokasi produk harus berupa teks.',
        ];
    }
}

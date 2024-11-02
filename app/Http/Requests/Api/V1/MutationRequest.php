<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MutationRequest extends FormRequest
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
            'user_id' => [
                'required',
                'string',
                Rule::exists('users', 'uuid'),
            ],
            'product_id' => [
                'required',
                'string',
                Rule::exists('products', 'uuid'),
            ],
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'type' => [
                'required',
                'string',
                Rule::in(['in', 'out']),
            ],
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
            'user_id.required' => 'User wajib diisi.',
            'user_id.string' => 'User harus berupa teks.',
            'user_id.exists' => 'User tidak ditemukan dalam data.',

            'product_id.required' => 'Product wajib diisi.',
            'product_id.string' => 'Product harus berupa teks.',
            'product_id.exists' => 'Product tidak ditemukan dalam data.',

            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Tanggal harus dalam format yang benar.',

            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.numeric' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah tidak boleh kurang dari 0.',

            'type.required' => 'Tipe wajib diisi.',
            'type.string' => 'Tipe harus berupa teks.',
            'type.in' => "Tipe harus berupa 'in' atau 'out'.", 
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePegawaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // minimal harus login
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
{
    return [
        'nip' => 'required|unique:pegawais',
        'nama_lengkap' => 'required',
        'gender' => 'nullable|in:pria,perempuan',
        'email' => 'nullable|email|unique:pegawais',
        'tanggal_lahir' => 'nullable|date',
        'departemen_id' => 'nullable|exists:departemens,id',
        'jabatan_id' => 'nullable|exists:jabatans,id',
    ];
}

}

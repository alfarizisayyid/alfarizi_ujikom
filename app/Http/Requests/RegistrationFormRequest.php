<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'ktp_number' => 'required|string|max:20|unique:registrations,ktp_number',
            'ktp_expiry' => 'required|date|after:today',
            'education_level' => 'required|string|max:100',
            'institution' => 'required|string|max:255',
            'graduation_year' => 'required|digits:4',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap harus diisi.',
            'birth_date.required' => 'Tanggal lahir harus diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
            'gender.required' => 'Jenis kelamin harus dipilih.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'ktp_number.required' => 'Nomor KTP harus diisi.',
            'ktp_number.unique' => 'Nomor KTP sudah terdaftar dalam sistem.',
            'ktp_expiry.required' => 'Tanggal berlaku KTP harus diisi.',
            'ktp_expiry.after' => 'KTP harus masih berlaku (tanggal melebihi hari ini).',
            'education_level.required' => 'Tingkat pendidikan harus dipilih.',
            'institution.required' => 'Nama institusi pendidikan harus diisi.',
            'graduation_year.required' => 'Tahun lulus harus diisi.',
            'graduation_year.digits' => 'Tahun lulus harus berupa 4 digit angka.',
        ];
    }
}

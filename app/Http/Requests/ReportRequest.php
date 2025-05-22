<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return optional($this->user())->role === 'admin' || optional($this->user())->role === 'superadmin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'online_funds' => 'required|integer|min:0',
            'offline_funds' => 'required|integer|min:0',
            'donor_count' => 'required|integer|min:0',
            'active_program' => 'required|string|max:255', // Ganti 255 dengan panjang yang sesuai
            'beneficiary_count' => 'required|integer|min:0',
            'coverage_area' => 'required|string|max:255', // Ganti 255 dengan panjang yang sesuai
        ];
    }


    public function messages(): array
    {
        return [
            'online_funds.required' => 'Online fundraising is required.',
            'online_funds.integer' => 'Online fundraising must be an integer.',
            'online_funds.min' => 'Online fundraising cannot be less than 0.',
            'offline_funds.required' => 'Offline fundraising is required.',
            'offline_funds.integer' => 'Offline fundraising must be an integer.',
            'offline_funds.min' => 'Offline fundraising cannot be less than 0.',
            'donor_count.required' => 'Donor count is required.',
            'donor_count.integer' => 'Donor count must be an integer.',
            'donor_count.min' => 'Donor count cannot be less than 0.',
            'active_program.required' => 'Active program name is required.',
            'active_program.string' => 'Active program name must be a string.',
            'active_program.max' => 'Active program name cannot be longer than 255 characters.',
            'beneficiary_count.required' => 'Beneficiary count is required.',
            'beneficiary_count.integer' => 'Beneficiary count must be an integer.',
            'beneficiary_count.min' => 'Beneficiary count cannot be less than 0.',
            'coverage_area.required' => 'Coverage area is required.',
            'coverage_area.string' => 'Coverage area must be a string.',
            'coverage_area.max' => 'Coverage area cannot be longer than 255 characters.',
        ];
    }
}

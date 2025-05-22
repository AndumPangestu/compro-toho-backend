<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnualReportRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'collected_funds' => 'required|numeric|min:0',
            'donor_count' => 'required|integer|min:0',
            'active_program_count' => 'required|integer|min:0',
            'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,csv|max:10240',
        ];
    }


    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'year.required' => 'Year is required',
            'collected_funds.required' => 'Collected Funds is required',
            'donor_count.required' => 'Donor Count is required',
            'active_program_count.required' => 'Active Program Count is required',
            'file.mimes' => 'File must be a PDF, DOC, DOCX, XLS, XLSX, or CSV',
            'file.max' => 'File size must be less than 10MB',
        ];
    }
}

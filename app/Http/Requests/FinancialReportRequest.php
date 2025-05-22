<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinancialReportRequest extends FormRequest
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
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,csv|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'year.required' => 'Year is required',
            'file.required' => 'File is required',
            'file.mimes' => 'File must be a PDF, DOC, DOCX, XLS, XLSX, or CSV',
            'file.max' => 'File size must be less than 10MB',
        ];
    }
}

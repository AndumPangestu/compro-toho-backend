<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyReportRequest extends FormRequest
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
            'month' => 'required|string|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'category_id' => 'required|exists:donation_categories,id',
            'total_expenses' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'year.required' => 'Year is required',
            'month.required' => 'Month is required',
            'category_id.required' => 'Category is required',
            'total_expenses.required' => 'Total Expenses is required',
            'total_expenses.numeric' => 'Total Expenses must be a number',
        ];
    }
}

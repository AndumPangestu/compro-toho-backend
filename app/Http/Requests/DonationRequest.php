<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
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
            'category_id' => 'required|exists:donation_categories,id',
            'title' => 'required|string|max:255|unique:donations,title' . ($this->route('donation') ? ',' . $this->route('donation')->id : ''),
            'fund_usage_details' => 'nullable|string',
            'description' => 'nullable|string',
            'distribution_information' => 'nullable|string',
            'target_amount' => 'required|numeric|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'put_on_highlight' => 'boolean',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'The category field is required.',
            'title.required' => 'The title field is required.',
            'title.unique' => 'The title has already been taken.',
            'image.max' => 'Image must not exceed 4MB',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif',
            'start_date.required' => 'The start date field is required.',
            'end_date.required' => 'The end date field is required.',
            'start_date.after_or_equal' => 'Start date must be after or equal to today',
            'end_date.after_or_equal' => 'End date must be after or equal to the start date',
            'target_amount.required' => 'The target amount field is required.',
            'target_amount.numeric' => 'The target amount must be a number.',
            'target_amount.min' => 'The target amount must be at least 1.',
            'location.required' => 'The location field is required.',
        ];
    }
}

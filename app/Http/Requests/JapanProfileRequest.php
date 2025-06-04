<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JapanProfileRequest extends FormRequest
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
            'description' => 'required|string',
            'name' => 'required|string|max:255',
            'established' => 'required|date',
            'address' => 'required|string|max:255',
            'employees' => 'required|integer',
            'chairman' => 'required|string|max:255',
            'president' => 'required|string|max:255',
            'domestic_group' => 'required|array|min:1',
            'domestic_group.*.name' => 'required|string|max:255',
            'overseas_group' => 'required|array|min:1',
            'overseas_group.*.name' => 'required|string|max:255',
            'image' => 'nullable|array|min:1',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:9792',
        ];
    }
}

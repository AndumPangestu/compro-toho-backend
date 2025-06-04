<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndonesiaProfileRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'image_url.required' => 'Image URL is required',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be a file of type: jpg, jpeg, png',
            'image.max' => 'Image must not exceed 8MB',
        ];
    }
}

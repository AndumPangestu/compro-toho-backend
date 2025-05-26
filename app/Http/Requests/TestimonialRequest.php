<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
            'message' => 'required|string',
            'sender_name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            // 'sender_category' => 'required|in:donor,partner,recipient',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Message is required',
            'sender_name.required' => 'Sender name is required',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, svg',
            'image.max' => 'Image must not exceed 4MB',
        ];
    }
}

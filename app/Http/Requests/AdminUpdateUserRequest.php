<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateUserRequest extends FormRequest
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

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'max:255'],
            'password_confirmation' => ['nullable', 'string', 'max:255', 'same:password'],
            'role' => ['required', 'string', 'in:admin,user'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name must not exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a string.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a string.',
            'phone.max' => 'Phone number must not exceed 20 characters.',
            'role.required' => 'Role is required.',
            'role.string' => 'Role must be a string.',
            'role.in' => 'Role must be "admin" or "user".',
            'password.string' => 'Password must be a string.',
            'password.max' => 'Password must not exceed 255 characters.',
            'password_confirmation.string' => 'Password confirmation must be a string.',
            'password_confirmation.max' => 'Password confirmation must not exceed 255 characters.',
            'password_confirmation.same' => 'Password confirmation must match the password.',
        ];
    }
}

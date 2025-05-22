<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionFilterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'donation_id' => 'nullable|exists:donations,id',
            'limit' => 'nullable|integer|min:1',
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|min:1',
            'status' => 'nullable|in:pending,success,cancel,expire',
            'sort' => 'nullable|in:asc,desc',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MidtransCallbackRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|string',
            'status_code' => 'required|string',
            'gross_amount' => 'required|numeric',
            'signature_key' => 'required|string',
            'payment_type' => 'nullable|string',
            'transaction_status' => 'required|string',
            'fraud_status' => 'nullable|string'
        ];
    }
}

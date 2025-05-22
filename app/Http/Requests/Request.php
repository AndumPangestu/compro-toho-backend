<?php

namespace App\Http\Requests;

use App\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
{
    use ResponseTrait;

    protected function failedValidation(Validator $validator)
    {
        return $this->SendError(400, $validator->getMessageBag()->all(), "Validation error");
    }
}

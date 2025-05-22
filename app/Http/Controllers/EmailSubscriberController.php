<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailSubscriber;
use App\Http\Requests\EmailSubscriberRequest;

class EmailSubscriberController extends Controller
{
    public function store(EmailSubscriberRequest $request)
    {
        $isExists = EmailSubscriber::where('email', $request->email)->exists();

        if ($isExists) {
            return $this->sendError(400, null, "Email already exists.");
        }

        EmailSubscriber::create($request->validated());

        return $this->sendSuccess(200, null, "Email subscriber created successfully.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email'],
            'subject' => ['required', 'max:200'],
            'message' => ['required', 'max:400']
        ]);

        if ($validator->fails()) {
            return $this->SendError(422, $validator->errors(), "Validator error");
        }
        $countTodayMessage = Contact::whereDate('created_at', Carbon::now())->count();
        if ($countTodayMessage > 50) {
            $this->sendError(500, null, "You failed to send a message. You have reached the limit of 50 messages per day.");
        }

        try {
            $data = request()->only(['name', 'email', 'subject', 'message']);
            $contact = Contact::create($data);
            return $this->sendSuccess(201, $contact, "You have successfully sent a message.");
        } catch (\Exception $e) {
            $this->sendError(500, null, $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{

    public function index()
    {
        return view('contact.index');
    }


    public function getContacts(Request $request)
    {
        if ($request->ajax()) {
            $data = Contact::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return
                        '<form action=" ' . route('contact.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['image', 'action'])
                ->escapeColumns([])
                ->make(true);
        }


        return abort(403);
    }

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

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('contact.index')->with('success', 'Contact deleted successfully');
    }
}

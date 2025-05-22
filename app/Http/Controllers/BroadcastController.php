<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Broadcast;
use Illuminate\Http\Request;
use App\Models\BroadcastToken;
use Kreait\Firebase\Messaging;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\BroadcastRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UserFcmTokenRequest;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


class BroadcastController extends Controller
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function index(Request $request)
    {
        return view('broadcasts.index');
    }

    public function getBroadcasts(Request $request)
    {
        if ($request->ajax()) {
            $data = Broadcast::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('broadcasts.show', $row->id) . '" class="btn btn-sm btn-info">View</a> '
                        . '<a href="' . route('broadcasts.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> '
                        . '<form action=" ' . route('broadcasts.destroy', $row->id) . '" method="POST" class="d-inline">'
                        . csrf_field()
                        . method_field("DELETE")
                        . '<button type="submit" class="btn btn-sm btn-danger btn-delete" data-id="{{ $row->id }}">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }
        return abort(403);
    }

    public function create()
    {

        return view('broadcasts.broadcast-form');
    }


    public function store(BroadcastRequest $request)
    {
        try {

            $tokens = BroadcastToken::distinct()->pluck('fcm_token')->filter()->all();

            if (empty($tokens)) {
                return redirect()->back()->with('error', 'No valid FCM tokens found');
            }


            $message = CloudMessage::new()
                ->withData([
                    'title' => $request->title,
                    'body' => $request->description,
                    'link' => $request->link
                ]);


            $sendReport = $this->messaging->sendMulticast($message, $tokens);


            $validTokens = $sendReport->validTokens();


            $failedTokens = [];
            foreach ($sendReport->failures() as $failure) {
                $failedTokens[] = $failure->target();
            }


            if (count($validTokens) === 0) {
                return redirect()->back()->with('error', 'Semua notifikasi gagal dikirim');
            }


            if (!empty($failedTokens)) {
                Log::warning('Beberapa token tidak valid', ['tokens' => $failedTokens]);
            }


            Broadcast::create($request->validated());

            return redirect()->route('broadcasts.index')->with('success', 'Broadcast created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function show(Request $request, Broadcast $broadcast)
    {

        $viewMode = true;

        return view('broadcasts.broadcast-form', compact('broadcast', 'viewMode'));
    }

    public function edit(Broadcast $broadcast)
    {


        return view('broadcasts.broadcast-form', compact('broadcast'));
    }

    public function update(BroadcastRequest $request, Broadcast $broadcast)
    {
        try {

            $tokens = BroadcastToken::distinct()->pluck('fcm_token')->filter()->all();

            if (empty($tokens)) {
                return redirect()->back()->with('error', 'No valid FCM tokens found');
            }


            $message = CloudMessage::new()
                ->withData([
                    'title' => $request->title,
                    'body' => $request->description,
                    'link' => $request->link
                ]);


            $sendReport = $this->messaging->sendMulticast($message, $tokens);


            $validTokens = $sendReport->validTokens();


            $failedTokens = [];
            foreach ($sendReport->failures() as $failure) {
                $failedTokens[] = $failure->target();
            }


            if (count($validTokens) === 0) {
                return redirect()->back()->with('error', 'Semua notifikasi gagal dikirim');
            }


            if (!empty($failedTokens)) {
                Log::warning('Beberapa token tidak valid', ['tokens' => $failedTokens]);
            }

            $broadcast->update($request->validated());

            return  redirect()->route('broadcasts.index')->with('success', 'Broadcast updated successfully');
        } catch (\Exception $e) {
            return  redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, Broadcast $broadcast)
    {
        try {
            $broadcast->delete();
            return  redirect()->route('broadcasts.index')->with('success', 'Broadcast deleted successfully');
        } catch (\Exception $e) {
            return  redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function storeUserFcmToken(UserFcmTokenRequest $request)
    {
        try {
            BroadcastToken::firstOrCreate(['fcm_token' => $request->fcm_token]);

            return $this->sendSuccess(200, null, "FCM token updated successfully");
        } catch (\Exception $e) {
            return $this->sendError(500, null, "Internal server error");
        }
    }
}

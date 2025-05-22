<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Donation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\MidtransCallbackRequest;
use App\Http\Requests\TransactionFilterRequest;
use App\Models\AnonymousDonor;
use App\Notifications\TransactionNotification;
use Illuminate\Support\Facades\Notification;

class TransactionController extends Controller
{
    public function indexUser(TransactionFilterRequest $request)
    {

        $query = Transaction::latest()->where('user_id', auth()->id());

        // Filter berdasarkan request
        $query->when($request->filled('put_on_highlight'), fn($q) => $q->where('put_on_highlight', $request->put_on_highlight))
            ->when($request->filled('status'), fn($q) => $q->where('transaction_status', $request->status))
            ->when($request->filled('donation_id'), fn($q) => $q->where('donation_id', $request->donation_id))
            ->when($request->filled('sort'), function ($q) use ($request) {
                $sortOrder = $request->sort === 'asc' ? 'asc' : 'desc';
                $q->orderBy('created_at', $sortOrder);
            });



        $paginate = $request->input('paginate');
        $limit = $request->input('limit');


        if ($paginate) {
            $transactions = $query->paginate($request->input('per_page', 10));
            $pagination = [
                'total' => $transactions->total(),
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ];
        } else {
            $transactions = $limit ? $query->limit($limit)->get() : $query->get();
            $pagination = null;
        }

        foreach ($transactions as $transaction) {
            $transaction->snap_token = $transaction->transaction_status === "pending" ? $this->getSnapToken($transaction) : null;
        }

        return $this->sendSuccess(200, TransactionResource::collection($transactions), "Transactions fetched successfully", $pagination);
    }

    public function getSnapToken($transaction)
    {

        try {

            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $user = auth()->user();


            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->id,
                    'gross_amount' => $transaction->amount
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => filter_var($user->email, FILTER_VALIDATE_EMAIL),
                    'phone' => preg_replace('/[^0-9]/', '', $user->phone),
                ]
            ];


            $snapToken = Snap::getSnapToken($params);

            return $snapToken;
        } catch (\Exception $e) {
            return;
        }
    }



    public function indexAdmin(Request $request)
    {

        return view('transactions.index');
    }

    public function getTransactions(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->editColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d M Y H:i');
                })
                ->addColumn('donation', function ($transaction) {
                    return $transaction->donation ? $transaction->donation->title : '-';
                })
                ->addColumn('name', function ($transaction) {
                    if ($transaction->user_id) {
                        return $transaction->user ? $transaction->user->name : '-';
                    } elseif ($transaction->anonymous_donor_id) {
                        return $transaction->anonymousDonor ? $transaction->anonymousDonor->name : '-';
                    }
                    return '-';
                })
                ->addColumn('email', function ($transaction) {
                    if ($transaction->user_id) {
                        return $transaction->user ? $transaction->user->email : '-';
                    } elseif ($transaction->anonymous_donor_id) {
                        return $transaction->anonymousDonor ? $transaction->anonymousDonor->email : '-';
                    }
                    return '-';
                })

                ->addColumn('action', function ($row) {
                    return '<a href="' . route('transactions.show', $row->id) . '" class="btn btn-sm btn-info">View</a>';
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }
        return abort(403);
    }



    public function store(TransactionRequest $request)
    {


        try {

            $donation = Donation::findOrFail($request->donation_id);

            if ($donation->start_date > Carbon::now() || $donation->end_date < Carbon::now()) {
                throw new \Exception("Donation is not active", 400);
            }



            return DB::transaction(function () use ($request) {

                $user = auth()->user();
                $anonymousDonor = null;

                if (!$user) {
                    $anonymousDonor = AnonymousDonor::firstOrCreate(
                        [
                            'name'  => $request->get('name', 'Anonim'),
                            'phone' => $request->get('phone', '0000000000'),
                            'email' => $request->email
                        ]
                    );
                } else {

                    if ($request->filled('phone')) {
                        $user->phone = $request->phone;
                    } elseif (!$user->phone) {
                        $user->phone = '0000000000';
                    }
                                        
                }

                $transaction = Transaction::create([
                    'donation_id' => $request->donation_id,
                    'user_id' => $user->id ?? null,
                    'anonymous_donor_id' => $anonymousDonor->id ?? null,
                    'amount' => $request->amount,
                    'message' => $request->message,
                    'is_anonym' => strtolower(trim($request->get('name', 'Anonim'))) === "anonim",
                ]);


                $grossAmount = $transaction->amount;


                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = true;
                Config::$is3ds = true;



                $params = [
                    'transaction_details' => [
                        'order_id' => $transaction->id,
                        'gross_amount' => $grossAmount
                    ],
                    'customer_details' => [
                        'first_name' => $user->name ?? $anonymousDonor->name,
                        'email' =>  $user->email ?? $anonymousDonor->email,
                        'phone' =>  $user->phone ?? $anonymousDonor->phone
                    ]
                ];


                $snapToken = Snap::getSnapToken($params);

                return $this->sendSuccess(200, ['id_transaction' => $transaction->id, 'snap_token' => $snapToken],  "Transaction created successfully");
            });
        } catch (\Exception $e) {
            Log::error("Transaction failed: " . $e->getMessage());

            if ($e->getCode() && $e->getCode() != 500) {

                return $this->sendError($e->getCode(), null, $e->getMessage());
            }

            return response()->json(['message' => 'Transaction failed, please try again'], 500);
        }
    }

    public function show(Request $request, Transaction $transaction)
    {

        if ($request->wantsJson()) {

            return $this->sendSuccess(200, new TransactionResource($transaction), "Transaction fetched successfully");
        }

        return view('transactions.transaction-view', compact('transaction'));
    }


    public function callback(MidtransCallbackRequest $request)
    {
        try {

            $validatedData = $request->validated();

            $serverKey = config('services.midtrans.server_key');
            $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashedKey !== $request->signature_key) {
                Log::warning("Invalid signature key for order: {$validatedData['order_id']}");
                return response()->json(['message' => 'Invalid signature key'], 400);
            }

            $transaction = Transaction::where('id', $validatedData['order_id'])->first();

            if (!$transaction) {
                Log::warning("Transaction not found for order_id: {$validatedData['order_id']}");
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            DB::transaction(function () use ($transaction, $validatedData) {

                if (!empty($validatedData['payment_type'])) {
                    $transaction->update(['payment_type' => $validatedData['payment_type']]);
                }

                if (!empty($validatedData['transaction_id'])) {
                    $transaction->update(['midtrans_transaction_id' => $validatedData['transaction_id']]);
                }

                // Tentukan status transaksi berdasarkan status dari Midtrans
                $transactionStatus = $validatedData['transaction_status'];
                $newStatus = match ($transactionStatus) {
                    'capture' => ($validatedData['payment_type'] === 'credit_card' && $validatedData['fraud_status'] === 'challenge') ? 'pending' : 'success',
                    'settlement' => 'success',
                    'pending' => 'pending',
                    'deny' => 'denied',
                    'cancel' => 'canceled',
                    'expire' => 'expired',
                    'failure' => 'failed',
                    'refund' => 'refunded',
                    'partial_refund' => 'partially_refunded',
                    'authorize' => 'authorized',
                    default => 'unknown',
                };


                if ($newStatus === 'unknown') {
                    Log::warning("Unknown transaction status for order: {$validatedData['order_id']}");
                    return response()->json(['message' => 'Unknown transaction status'], 400);
                }

                $transaction->update(['transaction_status' => $newStatus]);

                if ($newStatus === 'success') {
                    $donation = Donation::lockForUpdate()->findOrFail($transaction->donation_id);
                    try {
                        $donation->increment('collected_amount', $transaction->amount);
                        $users = User::whereIn('role', ['admin', 'superadmin'])->get();
                        Notification::send($users, new TransactionNotification($transaction));
                    } catch (\Exception $e) {
                        Log::error("Error updating donation: " . $e->getMessage());
                    }
                }
            });

            return response()->json(['message' => 'Transaction updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Error processing transaction callback: " . $e->getMessage());
            return response()->json(['message' => 'Transaction processing error'], 500);
        }
    }
}

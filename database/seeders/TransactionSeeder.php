<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Support\Str;
use App\Models\AnonymousDonor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $donationId = Donation::first()->id;
        $userId = User::where('role', 'user')->first()->id;
        $anonymousDonorId = AnonymousDonor::first()->id;

        DB::table('transactions')->insert([
            [
                'id' => Str::uuid(),
                'donation_id' => $donationId,
                'user_id' => $userId,
                'anonymous_donor_id' => null,
                'midtrans_transaction_id' => 'TX1234567890',
                'amount' => 100000.00,
                'payment_type' => 'credit_card',
                'transaction_status' => 'success',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'donation_id' => $donationId,
                'user_id' => $userId,
                'anonymous_donor_id' => null,
                'midtrans_transaction_id' => 'TX0987654321',
                'amount' => 50000.00,
                'payment_type' => 'bank_transfer',
                'transaction_status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'donation_id' => $donationId,
                'user_id' =>  $userId,
                'anonymous_donor_id' => null,
                'midtrans_transaction_id' => 'TX1122334455',
                'amount' => 200000.00,
                'payment_type' => 'ewallet',
                'transaction_status' => 'failed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'donation_id' => $donationId,
                'user_id' => null,
                'anonymous_donor_id' => $anonymousDonorId,
                'midtrans_transaction_id' => 'TX5566778899',
                'amount' => 75000.00,
                'payment_type' => 'qris',
                'transaction_status' => 'expired',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'donation_id' => $donationId,
                'user_id' => null,
                'anonymous_donor_id' => $anonymousDonorId,
                'midtrans_transaction_id' => 'TX6677889900',
                'amount' => 150000.00,
                'payment_type' => 'cash',
                'transaction_status' => 'canceled',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}

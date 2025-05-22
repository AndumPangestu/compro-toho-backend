<?php

namespace App\Models;

use App\UUID;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use UUID;
    public $incrementing = false;

    protected $fillable = [
        'donation_id',
        'midtrans_transaction_id',
        'user_id',
        'anonymous_donor_id',
        'amount',
        'payment_type',
        'transaction_status',
        'message',
        'is_anonym',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function anonymousDonor()
    {
        return $this->belongsTo(AnonymousDonor::class, 'anonymous_donor_id', 'id');
    }
}

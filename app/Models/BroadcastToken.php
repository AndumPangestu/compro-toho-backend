<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BroadcastToken extends Model
{
    protected $fillable = ['fcm_token'];
}

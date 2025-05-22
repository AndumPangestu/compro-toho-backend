<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmailSubscriber extends Model
{
    use Notifiable;
    protected $fillable = [
        'email',
    ];
}

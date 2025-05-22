<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnonymousDonor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'anonymous_donor_id', 'id');
    }
}

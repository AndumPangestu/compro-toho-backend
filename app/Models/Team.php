<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'role', 'position_number', 'image'];

    public function image()
    {
        if ($this->image) {
            return asset('uploads/teams/' . $this->image);
        }
        return asset('img/user-default.png');
    }
}

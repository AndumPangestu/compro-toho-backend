<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['title', 'image', 'description'];
    public function image()
    {
        if ($this->image) {
            return asset('uploads/services/' . $this->image);
        }
        return asset('img/user-default.png');
    }
}

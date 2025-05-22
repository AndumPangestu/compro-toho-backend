<?php

namespace App\Models;

use App\UUID;
use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{

    protected $fillable = ['name', 'description'];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'category_id', 'id');
    }

    public function MonthlyReports()
    {
        return $this->hasMany(MonthlyReport::class, 'category_id', 'id');
    }
}

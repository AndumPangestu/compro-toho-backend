<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Report extends Model
{

    protected $fillable = [
        'online_funds',
        'offline_funds',
        'donor_count',
        'active_program',
        'beneficiary_count',
        'coverage_area',
    ];
}

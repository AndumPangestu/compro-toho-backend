<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    protected $table = 'office_locations';
    protected $fillable = ['name', 'address', 'icon'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function icon()
    {
        if ($this->icon) {
            return asset('uploads/office-locations/' . $this->icon);
        } else {
            return asset('images/default-office-icon.png');
        }
    }
}

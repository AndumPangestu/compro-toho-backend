<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class OfficeLocation extends Model  implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'office_locations';
    protected $fillable = ['name', 'address', 'position', 'map_address'];
}

<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class SocialMedia extends Model  implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'social_media';
    protected $fillable = ['name', 'url'];
}

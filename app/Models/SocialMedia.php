<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $table = 'social_media';
    protected $fillable = ['name', 'url', 'icon'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function icon()
    {
        if ($this->icon) {
            return asset('uploads/social-media/' . $this->icon);
        } else {
            return asset('images/default-office-icon.png');
        }
    }
}

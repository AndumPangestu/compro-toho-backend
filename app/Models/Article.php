<?php

namespace App\Models;

use App\UUID;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ArticleNotification;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model implements HasMedia
{
    use UUID, InteractsWithMedia;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['title', 'slug', 'content', 'type', 'category_id', 'put_on_highlight', 'description'];

    protected $casts = [
        'put_on_highlight' => 'boolean'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('articles')->singleFile();
        $this->addMediaCollection('articles_content');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id');
    }

    public function banner()
    {
        return $this->hasMany(Banner::class, 'article_id', 'id');
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag', 'article_id', 'tag_id');
    }
}

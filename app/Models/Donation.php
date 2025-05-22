<?php

namespace App\Models;

use App\Notifications\DonationNotification;
use App\UUID;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Donation extends Model implements HasMedia
{
    use UUID, InteractsWithMedia;
    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'fund_usage_details',
        'distribution_information',
        'target_amount',
        'collected_amount',
        'start_date',
        'end_date',
        'location',
        'put_on_highlight',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'put_on_highlight' => 'boolean',
        'status' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'category_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'donation_id', 'id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'donation_id', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('donations')->singleFile();
        $this->addMediaCollection('donations_content');
    }
}

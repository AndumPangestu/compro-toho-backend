<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class MonthlyReport extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'year',
        'month',
        'category_id',
        'total_expenses'
    ];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('monthly_reports')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ])
            ->singleFile();
    }

    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'category_id', 'id');
    }
}

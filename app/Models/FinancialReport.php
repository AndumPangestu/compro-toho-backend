<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class FinancialReport extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'year',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('financial_reports')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ])
            ->singleFile();
    }
}

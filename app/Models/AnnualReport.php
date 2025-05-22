<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class AnnualReport extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'year',
        'collected_funds',
        'donor_count',
        'active_program_count',
    ];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('annual_reports')
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

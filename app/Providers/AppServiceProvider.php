<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Donation;
use App\ResponseTrait;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use ResponseTrait;
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('article', function ($value) {
            return Article::where('id', $value)->orWhere('slug', $value)->firstOr(function () {
                $this->SendError(404, null, 'Article not found');
            });
        });

        Route::bind('donation', function ($value) {
            return Donation::where('id', $value)->orWhere('slug', $value)->firstOr(function () {
                $this->SendError(404, null, 'Donation not found');
            });
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Messaging::class, function ($app) {

            $credentials = json_decode(env('FIREBASE_CREDENTIALS'), true);

            return (new Factory)
                ->withServiceAccount($credentials)
                ->createMessaging();
        });
    }
}

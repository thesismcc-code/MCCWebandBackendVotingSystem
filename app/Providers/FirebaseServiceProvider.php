<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('firebase.database', function ($app) {
            $factory = (new Factory)
                ->withServiceAccount(config('firebase.credentials'))
                ->withDatabaseUri(config('firebase.database_url'));

            return $factory->createDatabase();
        });

        $this->app->singleton('firebase.auth', function ($app) {
            $factory = (new Factory)
                ->withServiceAccount(config('firebase.credentials'));

            return $factory->createAuth();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

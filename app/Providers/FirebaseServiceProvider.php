<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Auth;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton("firebase", function ($app) {
            return new Factory()
                ->withServiceAccount(
                    storage_path("app/firebase/firebase-credentials.json"),
                )
                ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
        });

        $this->app->singleton(Database::class, function ($app) {
            return $app->make("firebase")->createDatabase();
        });

        $this->app->singleton(Auth::class, function ($app) {
            return $app->make("firebase")->createAuth();
        });

        // Also bind to string keys for convenience
        $this->app->singleton("firebase.database", function ($app) {
            return $app->make(Database::class);
        });

        $this->app->singleton("firebase.auth", function ($app) {
            return $app->make(Auth::class);
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

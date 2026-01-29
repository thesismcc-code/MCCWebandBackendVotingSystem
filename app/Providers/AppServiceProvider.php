<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Eloquent\User\EloquentUserRepository;
use App\Domain\User\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

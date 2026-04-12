<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Auth\AuthRepository;
use App\Eloquent\Auth\EloquentAuthRepository;
use App\Domain\User\UserRepository;
use App\Eloquent\User\EloquentUserRepository;
use App\Domain\Votes\VotesRepository;
use App\Eloquent\Vote\EloquentVoteRepository;
use App\Domain\Candidates\CandidatesRepository;
use App\Eloquent\Candidates\EloquentCandidateRepository;
use App\Domain\SecurityLogs\SecurityLogsRepository;
use App\Eloquent\SecurityLogs\EloquentSecurityLogsRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(AuthRepository::class, EloquentAuthRepository::class);
        $this->app->bind(VotesRepository::class, EloquentVoteRepository::class);
        $this->app->bind(CandidatesRepository::class, EloquentCandidateRepository::class);
        $this->app->bind(SecurityLogsRepository::class, EloquentSecurityLogsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

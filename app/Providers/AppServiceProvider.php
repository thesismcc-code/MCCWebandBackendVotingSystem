<?php

namespace App\Providers;

use App\Domain\Auth\AuthRepository;
use App\Domain\Candidates\CandidatesRepository;
use App\Domain\Election\ElectionRepository;
use App\Domain\SecurityLogs\SecurityLogsRepository;
use App\Domain\SystemActivity\SystemActivityRepository;
use App\Domain\User\UserRepository;
use App\Domain\Votes\VotesRepository;
use App\Eloquent\Auth\EloquentAuthRepository;
use App\Eloquent\Candidates\EloquentCandidateRepository;
use App\Eloquent\Election\EloquentElectionRepository;
use App\Eloquent\SecurityLogs\EloquentSecurityLogsRepository;
use App\Eloquent\SystemActivity\EloquentSystemActivityRepository;
use App\Eloquent\User\EloquentUserRepository;
use App\Eloquent\Vote\EloquentVoteRepository;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(ElectionRepository::class, EloquentElectionRepository::class);
        $this->app->bind(SystemActivityRepository::class, EloquentSystemActivityRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

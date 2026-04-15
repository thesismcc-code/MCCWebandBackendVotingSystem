<?php

use App\Application\RegisterUser\RegisterUser;
use App\Application\RegisterVotes\RegisterVotes;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\get;
use function Pest\Laravel\mock;

beforeEach(function (): void {
    Session::put('auth_user', [
        'id' => 'COM-test123',
        'role' => 'comelec',
        'email' => 'comelec@test.com',
        'first_name' => 'Comelec',
        'last_name' => 'User',
        'middle_name' => '',
        'student_id' => null,
        'comelec_id' => 'COM-test123',
        'admin_id' => null,
    ]);
});

it('renders comelec dashboard with live turnout data', function (): void {
    mock(RegisterUser::class, function ($mock): void {
        $mock->shouldReceive('realtimeVoterTurnout')
            ->once()
            ->andReturn([
                'total_students' => 100,
                'voted_count' => 40,
                'not_yet_voted' => 60,
                'turnout_percent' => 40.0,
            ]);

        $mock->shouldReceive('voterTurnoutByYearLevel')
            ->once()
            ->andReturn([
                [
                    'year_level' => '1st Year',
                    'total_students' => 25,
                    'voted' => 10,
                    'not_yet_voted' => 15,
                    'turnout_percent' => 40.0,
                ],
            ]);
    });

    mock(RegisterVotes::class, function ($mock): void {
        $mock->shouldReceive('liveVoteCast')->never();
    });

    $response = get(route('view.comelec-dashboard'));

    $response->assertSuccessful();
    $response->assertSee('Dashboard');
    $response->assertSee('100');
    $response->assertSee('40.00%');
    $response->assertSee('40');
    $response->assertSee('60');
    $response->assertSee('1st Year');
});

it('renders empty year-level message when no breakdown data', function (): void {
    mock(RegisterUser::class, function ($mock): void {
        $mock->shouldReceive('realtimeVoterTurnout')
            ->once()
            ->andReturn([
                'total_students' => 0,
                'voted_count' => 0,
                'not_yet_voted' => 0,
                'turnout_percent' => 0.0,
            ]);

        $mock->shouldReceive('voterTurnoutByYearLevel')
            ->once()
            ->andReturn([]);
    });

    mock(RegisterVotes::class, function ($mock): void {
        $mock->shouldReceive('liveVoteCast')->never();
    });

    $response = get(route('view.comelec-dashboard'));

    $response->assertSuccessful();
    $response->assertSee('No year level turnout data available.');
});

it('recomputes turnout metrics when realtime voted_count falls back', function (): void {
    mock(RegisterUser::class, function ($mock): void {
        $mock->shouldReceive('realtimeVoterTurnout')
            ->once()
            ->andReturn([
                'total_students' => 100,
            ]);

        $mock->shouldReceive('voterTurnoutByYearLevel')
            ->once()
            ->andReturn([]);
    });

    mock(RegisterVotes::class, function ($mock): void {
        $mock->shouldReceive('liveVoteCast')
            ->once()
            ->andReturn(45);
    });

    $response = get(route('view.comelec-dashboard'));

    $response->assertSuccessful();
    $response->assertSee('45');
    $response->assertSee('55');
    $response->assertSee('45.00%');
});

it('redirects guests from comelec dashboard', function (): void {
    Session::flush();

    get(route('view.comelec-dashboard'))->assertRedirect(route('login'));
});

<?php

use App\Application\RegisterUser\RegisterUser;
use App\Application\RegisterVotes\RegisterVotes;
use App\Domain\Election\Election;
use App\Domain\Election\ElectionRepository;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\get;
use function Pest\Laravel\mock;

beforeEach(function (): void {
    Session::put('auth_user', [
        'id' => 'SAO-test123',
        'role' => 'sao',
        'email' => 'sao@test.com',
        'first_name' => 'Sao',
        'last_name' => 'User',
        'middle_name' => '',
        'student_id' => null,
        'comelec_id' => null,
        'admin_id' => null,
    ]);
});

it('renders sao dashboard with live election and turnout data', function (): void {
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
        $mock->shouldReceive('liveVoteCast')
            ->never();
    });

    mock(ElectionRepository::class, function ($mock): void {
        $mock->shouldReceive('getActiveElection')
            ->once()
            ->andReturn(new Election(
                id: 'election_1',
                electionName: 'MCC Election 2026',
                description: 'SAO election',
                semester: '2nd',
                academicYear: '2025-2026',
                startDate: '2026-04-01',
                endDate: '2026-04-30',
                openingTime: '08:00',
                closingTime: '17:00',
                status: 'active',
                createdAt: now()->toISOString(),
                updatedAt: now()->toISOString(),
            ));
    });

    $response = get(route('view.sao-dashboard'));

    $response->assertSuccessful();
    $response->assertSee('MCC Election 2026');
    $response->assertSee('Active');
    $response->assertSee('40.00%');
    $response->assertSee('1st Year');
});

it('renders fallback values when there is no active election', function (): void {
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
        $mock->shouldReceive('liveVoteCast')
            ->never();
    });

    mock(ElectionRepository::class, function ($mock): void {
        $mock->shouldReceive('getActiveElection')
            ->once()
            ->andReturnNull();
    });

    $response = get(route('view.sao-dashboard'));

    $response->assertSuccessful();
    $response->assertSee('No Active Election');
    $response->assertSee('Not Started');
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
            ->andReturn(35);
    });

    mock(ElectionRepository::class, function ($mock): void {
        $mock->shouldReceive('getActiveElection')
            ->once()
            ->andReturnNull();
    });

    $response = get(route('view.sao-dashboard'));

    $response->assertSuccessful();
    $response->assertSee('35');
    $response->assertSee('65');
    $response->assertSee('35.00%');
});

it('redirects guests from sao dashboard', function (): void {
    Session::flush();

    get(route('view.sao-dashboard'))->assertRedirect(route('login'));
});

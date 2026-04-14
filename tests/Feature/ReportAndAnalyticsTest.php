<?php

use App\Application\ReportAnalytics\ReportAnalytics;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\get;
use function Pest\Laravel\mock;

beforeEach(function (): void {
    Session::put('auth_user', [
        'id' => 'ADM-test123',
        'role' => 'admin',
        'email' => 'admin@test.com',
        'first_name' => 'Admin',
        'last_name' => 'User',
        'middle_name' => '',
        'student_id' => null,
        'comelec_id' => null,
        'admin_id' => 'ADM-test123',
    ]);
});

it('renders reports and analytics page with mocked data', function (): void {
    mock(ReportAnalytics::class, function ($mock): void {
        $mock->shouldReceive('getReportPageData')
            ->once()
            ->andReturn([
                'stats_card_data' => [
                    'eligible_students' => 100,
                    'live_vote_cast' => 50,
                    'total_positions' => 3,
                    'running_candidates' => 10,
                    'turnout_percent' => 50.0,
                ],
                'realtime_turnout' => [
                    'total_students' => 100,
                    'voted_count' => 50,
                    'not_yet_voted' => 50,
                    'turnout_percent' => 50.0,
                ],
                'per_year_level_turnout' => [
                    [
                        'year_level' => '1st Year',
                        'total_students' => 25,
                        'voted' => 0,
                        'not_yet_voted' => 25,
                        'turnout_percent' => 0.0,
                    ],
                ],
                'live_candidate_result' => [],
                'election' => ['id' => 'e1', 'name' => 'Spring Election'],
            ]);
    });

    $response = get(route('view.reports-and-analytics'));

    $response->assertSuccessful();
    $response->assertSee('Spring Election');
    $response->assertSee('Eligible students');
});

it('renders end of election reports with mocked data', function (): void {
    mock(ReportAnalytics::class, function ($mock): void {
        $mock->shouldReceive('getEndOfElectionData')
            ->once()
            ->andReturn([
                'election' => ['id' => 'e1', 'name' => 'Spring Election'],
                'winners' => [
                    ['position' => 'President', 'name' => 'Jane Doe', 'votes' => 10, 'percentage' => 100.0],
                ],
                'year_levels' => [],
                'full_results' => [
                    'President' => [
                        ['name' => 'Jane Doe', 'votes' => 10, 'percentage' => 100.0],
                    ],
                ],
            ]);
    });

    $response = get(route('view.reports-and-analytics-end-of-election'));

    $response->assertSuccessful();
    $response->assertSee('Spring Election');
    $response->assertSee('Jane Doe');
});

it('downloads end of election pdf', function (): void {
    mock(ReportAnalytics::class, function ($mock): void {
        $mock->shouldReceive('getEndOfElectionData')
            ->once()
            ->andReturn([
                'election' => ['id' => 'e1', 'name' => 'Spring Election'],
                'winners' => [],
                'year_levels' => [],
                'full_results' => [],
            ]);
    });

    $response = get(route('reports.end-of-election-pdf'));

    $response->assertSuccessful();
    expect($response->headers->get('content-type'))->toContain('application/pdf');
});

it('returns live report json for polling', function (): void {
    mock(ReportAnalytics::class, function ($mock): void {
        $mock->shouldReceive('getReportPageData')
            ->once()
            ->andReturn([
                'stats_card_data' => [
                    'eligible_students' => 10,
                    'live_vote_cast' => 2,
                    'total_positions' => 1,
                    'running_candidates' => 2,
                    'turnout_percent' => 20.0,
                ],
                'realtime_turnout' => [
                    'total_students' => 10,
                    'voted_count' => 2,
                    'not_yet_voted' => 8,
                    'turnout_percent' => 20.0,
                ],
                'per_year_level_turnout' => [],
                'live_candidate_result' => [],
                'election' => null,
            ]);
    });

    $response = get(route('reports.live-data'));

    $response->assertSuccessful();
    $response->assertJsonPath('stats_card_data.eligible_students', 10);
    $response->assertJsonPath('realtime_turnout.voted_count', 2);
});

it('redirects guests from reports routes', function (): void {
    Session::flush();

    get(route('view.reports-and-analytics'))->assertRedirect(route('login'));
    get(route('reports.live-data'))->assertRedirect(route('login'));
});

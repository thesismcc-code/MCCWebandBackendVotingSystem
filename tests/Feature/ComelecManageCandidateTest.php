<?php

use App\Domain\Candidates\Candidates;
use App\Domain\Election\Election;
use App\Domain\Election\ElectionRepository;
use App\Domain\Position\Position;
use App\Http\Middleware\LogSystemActivity;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\get;
use function Pest\Laravel\mock;
use function Pest\Laravel\post;

beforeEach(function (): void {
    Session::put('auth_user', [
        'id' => 'COM-test456',
        'role' => 'comelec',
        'email' => 'comelec2@test.com',
        'first_name' => 'Comelec',
        'last_name' => 'Officer',
        'middle_name' => '',
        'student_id' => null,
        'comelec_id' => 'COM-test456',
        'admin_id' => null,
    ]);
});

it('renders manage candidates with no positions', function (): void {
    mock(ElectionRepository::class, function ($mock): void {
        $mock->shouldReceive('getActiveElection')->once()->andReturnNull();
        $mock->shouldReceive('getAllPositions')->once()->andReturn([]);
        $mock->shouldReceive('getAllCandidates')->once()->andReturn([]);
    });

    $response = get(route('view.comelec-manage-candidates'));

    $response->assertSuccessful();
    $response->assertSee('Manage Candidates');
    $response->assertSee('No positions are configured yet');
});

it('renders a position card with candidate names', function (): void {
    $election = new Election(
        id: 'elec_x',
        electionName: 'Spring Election',
        description: '',
        semester: '2nd',
        academicYear: '2025-2026',
        startDate: '2026-04-01',
        endDate: '2026-04-30',
        openingTime: '08:00',
        closingTime: '17:00',
        status: 'active',
        createdAt: now()->toISOString(),
        updatedAt: now()->toISOString(),
    );

    $position = Position::fromFirebase([
        'id' => 'pos_abc',
        'election_id' => 'elec_x',
        'position_name' => 'PRESIDENT',
        'max_votes' => 2,
        'created_at' => now()->toISOString(),
        'updated_at' => now()->toISOString(),
    ]);

    $candidate = Candidates::fromFirebase([
        'id' => 'cand_1',
        'election_id' => 'elec_x',
        'position' => 'PRESIDENT',
        'position_id' => 'pos_abc',
        'position_name' => 'PRESIDENT',
        'full_name' => 'Example Candidate',
        'course' => 'BSIT',
        'year' => '3rd Year',
        'political_party' => 'Party A',
        'manifesto' => 'Platform text',
        'image_url' => '',
        'status' => 'approved',
        'created_at' => now()->toISOString(),
        'updated_at' => now()->toISOString(),
    ]);

    mock(ElectionRepository::class, function ($mock) use ($election, $position, $candidate): void {
        $mock->shouldReceive('getActiveElection')->once()->andReturn($election);
        $mock->shouldReceive('getAllPositions')->once()->andReturn([$position]);
        $mock->shouldReceive('getAllCandidates')->once()->andReturn([$candidate]);
    });

    $response = get(route('view.comelec-manage-candidates'));

    $response->assertSuccessful();
    $response->assertSee('PRESIDENT');
    $response->assertSee('Example Candidate');
    $response->assertSee('Positions');
});

it('stores a candidate when election is active and slots remain', function (): void {
    /** @var \Tests\TestCase $this */
    $this->withoutMiddleware([
        LogSystemActivity::class,
        ValidateCsrfToken::class,
    ]);

    $election = new Election(
        id: 'elec_x',
        electionName: 'Spring Election',
        description: '',
        semester: '2nd',
        academicYear: '2025-2026',
        startDate: '2026-04-01',
        endDate: '2026-04-30',
        openingTime: '08:00',
        closingTime: '17:00',
        status: 'active',
        createdAt: now()->toISOString(),
        updatedAt: now()->toISOString(),
    );

    $position = Position::fromFirebase([
        'id' => 'pos_abc',
        'election_id' => 'elec_x',
        'position_name' => 'PRESIDENT',
        'max_votes' => 2,
        'created_at' => now()->toISOString(),
        'updated_at' => now()->toISOString(),
    ]);

    mock(ElectionRepository::class, function ($mock) use ($election, $position): void {
        $mock->shouldReceive('getActiveElection')->andReturn($election);
        $mock->shouldReceive('getAllPositions')->andReturn([$position]);
        $mock->shouldReceive('getCandidatesByPosition')->with('PRESIDENT')->andReturn([]);
        $mock->shouldReceive('saveCandidate')
            ->once()
            ->with(\Mockery::on(function (array $data): bool {
                return $data['election_id'] === 'elec_x'
                    && $data['position'] === 'PRESIDENT'
                    && $data['full_name'] === 'New Person'
                    && $data['course'] === 'BSCS'
                    && $data['year'] === '2nd Year';
            }));
    });

    $this->from(route('view.comelec-manage-candidates'));

    $response = post(route('comelec.candidate.save'), [
        'position_name' => 'PRESIDENT',
        'full_name' => 'New Person',
        'course' => 'BSCS',
        'year' => '2nd Year',
        'political_party' => '',
        'platform_agenda' => 'Test agenda',
    ]);

    $response->assertRedirect(route('view.comelec-manage-candidates'));
    $response->assertSessionHas('success');
});

it('rejects store when there is no active election', function (): void {
    /** @var \Tests\TestCase $this */
    $this->withoutMiddleware([
        LogSystemActivity::class,
        ValidateCsrfToken::class,
    ]);

    mock(ElectionRepository::class, function ($mock): void {
        $mock->shouldReceive('getActiveElection')->once()->andReturnNull();
    });

    $this->from(route('view.comelec-manage-candidates'));

    $response = post(route('comelec.candidate.save'), [
        'position_name' => 'PRESIDENT',
        'full_name' => 'New Person',
        'course' => 'BSIT',
        'year' => '1st Year',
    ]);

    $response->assertSessionHas('error');
});

it('redirects guests from manage candidates', function (): void {
    Session::flush();

    get(route('view.comelec-manage-candidates'))->assertRedirect(route('login'));
});

<?php

use App\Application\RegisterVotes\RegisterVotes;
use App\Domain\Election\Election;
use App\Domain\Election\ElectionRepository;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\get;
use function Pest\Laravel\mock;
use function Pest\Laravel\post;

beforeEach(function (): void {
    Session::put('auth_user', [
        'id' => 'SAO-test123',
        'role' => 'sao',
        'email' => 'sao@test.com',
        'first_name' => 'Sao',
        'last_name' => 'User',
    ]);
});

it('renders dynamic final results from backend data', function (): void {
    mock(RegisterVotes::class, function ($mock): void {
        $mock->shouldReceive('liveCandidateResult')
            ->once()
            ->andReturn([
                'President' => [
                    ['name' => 'Jane Doe', 'votes' => 53, 'percentage' => 61.0],
                    ['name' => 'John Roe', 'votes' => 34, 'percentage' => 39.0],
                ],
            ]);
    });

    mock(ElectionRepository::class, function ($mock): void {
        $mock->shouldReceive('getActiveElection')
            ->once()
            ->andReturn(makeActiveElection());
        $mock->shouldReceive('getAllPositions')
            ->once()
            ->andReturn([makePositionStub('President', 1, 'election_1')]);
        $mock->shouldReceive('getFinalResultsPublishMetadata')
            ->once()
            ->with('election_1')
            ->andReturn([
                'is_published' => false,
                'published_at' => null,
                'published_by_name' => null,
            ]);
    });

    $response = get(route('view.sao-final-results'));

    $response->assertSuccessful();
    $response->assertSee('Jane Doe');
    $response->assertSee('PUBLISH OFFICIAL RESULTS');
});

it('returns live json payload for polling', function (): void {
    mock(RegisterVotes::class, function ($mock): void {
        $mock->shouldReceive('liveCandidateResult')
            ->once()
            ->andReturn([
                'Senators' => [
                    ['name' => 'Alice', 'votes' => 20, 'percentage' => 50.0],
                    ['name' => 'Bob', 'votes' => 19, 'percentage' => 47.5],
                ],
            ]);
    });

    mock(ElectionRepository::class, function ($mock): void {
        $mock->shouldReceive('getActiveElection')
            ->once()
            ->andReturn(makeActiveElection());
        $mock->shouldReceive('getAllPositions')
            ->once()
            ->andReturn([makePositionStub('Senators', 2, 'election_1')]);
        $mock->shouldReceive('getFinalResultsPublishMetadata')
            ->once()
            ->with('election_1')
            ->andReturn([
                'is_published' => false,
                'published_at' => null,
                'published_by_name' => null,
            ]);
    });

    $response = get(route('sao-final-results.live-data'));

    $response->assertSuccessful();
    $response->assertJsonPath('election.id', 'election_1');
    $response->assertJsonPath('sections.0.position', 'Senators');
    $response->assertJsonPath('sections.0.winners.1.name', 'Bob');
});

it('publishes final results once and stores metadata', function (): void {
    mock(RegisterVotes::class, function ($mock): void {
        $mock->shouldReceive('liveCandidateResult')
            ->once()
            ->andReturn([
                'President' => [
                    ['name' => 'Jane Doe', 'votes' => 53, 'percentage' => 61.0],
                ],
            ]);
    });

    mock(ElectionRepository::class, function ($mock): void {
        $mock->shouldReceive('getActiveElection')
            ->once()
            ->andReturn(makeActiveElection());
        $mock->shouldReceive('getAllPositions')
            ->once()
            ->andReturn([makePositionStub('President', 1, 'election_1')]);
        $mock->shouldReceive('getFinalResultsPublishMetadata')
            ->once()
            ->with('election_1')
            ->andReturn([
                'is_published' => false,
                'published_at' => null,
                'published_by_name' => null,
            ]);
        $mock->shouldReceive('publishFinalResults')
            ->once()
            ->with('election_1', \Mockery::on(function (array $actor): bool {
                return ($actor['published_by'] ?? '') === 'SAO-test123'
                    && ($actor['published_by_name'] ?? '') === 'Sao User';
            }));
    });

    $response = post(route('sao-final-results.publish'));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Official results have been published.');
});

function makeActiveElection(): Election
{
    return new Election(
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
    );
}

function makePositionStub(string $positionName, int $maxVotes, string $electionId): object
{
    return new class($positionName, $maxVotes, $electionId)
    {
        public function __construct(
            private string $positionName,
            private int $maxVotes,
            private string $electionId,
        ) {}

        public function getPositionName(): string
        {
            return $this->positionName;
        }

        public function getMaxVotes(): int
        {
            return $this->maxVotes;
        }

        public function getElectionId(): string
        {
            return $this->electionId;
        }
    };
}

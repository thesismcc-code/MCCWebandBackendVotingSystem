<?php

namespace App\Application\ReportAnalytics;

use App\Application\RegisterCandidates\RegisterCandidates;
use App\Application\RegisterUser\RegisterUser;
use App\Application\RegisterVotes\RegisterVotes;
use App\Domain\Election\ElectionRepository;

class ReportAnalytics
{
    public function __construct(
        private RegisterUser $registerUser,
        private RegisterVotes $registerVotes,
        private RegisterCandidates $registerCandidates,
        private ElectionRepository $electionRepository,
    ) {}

    /**
     * @return array{
     *     stats_card_data: array{
     *         eligible_students: int,
     *         live_vote_cast: int,
     *         total_positions: int,
     *         running_candidates: int,
     *         turnout_percent: float
     *     },
     *     realtime_turnout: array<string, mixed>,
     *     per_year_level_turnout: array<int, array<string, mixed>>,
     *     live_candidate_result: array<string, array<int, array<string, mixed>>>,
     *     election: ?array{id: string, name: string}
     * }
     */
    public function getReportPageData(): array
    {
        $activeElection = $this->electionRepository->getActiveElection();
        $electionId = $activeElection?->getId();

        $liveCandidateResult = $this->registerVotes->liveCandidateResult();
        $realtimeTurnout = $this->registerUser->realtimeVoterTurnout();

        return [
            'stats_card_data' => [
                'eligible_students' => $this->registerUser->countTotalStudents(),
                'live_vote_cast' => $this->registerVotes->liveVoteCast(),
                'total_positions' => $this->countPositionsForActiveElection($electionId, $liveCandidateResult),
                'running_candidates' => $this->registerCandidates->getRunnCandidatesCount(),
                'turnout_percent' => (float) ($realtimeTurnout['turnout_percent'] ?? 0.0),
            ],
            'realtime_turnout' => $realtimeTurnout,
            'per_year_level_turnout' => $this->registerUser->voterTurnoutByYearLevel(),
            'live_candidate_result' => $liveCandidateResult,
            'election' => $activeElection ? [
                'id' => $activeElection->getId(),
                'name' => $activeElection->getElectionName(),
            ] : null,
        ];
    }

    /**
     * @return array{
     *     election: ?array{id: string, name: string},
     *     winners: array<int, array{position: string, name: string, votes: int, percentage: float}>,
     *     year_levels: array<int, array<string, mixed>>,
     *     full_results: array<string, array<int, array<string, mixed>>>
     * }
     */
    public function getEndOfElectionData(): array
    {
        $activeElection = $this->electionRepository->getActiveElection();
        $fullResults = $this->registerVotes->liveCandidateResult();

        return [
            'election' => $activeElection ? [
                'id' => $activeElection->getId(),
                'name' => $activeElection->getElectionName(),
            ] : null,
            'winners' => $this->buildWinners($fullResults),
            'year_levels' => $this->registerUser->voterTurnoutByYearLevel(),
            'full_results' => $fullResults,
        ];
    }

    /**
     * @param  array<string, array<int, array<string, mixed>>>  $liveCandidateResult
     */
    private function countPositionsForActiveElection(?string $electionId, array $liveCandidateResult): int
    {
        if ($electionId === null || $electionId === '') {
            return count($liveCandidateResult);
        }

        $positions = $this->electionRepository->getAllPositions();
        $count = 0;

        foreach ($positions as $position) {
            if ($position->getElectionId() === $electionId) {
                $count++;
            }
        }

        return $count > 0 ? $count : count($liveCandidateResult);
    }

    /**
     * @param  array<string, array<int, array<string, mixed>>>  $fullResults
     * @return array<int, array{position: string, name: string, votes: int, percentage: float}>
     */
    private function buildWinners(array $fullResults): array
    {
        $winners = [];

        foreach ($fullResults as $position => $candidates) {
            if ($candidates === [] || ! isset($candidates[0])) {
                continue;
            }

            $top = $candidates[0];
            $winners[] = [
                'position' => $position,
                'name' => (string) ($top['name'] ?? ''),
                'votes' => (int) ($top['votes'] ?? 0),
                'percentage' => (float) ($top['percentage'] ?? 0.0),
            ];
        }

        return $winners;
    }
}

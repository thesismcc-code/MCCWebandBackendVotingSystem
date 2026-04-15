<?php

namespace App\Http\Controllers;

use App\Application\RegisterUser\RegisterUser;
use App\Application\RegisterVotes\RegisterVotes;
use App\Domain\Election\ElectionRepository;
use Illuminate\View\View;

class SAODashboardController extends Controller
{
    public function __construct(
        private RegisterUser $registerUser,
        private RegisterVotes $registerVotes,
        private ElectionRepository $electionRepository,
    ) {}

    public function index(): View
    {
        $activeElection = $this->electionRepository->getActiveElection();
        $realtimeTurnout = $this->registerUser->realtimeVoterTurnout();
        $perYearLevelTurnout = $this->registerUser->voterTurnoutByYearLevel();
        $totalStudents = (int) ($realtimeTurnout['total_students'] ?? 0);
        $votedCount = (int) ($realtimeTurnout['voted_count'] ?? $this->registerVotes->liveVoteCast());
        $notYetVoted = max($totalStudents - $votedCount, 0);
        $turnoutPercent = $totalStudents > 0
            ? round(($votedCount / $totalStudents) * 100, 2)
            : 0.0;

        $data = [
            'election' => [
                'name' => $activeElection?->getElectionName() ?: 'No Active Election',
                'status' => $activeElection ? ucfirst($activeElection->getStatus()) : 'Not Started',
            ],
            'results_status' => $activeElection && $activeElection->isClosed() ? 'Published' : 'Not Published',
            'realtime_turnout' => [
                'total_students' => $totalStudents,
                'voted_count' => $votedCount,
                'not_yet_voted' => $notYetVoted,
                'turnout_percent' => $turnoutPercent,
            ],
            'per_year_level_turnout' => $perYearLevelTurnout,
        ];

        return view('saodashboard', compact('data'));
    }
}

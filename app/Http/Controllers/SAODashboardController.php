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

        $data = [
            'election' => [
                'name' => $activeElection?->getElectionName() ?: 'No Active Election',
                'status' => $activeElection ? ucfirst($activeElection->getStatus()) : 'Not Started',
            ],
            'results_status' => $activeElection && $activeElection->isClosed() ? 'Published' : 'Not Published',
            'realtime_turnout' => [
                'total_students' => (int) ($realtimeTurnout['total_students'] ?? 0),
                'voted_count' => (int) ($realtimeTurnout['voted_count'] ?? $this->registerVotes->liveVoteCast()),
                'not_yet_voted' => (int) ($realtimeTurnout['not_yet_voted'] ?? 0),
                'turnout_percent' => (float) ($realtimeTurnout['turnout_percent'] ?? 0.0),
            ],
            'per_year_level_turnout' => $perYearLevelTurnout,
        ];

        return view('saodashboard', compact('data'));
    }
}

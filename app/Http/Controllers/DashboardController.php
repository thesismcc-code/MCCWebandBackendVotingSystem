<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Application\RegisterUser\RegisterUser;
use App\Application\RegisterVotes\RegisterVotes;
use App\Application\RegisterCandidates\RegisterCandidates;


class DashboardController extends Controller
{
    private RegisterUser $registerUser;
    private RegisterVotes $registerVote;
    private RegisterCandidates $registerCandidates;

    public function __construct(
        RegisterUser $registerUser,
        RegisterVotes $registerVote,
        RegisterCandidates $registerCandidates
    ) {
        $this->registerUser = $registerUser;
        $this->registerVote = $registerVote;
        $this->registerCandidates = $registerCandidates;
    }
    public function index(): View
    {
        $total_register_voters = $this->registerUser->total_registered_voters();
        $live_vote_cast = $this->registerVote->liveVoteCast();
        $running_candidates = $this->registerCandidates->getRunnCandidatesCount();
        $turn_out_rates = $this->registerUser->turnOutRates();
        $live_candidate_result = [];
        $senators = [];

        $data = [
            "stats_card_data" => [
                "total_register_voters" => $total_register_voters,
                "live_vote_cast" => $live_vote_cast,
                "running_candidates" => $running_candidates,
                "turn_out_rates" => $turn_out_rates,
            ],
            "live_candidate_result" => $live_candidate_result,
            "senators" => $senators,
        ];
        // dd($data);
        return view('dashboard', compact('data'));
    }
}

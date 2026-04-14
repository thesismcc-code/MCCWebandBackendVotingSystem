<?php

namespace App\Http\Controllers;

use App\Domain\Election\ElectionRepository;
use Illuminate\View\View;

class SAOCandidateList extends Controller
{
    public function __construct(
        private ElectionRepository $electionRepository
    ) {}

    public function index(): View
    {
        $activeElection = $this->electionRepository->getActiveElection();
        $candidates = $this->electionRepository->getAllCandidates();

        $groupedCandidates = collect($candidates)
            ->groupBy(function ($candidate) {
                $positionName = trim($candidate->getPositionName());

                return $positionName !== '' ? $positionName : 'Unassigned Position';
            })
            ->map(function ($positionCandidates, $positionName) {
                return [
                    'position_name' => $positionName,
                    'candidates' => $positionCandidates
                        ->map(function ($candidate) {
                            $fullName = trim($candidate->getFullName());

                            return $fullName !== '' ? $fullName : 'Unnamed Candidate';
                        })
                        ->values()
                        ->all(),
                ];
            })
            ->sortBy('position_name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        return view('saocandidatelist', [
            'activeElection' => $activeElection,
            'groupedCandidates' => $groupedCandidates,
        ]);
    }
}

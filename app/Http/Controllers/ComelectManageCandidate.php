<?php

namespace App\Http\Controllers;

use App\Domain\Election\ElectionRepository;
use App\Http\Requests\Comelec\DeleteComelecCandidateRequest;
use App\Http\Requests\Comelec\StoreComelecCandidateRequest;
use App\Http\Requests\Comelec\UpdateComelecCandidateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ComelectManageCandidate extends Controller
{
    public function __construct(
        private ElectionRepository $electionRepository,
    ) {}

    public function index(): View
    {
        $activeElection = $this->electionRepository->getActiveElection();
        $positions = $this->electionRepository->getAllPositions();
        $allCandidates = $this->electionRepository->getAllCandidates();

        $grouped = collect($allCandidates)->groupBy(fn ($c) => trim($c->getPositionName()) ?: '');

        $positionRows = collect($positions)->map(function ($position) use ($grouped) {
            $name = trim($position->getPositionName());
            $candidates = ($grouped->get($name) ?? collect())->values()->all();
            $count = count($candidates);
            $maxVotes = $position->getMaxVotes();

            return [
                'position_id' => $position->getId(),
                'position_name' => $name,
                'max_votes' => $maxVotes,
                'candidate_count' => $count,
                'remaining_slots' => max(0, $maxVotes - $count),
                'candidates' => $candidates,
                'is_full' => $count >= $maxVotes,
            ];
        })->values()->all();

        return view('comelecmanagecandidate', [
            'activeElection' => $activeElection,
            'positionRows' => $positionRows,
        ]);
    }

    public function store(StoreComelecCandidateRequest $request): RedirectResponse
    {
        $activeElection = $this->electionRepository->getActiveElection();

        if (! $activeElection) {
            return back()->with('error', 'No active election found.');
        }

        $validated = $request->validated();

        $positions = $this->electionRepository->getAllPositions();
        $position = collect($positions)->first(
            fn ($p) => trim($p->getPositionName()) === trim($validated['position_name'])
        );

        if (! $position) {
            return back()->with('error', 'Invalid position selected.');
        }

        $current = $this->electionRepository->getCandidatesByPosition($validated['position_name']);

        if (count($current) >= $position->getMaxVotes()) {
            return back()->with('error', 'Maximum candidates reached for this position.');
        }

        $this->electionRepository->saveCandidate([
            'election_id' => $activeElection->getId(),
            'position' => $validated['position_name'],
            'full_name' => $validated['full_name'],
            'course' => $validated['course'],
            'year' => $validated['year'],
            'political_party' => $validated['political_party'] ?? '',
            'manifesto' => $validated['platform_agenda'] ?? '',
            'image_url' => '',
            'status' => 'approved',
        ]);

        return back()->with('success', 'Candidate added successfully.');
    }

    public function update(UpdateComelecCandidateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->electionRepository->updateCandidate($validated['candidate_id'], [
            'position' => $validated['position_name'],
            'full_name' => $validated['full_name'],
            'course' => $validated['course'],
            'year' => $validated['year'],
            'political_party' => $validated['political_party'] ?? '',
            'manifesto' => $validated['platform_agenda'] ?? '',
        ]);

        return back()->with('success', 'Candidate updated successfully.');
    }

    public function destroy(DeleteComelecCandidateRequest $request): RedirectResponse
    {
        $this->electionRepository->deleteCandidate($request->validated()['candidate_id']);

        return back()->with('success', 'Candidate deleted successfully.');
    }
}

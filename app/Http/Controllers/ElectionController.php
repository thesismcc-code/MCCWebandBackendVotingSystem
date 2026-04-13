<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Domain\Election\ElectionRepository;
use App\Application\RegisterElection\RegisterElection;

class ElectionController extends Controller
{
    private RegisterElection $registerElection;
    public function __construct(
        RegisterElection $registerElection,
        private ElectionRepository $electionRepository
    ) {
        $this->registerElection = $registerElection;
    }

    public function index(): View
    {
        $activeElection = $this->electionRepository->getActiveElection();

        return view('electioncontrol', compact('activeElection'));
    }

    public function indexPositionSetup(): View
    {
        $positions      = $this->registerElection->getAllPosistion();
        $totalPositions = $this->registerElection->getTotalPositions();
        $totalCandidates = $this->registerElection->getTotalCandidates();

        return view('posistionsetup', compact('positions', 'totalPositions', 'totalCandidates'));
    }

    public function indexCandidateList(): View
    {
        $candidates = $this->electionRepository->getAllCandidates();
        $positions  = $this->electionRepository->getAllPositions();
        // dd($positions);
        return view('candidatelist', compact('candidates', 'positions'));
    }

    // ─── SAVE GENERAL SETTINGS ──────────────────────────────────────────────────

    public function saveGeneralSettings(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'election_name' => 'required|string|max:255',
            'semester'      => 'required|string',
            'academic_year' => 'required|string',
        ]);

        $activeElection = $this->electionRepository->getActiveElection();

        if (! $activeElection) {
            return back()->with('error', 'No active or upcoming election found.');
        }

        $this->electionRepository->updateElectionGeneral($activeElection->getId(), [
            'election_name' => $request->input('election_name'),
            'semester'      => $request->input('semester'),
            'academic_year' => $request->input('academic_year'),
        ]);

        return back()->with('success', 'General settings saved successfully.');
    }

    // ─── SAVE SCHEDULE SETTINGS ─────────────────────────────────────────────────

    public function saveScheduleSettings(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'opening_time' => 'required|string',
            'closing_time' => 'required|string',
        ]);

        $activeElection = $this->electionRepository->getActiveElection();

        if (! $activeElection) {
            return back()->with('error', 'No active or upcoming election found.');
        }

        $this->electionRepository->updateElectionSchedule($activeElection->getId(), [
            'start_date'   => $request->input('start_date'),
            'end_date'     => $request->input('end_date'),
            'opening_time' => $request->input('opening_time'),
            'closing_time' => $request->input('closing_time'),
        ]);

        return back()->with('success', 'Schedule settings saved successfully.');
    }

    // ─── POSITION CRUD ──────────────────────────────────────────────────────────

    public function savePosition(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'position_name' => 'required|string|max:100',
            'max_votes'     => 'required|integer|min:1',
        ]);

        $this->electionRepository->savePosition([
            'election_id'   => $this->electionRepository->getActiveElection()?->getId() ?? '',
            'position_name' => $request->input('position_name'),
            'max_votes'     => (int) $request->input('max_votes'),
        ]);

        return back()->with('success', 'Position added successfully.');
    }

    public function updatePosition(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'position_id'   => 'required|string',
            'position_name' => 'required|string|max:100',
            'max_votes'     => 'required|integer|min:1',
        ]);

        $this->electionRepository->updatePosition($request->input('position_id'), [
            'position_name' => $request->input('position_name'),
            'max_votes'     => (int) $request->input('max_votes'),
        ]);

        return back()->with('success', 'Position updated successfully.');
    }

    public function deletePosition(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['position_id' => 'required|string']);

        $this->electionRepository->deletePosition($request->input('position_id'));

        return back()->with('success', 'Position deleted successfully.');
    }

    // ─── CANDIDATE CRUD ─────────────────────────────────────────────────────────

    public function saveCandidate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'position_name'  => 'required|string',
            'full_name'      => 'required|string|max:255',
            'course'         => 'required|string',
            'year'           => 'required|string',
            'political_party' => 'nullable|string|max:100',
            'platform_agenda' => 'nullable|string',
        ]);

        $this->electionRepository->saveCandidate([
            'election_id'    => $this->electionRepository->getActiveElection()?->getId() ?? '',
            'position_name'  => $request->input('position_name'),
            'full_name'      => $request->input('full_name'),
            'course'         => $request->input('course'),
            'year'           => $request->input('year'),
            'political_party' => $request->input('political_party', ''),
            'platform_agenda' => $request->input('platform_agenda', ''),
            'image_url'      => '',  // handle image upload separately if needed
            'status'         => 'active',
        ]);

        return back()->with('success', 'Candidate added successfully.');
    }

    public function updateCandidate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'candidate_id'   => 'required|string',
            'position_name'  => 'required|string',
            'full_name'      => 'required|string|max:255',
            'course'         => 'required|string',
            'year'           => 'required|string',
            'political_party' => 'nullable|string|max:100',
            'platform_agenda' => 'nullable|string',
        ]);

        $this->electionRepository->updateCandidate($request->input('candidate_id'), [
            'position_name'  => $request->input('position_name'),
            'full_name'      => $request->input('full_name'),
            'course'         => $request->input('course'),
            'year'           => $request->input('year'),
            'political_party' => $request->input('political_party', ''),
            'platform_agenda' => $request->input('platform_agenda', ''),
        ]);

        return back()->with('success', 'Candidate updated successfully.');
    }

    public function deleteCandidate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['candidate_id' => 'required|string']);

        $this->electionRepository->deleteCandidate($request->input('candidate_id'));

        return back()->with('success', 'Candidate deleted successfully.');
    }
}

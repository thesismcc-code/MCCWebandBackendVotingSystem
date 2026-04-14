<?php

namespace App\Http\Controllers;

use App\Application\RegisterVotes\RegisterVotes;
use App\Domain\Election\ElectionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SAOFinalResult extends Controller
{
    public function __construct(
        private RegisterVotes $registerVotes,
        private ElectionRepository $electionRepository,
    ) {}

    public function index(): View
    {
        return view('saofinalresult', [
            'finalResults' => $this->buildFinalResultsData(),
        ]);
    }

    public function liveData(): JsonResponse
    {
        return response()->json($this->buildFinalResultsData());
    }

    public function publish(): RedirectResponse
    {
        $finalResults = $this->buildFinalResultsData();
        $electionId = (string) data_get($finalResults, 'election.id', '');

        if ($electionId === '') {
            return back()->with('error', 'No election available for publishing final results.');
        }

        if ((bool) data_get($finalResults, 'publish.is_published', false)) {
            return back()->with('error', 'Final results are already published.');
        }

        $actor = Session::get('auth_user', []);
        $this->electionRepository->publishFinalResults($electionId, [
            'published_by' => (string) ($actor['id'] ?? ''),
            'published_by_name' => trim(
                ((string) ($actor['first_name'] ?? '')).' '.((string) ($actor['last_name'] ?? ''))
            ),
        ]);

        return back()->with('success', 'Official results have been published.');
    }

    public function exportPdf(): Response
    {
        $finalResults = $this->buildFinalResultsData();

        $pdf = app('dompdf.wrapper')
            ->loadView('pdf.sao-final-results-pdf', [
                'finalResults' => $finalResults,
                'generatedAt' => now(),
            ])
            ->setPaper('a4', 'portrait');

        return $pdf->download('sao-final-results-'.now()->format('Y-m-d').'.pdf');
    }

    /**
     * @return array{
     *     election: array{id: string, name: string, status: string},
     *     sections: array<int, array{
     *         position: string,
     *         max_votes: int,
     *         candidates: array<int, array{name: string, votes: int, percentage: float}>,
     *         winners: array<int, array{name: string, votes: int, percentage: float}>
     *     }>,
     *     publish: array{is_published: bool, published_at: ?string, published_by_name: ?string}
     * }
     */
    private function buildFinalResultsData(): array
    {
        $election = $this->electionRepository->getActiveElection();
        $electionId = $election?->getId() ?? '';
        $liveResults = $this->registerVotes->liveCandidateResult();
        $positionLimits = $this->buildPositionSeatMap($electionId);

        $sections = [];
        foreach ($liveResults as $positionName => $candidates) {
            $maxVotes = $positionLimits[$positionName] ?? 1;
            $normalizedCandidates = array_map(
                fn (array $candidate): array => [
                    'name' => (string) ($candidate['name'] ?? 'Unknown'),
                    'votes' => (int) ($candidate['votes'] ?? 0),
                    'percentage' => (float) ($candidate['percentage'] ?? 0.0),
                ],
                $candidates
            );

            $sections[] = [
                'position' => (string) $positionName,
                'max_votes' => $maxVotes,
                'candidates' => $normalizedCandidates,
                'winners' => array_slice($normalizedCandidates, 0, $maxVotes),
            ];
        }

        $publishMetadata = $this->getPublishMetadata($electionId);

        return [
            'election' => [
                'id' => $electionId,
                'name' => $election?->getElectionName() ?? 'No Active Election',
                'status' => $election ? ucfirst($election->getStatus()) : 'N/A',
            ],
            'sections' => $sections,
            'publish' => $publishMetadata,
        ];
    }

    /**
     * @return array<string, int>
     */
    private function buildPositionSeatMap(string $electionId): array
    {
        $positionMap = [];
        $positions = $this->electionRepository->getAllPositions();

        foreach ($positions as $position) {
            if ($electionId !== '' && $position->getElectionId() !== $electionId) {
                continue;
            }

            $positionMap[$position->getPositionName()] = max(1, $position->getMaxVotes());
        }

        return $positionMap;
    }

    /**
     * @return array{is_published: bool, published_at: ?string, published_by_name: ?string}
     */
    private function getPublishMetadata(string $electionId): array
    {
        if ($electionId === '') {
            return [
                'is_published' => false,
                'published_at' => null,
                'published_by_name' => null,
            ];
        }

        return $this->electionRepository->getFinalResultsPublishMetadata($electionId);
    }
}

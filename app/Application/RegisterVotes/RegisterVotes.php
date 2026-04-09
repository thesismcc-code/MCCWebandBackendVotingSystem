<?php

namespace App\Application\RegisterVotes;

use App\Domain\Votes\Votes;
use App\Domain\Votes\VotesRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class RegisterVotes
{
    private VotesRepository $votesRepository;
    public function __construct(VotesRepository $votesRepository)
    {
        $this->votesRepository = $votesRepository;
    }

    public function liveVoteCast(): int
    {
        return $this->votesRepository->liveVoteCast();
    }

    public function liveCandidateResult(): array
    {
        return $this->votesRepository->liveCandidateResult();
    }

    public function getVotingLogs(int $perPage, ?string $search, ?string $course, ?string $yearLevel): LengthAwarePaginator
    {
        return $this->votesRepository->getVotingLogs($perPage, $search, $course, $yearLevel);
    }

    public function getAllVotingLogsForExport(?string $search = null, ?string $course = null, ?string $yearLevel = null): array
    {
        return $this->votesRepository->getAllVotingLogsForExport($search, $course, $yearLevel);
    }
}

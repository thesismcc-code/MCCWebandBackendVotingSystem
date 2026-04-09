<?php

namespace App\Domain\Votes;

use Illuminate\Pagination\LengthAwarePaginator;

interface VotesRepository
{
    public function getVotes(int $id): ?Votes;
    public function getVotesCount(int $id): int;
    public function addVote(Votes $vote): ?Votes;
    public function liveVoteCast(): int;
    public function liveCandidateResult(): array;
    public function getVotingLogs(int $perPage, ?string $search, ?string $course, ?string $yearLevel): LengthAwarePaginator;
    public function getAllVotingLogsForExport(?string $search = null, ?string $course = null, ?string $yearLevel = null): array;
}

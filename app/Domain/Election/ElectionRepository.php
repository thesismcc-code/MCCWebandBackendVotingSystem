<?php

namespace App\Domain\Election;

interface ElectionRepository
{
    public function getActiveElection(): ?Election;

    public function updateElectionSchedule(string $electionId, array $data): void;

    public function updateElectionGeneral(string $electionId, array $data): void;

    public function getElection(): ?Election;

    public function saveElection(array $data): void;

    public function getAllPositions(): array;

    public function savePosition(array $data): void;

    public function updatePosition(string $id, array $data): void;

    public function deletePosition(string $id): void;

    public function getTotalPositions(): int;

    public function getAllCandidates(): array;

    public function getCandidatesByPosition(string $positionName): array;

    public function saveCandidate(array $data): void;

    public function updateCandidate(string $id, array $data): void;

    public function deleteCandidate(string $id): void;

    public function getTotalCandidates(): int;

    /**
     * @return array{is_published: bool, published_at: ?string, published_by_name: ?string}
     */
    public function getFinalResultsPublishMetadata(string $electionId): array;

    /**
     * @param  array{published_by: string, published_by_name: string}  $actor
     */
    public function publishFinalResults(string $electionId, array $actor): void;
}

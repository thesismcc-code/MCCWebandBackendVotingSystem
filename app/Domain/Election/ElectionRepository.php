<?php

namespace App\Domain\Election;

interface ElectionRepository
{
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
}

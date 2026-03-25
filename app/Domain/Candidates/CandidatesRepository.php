<?php

namespace App\Domain\Candidates;

interface CandidatesRepository
{
    public function getCandidates(): array;
    public function getRunnCandidates(): array;
    public function getRunnCandidatesCount(): int;
}

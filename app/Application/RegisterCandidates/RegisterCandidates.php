<?php

namespace App\Application\RegisterCandidates;

use App\Domain\Candidates\CandidatesRepository;

class RegisterCandidates
{
    private CandidatesRepository $candidatesRepository;
    public function __construct(CandidatesRepository $candidatesRepository)
    {
        $this->candidatesRepository = $candidatesRepository;
    }
    public function getCandidates(): array
    {
        return $this->candidatesRepository->getCandidates();
    }
    public function getRunnCandidates(): array
    {
        return $this->candidatesRepository->getRunnCandidates();
    }

    public function getRunnCandidatesCount(): int
    {
        return $this->candidatesRepository->getRunnCandidatesCount();
    }
}

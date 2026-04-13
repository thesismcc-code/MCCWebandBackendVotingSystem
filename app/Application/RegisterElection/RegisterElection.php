<?php

namespace App\Application\RegisterElection;

use App\Domain\Election\ElectionRepository;

class RegisterElection
{
    private ElectionRepository $electionRepository;

    public function __construct(ElectionRepository $electionRepository)
    {
        $this->electionRepository = $electionRepository;
    }

    public function getAllPosistion(): array
    {
        return $this->electionRepository->getAllPositions();
    }
    public function getTotalPositions()
    {
        return $this->electionRepository->getTotalPositions();
    }
    public function getTotalCandidates()
    {
        return $this->electionRepository->getTotalCandidates();
    }
}

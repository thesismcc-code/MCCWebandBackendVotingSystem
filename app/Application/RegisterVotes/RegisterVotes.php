<?php

namespace App\Application\RegisterVotes;

use App\Domain\Votes\Votes;
use App\Domain\Votes\VotesRepository;

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
}

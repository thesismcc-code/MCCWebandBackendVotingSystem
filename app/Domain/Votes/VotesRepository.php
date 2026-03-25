<?php

namespace App\Domain\Votes;

interface VotesRepository
{
    public function getVotes(int $id): ?Votes;
    public function getVotesCount(int $id): int;
    public function addVote(Votes $vote): ?Votes;
    public function liveVoteCast(): int;


}

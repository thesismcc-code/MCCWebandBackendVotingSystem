<?php

namespace App\Eloquent\Vote;

use App\Domain\Votes\Votes;
use App\Domain\Votes\VotesRepository;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;

class EloquentVoteRepository implements VotesRepository
{
    private Reference $db;
    private Reference $votesDb;
    private Reference $electionsDb;

    public function __construct(Database $db)
    {
        $this->db          = $db->getReference('users');
        $this->votesDb     = $db->getReference('votes');
        $this->electionsDb = $db->getReference('elections');
    }
    public function getVotes(int $id): ?Votes
    {
        return null;
    }
    public function getVotesCount(int $id): int
    {
        throw new \Exception('Not implemented');
    }
    public function addVote(Votes $vote): ?Votes
    {
        throw new \Exception('Not implemented');
    }
    public function liveVoteCast(): int
    {
        $electionSnapshot = $this->electionsDb
            ->orderByChild('status')
            ->equalTo('active')
            ->getSnapshot();

        if (!$electionSnapshot->exists() || $electionSnapshot->getValue() === null) {
            return 0;
        }

        $activeElectionId = array_key_first($electionSnapshot->getValue());

        $votesSnapshot = $this->votesDb
            ->orderByChild('election_id')
            ->equalTo($activeElectionId)
            ->getSnapshot();

        if (!$votesSnapshot->exists() || $votesSnapshot->getValue() === null) {
            return 0;
        }

        $uniqueVoterIds = [];
        foreach ($votesSnapshot->getValue() as $vote) {
            if (isset($vote['voter_id'])) {
                $uniqueVoterIds[$vote['voter_id']] = true;
            }
        }

        return count($uniqueVoterIds);
    }
}

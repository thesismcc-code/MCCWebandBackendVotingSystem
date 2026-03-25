<?php

namespace App\Domain\Votes;

class Votes
{
    private ?string $id;
    private string $voters_id;
    private string $candidate_id;
    private string $election_id;
    private string $position;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        ?string $id,
        string $voters_id,
        string $candidate_id,
        string $election_id,
        string $position,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->voters_id = $voters_id;
        $this->candidate_id = $candidate_id;
        $this->election_id = $election_id;
        $this->position = $position;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'voters_id' => $this->voters_id,
            'candidate_id' => $this->candidate_id,
            'election_id' => $this->election_id,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getVotersId(): ?string
    {
        return $this->voters_id;
    }
    public function getCandidateId(): ?string
    {
        return $this->candidate_id;
    }
    public function getElectionId(): ?string
    {
        return $this->election_id;
    }
    public function getPosition(): ?string
    {
        return $this->position;
    }
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
}

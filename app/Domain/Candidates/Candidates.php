<?php

namespace App\Domain\Candidates;

class Candidates
{
    private ?string $id;
    private ?string $party_list_id;
    private ?string $election_id;
    private ?string $position_id;
    private ?string $manifesto;
    private ?string $status;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        string $id,
        ?string $party_list_id,
        ?string $election_id,
        ?string $position_id,
        ?string $manifesto,
        ?string $status,
        ?string $created_at,
        ?string $updated_at
    ) {
        $this->id = $id;
        $this->party_list_id = $party_list_id;
        $this->election_id = $election_id;
        $this->position_id = $position_id;
        $this->manifesto = $manifesto;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function toArray() : array{
        return [
            "id"=> $this->id,
            "party_list_id"=> $this->party_list_id,
            "election_id"=> $this->election_id,
            "position_id"=> $this->position_id,
            "manifesto"=> $this->manifesto,
            "status"=> $this->status,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at
        ];
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    public function getPartyListId(): ?string
    {
        return $this->party_list_id;
    }
    public function getElectionId(): ?string
    {
        return $this->election_id;
    }
    public function getPositionId(): ?string
    {
        return $this->position_id;
    }
    public function getManifesto(): ?string
    {
        return $this->manifesto;
    }
    public function getStatus(): ?string
    {
        return $this->status;
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

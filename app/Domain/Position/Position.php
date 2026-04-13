<?php

namespace App\Domain\Position;

class Position
{
    public function __construct(
        private string $id,
        private string $electionId,
        private string $positionName,
        private int    $maxVotes,
        private string $createdAt,
        private string $updatedAt,
    ) {}
    public static function fromFirebase(array $data): self
    {
        return new self(
            id: $data['id']            ?? '',
            electionId: $data['election_id']   ?? '',
            positionName: $data['position_name'] ?? '',
            maxVotes: (int) ($data['max_votes'] ?? 1),
            createdAt: $data['created_at']    ?? '',
            updatedAt: $data['updated_at']    ?? '',
        );
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getElectionId(): string
    {
        return $this->electionId;
    }
    public function getPositionName(): string
    {
        return $this->positionName;
    }
    public function getMaxVotes(): int
    {
        return $this->maxVotes;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'election_id'   => $this->electionId,
            'position_name' => $this->positionName,
            'max_votes'     => $this->maxVotes,
            'created_at'    => $this->createdAt,
            'updated_at'    => $this->updatedAt,
        ];
    }
}

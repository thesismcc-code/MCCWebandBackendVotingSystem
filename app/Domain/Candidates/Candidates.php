<?php

namespace App\Domain\Candidates;

class Candidates
{
    public function __construct(
        private string $id,
        private string $electionId,
        private string $positionId,
        private string $positionName,
        private string $fullName,
        private string $course,
        private string $year,
        private string $politicalParty,
        private string $platformAgenda,
        private string $imageUrl,
        private string $status,
        private string $createdAt,
        private string $updatedAt,
    ) {}
    public static function fromFirebase(array $data): self
    {
        return new self(
            id: $data['id']              ?? '',
            electionId: $data['election_id']     ?? '',
            positionId: $data['position_id']     ?? '',
            positionName: $data['position_name']   ?? '',
            fullName: $data['full_name']        ?? '',
            course: $data['course']           ?? '',
            year: $data['year']             ?? '',
            politicalParty: $data['political_party']  ?? '',
            platformAgenda: $data['platform_agenda']  ?? '',
            imageUrl: $data['image_url']        ?? '',
            status: $data['status']           ?? 'active',
            createdAt: $data['created_at']      ?? '',
            updatedAt: $data['updated_at']      ?? '',
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
    public function getPositionId(): string
    {
        return $this->positionId;
    }
    public function getPositionName(): string
    {
        return $this->positionName;
    }
    public function getFullName(): string
    {
        return $this->fullName;
    }
    public function getCourse(): string
    {
        return $this->course;
    }
    public function getYear(): string
    {
        return $this->year;
    }
    public function getPoliticalParty(): string
    {
        return $this->politicalParty;
    }
    public function getPlatformAgenda(): string
    {
        return $this->platformAgenda;
    }
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
    public function hasImage(): bool
    {
        return $this->imageUrl !== '';
    }
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'election_id'    => $this->electionId,
            'position_id'    => $this->positionId,
            'position_name'  => $this->positionName,
            'full_name'      => $this->fullName,
            'course'         => $this->course,
            'year'           => $this->year,
            'political_party' => $this->politicalParty,
            'platform_agenda' => $this->platformAgenda,
            'image_url'      => $this->imageUrl,
            'status'         => $this->status,
            'created_at'     => $this->createdAt,
            'updated_at'     => $this->updatedAt,
        ];
    }
}

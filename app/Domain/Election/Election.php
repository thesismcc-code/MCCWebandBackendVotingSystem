<?php

namespace App\Domain\Election;

class Election
{
    private string $id;
    private string $electionName;
    private string $semester;
    private string $academicYear;
    private string $startDate;
    private string $endDate;
    private string $openingTime;
    private string $closingTime;
    private string $status;
    private string $createdAt;
    private string $updatedAt;
    public function __construct(
        string $id,
        string $electionName,
        string $semester,
        string $academicYear,
        string $startDate,
        string $endDate,
        string $openingTime,
        string $closingTime,
        string $status,
        string $createdAt,
        string $updatedAt,
    ) {
        $this->id = $id;
        $this->electionName = $electionName;
        $this->semester = $semester;
        $this->academicYear = $academicYear;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->openingTime = $openingTime;
        $this->closingTime = $closingTime;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getElectionName(): string
    {
        return $this->electionName;
    }
    public function getSemester(): string
    {
        return $this->semester;
    }
    public function getAcademicYear(): string
    {
        return $this->academicYear;
    }
    public function getStartDate(): string
    {
        return $this->startDate;
    }
    public function getEndDate(): string
    {
        return $this->endDate;
    }
    public function getOpeningTime(): string
    {
        return $this->openingTime;
    }
    public function getClosingTime(): string
    {
        return $this->closingTime;
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
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming';
    }
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'election_name' => $this->electionName,
            'semester'      => $this->semester,
            'academic_year' => $this->academicYear,
            'start_date'    => $this->startDate,
            'end_date'      => $this->endDate,
            'opening_time'  => $this->openingTime,
            'closing_time'  => $this->closingTime,
            'status'        => $this->status,
            'created_at'    => $this->createdAt,
            'updated_at'    => $this->updatedAt,
        ];
    }
    public static function fromFirebase(array $data): self
    {
        return new self(
            id: $data['id']            ?? '',
            electionName: $data['election_name'] ?? '',
            semester: $data['semester']      ?? '',
            academicYear: $data['academic_year'] ?? '',
            startDate: $data['start_date']    ?? '',
            endDate: $data['end_date']      ?? '',
            openingTime: $data['opening_time']  ?? '',
            closingTime: $data['closing_time']  ?? '',
            status: $data['status']        ?? 'upcoming',
            createdAt: $data['created_at']    ?? '',
            updatedAt: $data['updated_at']    ?? '',
        );
    }
}

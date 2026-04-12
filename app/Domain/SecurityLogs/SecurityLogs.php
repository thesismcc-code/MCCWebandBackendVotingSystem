<?php

namespace App\Domain\SecurityLogs;

class SecurityLogs
{
    public function __construct(
        private string  $id,
        private string  $studentId,
        private string  $firstName,
        private string  $lastName,
        private string  $course,
        private string  $yearLevel,
        private string  $logType,
        private string  $firstAttempt,
        private string  $secondAttempt,
        private string  $electionId,
        private string  $status,
        private string  $createdAt,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }
    public function getStudentId(): string
    {
        return $this->studentId;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getCourse(): string
    {
        return $this->course;
    }
    public function getYearLevel(): string
    {
        return $this->yearLevel;
    }
    public function getLogType(): string
    {
        return $this->logType;
    }
    public function getFirstAttempt(): string
    {
        return $this->firstAttempt;
    }
    public function getSecondAttempt(): string
    {
        return $this->secondAttempt;
    }
    public function getElectionId(): string
    {
        return $this->electionId;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function getFullName(): string
    {
        return trim("{$this->firstName} {$this->lastName}");
    }
    public function isDuplicateVote(): bool
    {
        return $this->logType === 'duplicate_vote';
    }
    public function isRejectedFingerprint(): bool
    {
        return $this->logType === 'rejected_fingerprint';
    }
    public function isDeniedAccess(): bool
    {
        return $this->logType === 'denied_access';
    }
    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }
    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'student_id'     => $this->studentId,
            'first_name'     => $this->firstName,
            'last_name'      => $this->lastName,
            'course'         => $this->course,
            'year_level'     => $this->yearLevel,
            'log_type'       => $this->logType,
            'first_attempt'  => $this->firstAttempt,
            'second_attempt' => $this->secondAttempt,
            'election_id'    => $this->electionId,
            'status'         => $this->status,
            'created_at'     => $this->createdAt,
        ];
    }
    public static function fromFirebase(array $data): self
    {
        return new self(
            id: $data['id']              ?? '',
            studentId: $data['student_id']      ?? '',
            firstName: $data['first_name']      ?? '',
            lastName: $data['last_name']        ?? '',
            course: $data['course']           ?? '',
            yearLevel: $data['year_level']       ?? '',
            logType: $data['log_type']         ?? '',
            firstAttempt: $data['first_attempt']   ?? '',
            secondAttempt: $data['second_attempt']  ?? '',
            electionId: $data['election_id']     ?? '',
            status: $data['status']           ?? 'blocked',
            createdAt: $data['created_at']      ?? '',
        );
    }
}

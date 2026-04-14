<?php

namespace App\Domain\SystemActivity;

class SystemActivity
{
    public function __construct(
        private string $id,
        private string $userId,
        private string $level,
        private string $activity,
        private string $createdAt,
        private string $updatedAt,
        private string $role = '',
        private string $httpStatus = '',
        private string $routeName = '',
        private string $ipAddress = '',
        private string $authChannel = '',
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getActivity(): string
    {
        return $this->activity;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getHttpStatus(): string
    {
        return $this->httpStatus;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function getAuthChannel(): string
    {
        return $this->authChannel;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromFirebase(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            userId: (string) ($data['user_id'] ?? $data['userId'] ?? ''),
            level: (string) ($data['level'] ?? 'info'),
            activity: (string) ($data['activity'] ?? ''),
            createdAt: (string) ($data['created_at'] ?? $data['createdAt'] ?? ''),
            updatedAt: (string) ($data['updated_at'] ?? $data['updatedAt'] ?? ''),
            role: (string) ($data['role'] ?? ''),
            httpStatus: (string) ($data['http_status'] ?? $data['httpStatus'] ?? ''),
            routeName: (string) ($data['route_name'] ?? $data['routeName'] ?? ''),
            ipAddress: (string) ($data['ip_address'] ?? $data['ipAddress'] ?? ''),
            authChannel: (string) ($data['auth_channel'] ?? $data['authChannel'] ?? ''),
        );
    }

    /**
     * @return array<string, string>
     */
    public function toFirebasePayload(): array
    {
        return [
            'user_id' => $this->userId,
            'level' => $this->level,
            'activity' => $this->activity,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'role' => $this->role,
            'http_status' => $this->httpStatus,
            'route_name' => $this->routeName,
            'ip_address' => $this->ipAddress,
            'auth_channel' => $this->authChannel,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'level' => $this->level,
            'activity' => $this->activity,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'role' => $this->role,
            'httpStatus' => $this->httpStatus,
            'routeName' => $this->routeName,
            'ipAddress' => $this->ipAddress,
            'authChannel' => $this->authChannel,
        ];
    }
}

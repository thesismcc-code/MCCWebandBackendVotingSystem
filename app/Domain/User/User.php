<?php

namespace App\Domain\User;

class User
{
    private ?int $id;
    private string $uid;
    private string $first_name;
    private string $middle_name;
    private string $last_name;
    private string $email;
    private string $password;
    private string $role;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        ?int $id = null,
        string $uid,
        string $first_name,
        string $middle_name,
        string $last_name,
        string $email,
        string $password,
        string $role,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->uid = $uid;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): string
    {
        return $this->uid;
    }
    public function getFirstName(): string
    {
        return $this->first_name;
    }
    public function getMiddleName(): string
    {
        return $this->middle_name;
    }
    public function getLastName(): string
    {
        return $this->last_name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }
}

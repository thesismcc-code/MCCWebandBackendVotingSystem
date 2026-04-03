<?php

namespace App\Domain\User;

class User
{
    private ?string $id;
    private string $first_name;
    private string $middle_name;
    private string $last_name;
    private string $email;
    private ?string $password;
    private string $role;
    private ?string $admin_id;
    private ?string $student_id;
    private ?string $course;
    private ?string $year_level;
    private ?string $comelec_id;
    private ?string $email_verified_at;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        ?string $id,
        string $first_name,
        string $middle_name,
        string $last_name,
        string $email,
        ?string $password,
        string $role,
        ?string $admin_id = null,
        ?string $student_id = null,
        ?string $course = null,
        ?string $year_level = null,
        ?string $comelec_id = null,
        ?string $email_verified_at = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->admin_id = $admin_id;
        $this->student_id = $student_id;
        $this->course = $course;
        $this->year_level = $year_level;
        $this->comelec_id = $comelec_id;
        $this->email_verified_at = $email_verified_at;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
            'admin_id' => $this->admin_id,
            'student_id' => $this->student_id,
            'course' => $this->course,
            'year_level' => $this->year_level,
            'comelec_id' => $this->comelec_id,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getAdminId(): ?string
    {
        return $this->admin_id;
    }

    public function getStudentId(): ?string
    {
        return $this->student_id;
    }

    public function getCourse(): ?string
    {
        return $this->course;
    }
    public function getYearLevel(): ?string
    {
        return $this->year_level;
    }

    public function getComelecId(): ?string
    {
        return $this->comelec_id;
    }

    public function getEmailVerifiedAt(): ?string
    {
        return $this->email_verified_at;
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

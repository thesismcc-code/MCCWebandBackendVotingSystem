<?php

namespace App\Domain\PartyList;

class PartyList
{
    private ?string $id;
    private string $partyName;
    private string $description;
    private ?string $logo;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        string $id,
        string $partyName,
        string $description,
        string $logo,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->partyName = $partyName;
        $this->description = $description;
        $this->logo = $logo;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "party_name" => $this->partyName,
            "description" => $this->description,
            "logo" => $this->logo,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getPartyName(): string
    {
        return $this->partyName;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getLogo(): string
    {
        return $this->logo;
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

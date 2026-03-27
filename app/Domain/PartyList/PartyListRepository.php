<?php

namespace App\Domain\PartyList;

interface PartyListRepository
{
    public function getByID(string $id): PartyList;
    public function findByPartyList(string $partyList): PartyList;
    public function createPartyList(string $partyList): PartyList;
    public function updatePartyList(string $partyList): PartyList;
    public function deletePartyList(string $partyList): bool;
    public function getAllPartyList(): array;
}

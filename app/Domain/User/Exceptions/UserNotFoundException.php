<?php

namespace App\Domain\User\Exceptions;

class UserNotFoundException extends \Exception
{
    public function __construct(int $id)
    {
        parent::__construct("User with ID {$id} not found.");
    }
}

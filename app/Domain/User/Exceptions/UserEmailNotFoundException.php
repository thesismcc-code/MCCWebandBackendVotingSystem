<?php

namespace App\Domain\User\Exceptions;

class UserEmailNotFoundException extends \Exception
{
    public function __construct(string $email)
    {
        parent::__construct("User with email {$email} not found.");
    }
}

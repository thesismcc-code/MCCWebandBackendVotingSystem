<?php

namespace App\Domain\User\Exceptions;

class UserPersistenceException extends \Exception
{
    public function __construct(string $operation, ?\Throwable $previous = null)  // Add ? before \Throwable
    {
        parent::__construct("Failed to {$operation} user.", 0, $previous);
    }
}

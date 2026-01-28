<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Firebase extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "firebase.database";
    }
}

<?php

namespace Hosomikai\Kotsms\Facade;

use Illuminate\Support\Facades\Facade;

class Kotsms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'kotsms';
    }
}

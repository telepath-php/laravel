<?php

namespace Telepath\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Telepath extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'telepath';
    }

}
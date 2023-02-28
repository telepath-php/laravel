<?php

namespace Telepath\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Telepath\Laravel\Telepath
 */
class Telepath extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'telepath';
    }

}
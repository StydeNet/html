<?php

namespace Styde\Html\Facades;

use Illuminate\Support\Facades\Facade;

class Access extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'access';
    }
}

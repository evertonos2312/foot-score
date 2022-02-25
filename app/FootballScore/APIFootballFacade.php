<?php

namespace App\FootballScore;

use Illuminate\Support\Facades\Facade;

class APIFootballFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    { return 'football'; }
}


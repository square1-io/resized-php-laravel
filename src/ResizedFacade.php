<?php

namespace Square1\Laravel\Resized;

use Illuminate\Support\Facades\Facade;

class ResizedFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'resized';
    }
}

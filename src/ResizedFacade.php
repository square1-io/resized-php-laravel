<?php

namespace Square1\Laravel\Resized;

use Illuminate\Support\Facades\Facade;

class ResizedFacade extends Facade
{
	/**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'resized';
    }
}

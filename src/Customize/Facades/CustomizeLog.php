<?php

namespace Customize\Facades;

use Illuminate\Support\Facades\Facade;

class CustomizeLog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'customizeLog';
    }
}
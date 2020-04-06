<?php

namespace Nanuc\Loggy\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string cdn(string $url, string $quality = null)
 *
 *  @see \Olelo\Cassy\Cassy
 */
class Loggy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'loggy';
    }
}

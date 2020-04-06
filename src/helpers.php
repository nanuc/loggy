<?php

use Nanuc\Loggy\Facades\Loggy;

if (! function_exists('loggy')) {
    /**
     * Send a loggy
     */
    function loggy($message)
    {
        return Loggy::send($message);
    }
}
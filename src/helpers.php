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

if (! function_exists('loggy_start')) {
    /**
     * Start a time measurement
     */
    function loggy_start($name = 'Measurement')
    {
        return Loggy::startMeasurement($name);
    }
}

if (! function_exists('loggy_stop')) {
    /**
     * Stop and send a time measurement
     */
    function loggy_stop($name = 'Measurement')
    {
        return Loggy::stopMeasurement($name);
    }
}
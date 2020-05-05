<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Appiusattaprobus\ServerTiming\StopWatch;

try {
    $closureTimer = StopWatch::measureFunc('Closure', function () {
        usleep(500000);
    });

    StopWatch::setTimingHeader(); // Must be set before output any data
} catch (Exception $e) {
    echo $e->getMessage();
}

// $closureTimer->spendTime();
// StopWatch::elapsed('Closure');
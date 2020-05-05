<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Appiusattaprobus\ServerTiming\StopWatch;

try {
    StopWatch::start('Part');
    sleep(1);
    StopWatch::pause('Part');
    sleep(3);
    StopWatch::resume('Part');
    sleep(1);
    StopWatch::stop('Part'); // Not necessary. At set headers all timers stop

    StopWatch::setTimingHeader(); // Must be set before output any data
} catch (Exception $e) {
    echo $e->getMessage();
}

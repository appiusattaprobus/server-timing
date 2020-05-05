<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Appiusattaprobus\ServerTiming\StopWatch;

try {
    StopWatch::start('Whole');
    sleep(1);
    StopWatch::stop('Whole');

    StopWatch::setTimingHeader(); // Must be set before output any data
} catch (Exception $e) {
    echo $e->getMessage();
}

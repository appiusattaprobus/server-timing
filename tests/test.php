<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Appiusattaprobus\ServerTiming\StopWatch;

StopWatch::start('test2');
sleep(1);
StopWatch::stop('test');
sleep(1);
sleep(1);
StopWatch::setTimingHeader();

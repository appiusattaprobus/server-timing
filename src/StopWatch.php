<?php

namespace Appiusattaprobus\ServerTiming;

class StopWatch
{
    const DEFAULT_NAME = 'default';
    const PRECISION = 5;

    private static $timers = [];

    /**
     * Get current microtime
     *
     * @return float
     */
    public static function now()
    {
        return microtime(true);
    }

    /**
     * Init new timer
     *
     * @param string $name - timer name
     */
    public static function start($name = self::DEFAULT_NAME)
    {
        self::$timers[$name]['start'] = self::now();
    }

    /**
     * Stop timer
     *
     * @param string $name
     */
    public static function stop($name = self::DEFAULT_NAME)
    {
        self::$timers[$name]['stop'] = self::now();
    }

    /**
     * Get elapsed time
     *
     * @param string $name
     * @param int $precision
     * @return float
     * @throws \Exception
     */
    public static function elapsed($name = self::DEFAULT_NAME, $precision = self::PRECISION)
    {
        if (!isset(self::$timers[$name])) {
            throw new \Exception(__CLASS__ . ': timer not found');
        }

        return round(
            self::now() - self::$timers[$name]['start'],
            $precision
        );
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function isTimerStopped($name = self::DEFAULT_NAME)
    {
        return !empty(self::$timers[$name]['stop']);
    }

    /**
     * @param string $name
     * @return float
     */
    public static function spendTime($name = self::DEFAULT_NAME)
    {
        if (self::isTimerStopped($name)) {
            return self::$timers[$name]['stop'] - self::$timers[$name]['start'];
        }

        return self::now() - self::$timers[$name]['start'];
    }

    /**
     * @return string
     */
    public static function getTimingHeader()
    {
        $metrics = [];
        $index = 0;
        foreach (self::$timers as $name => $timer) {
            if (!self::isTimerStopped($name)) {
                self::stop($name);
            }
            $metrics[] = $index . ';desc="' .   $name . '";dur=' . self::spendTime($name) * 1000;
            $index++;
        }

        return 'Server-Timing: ' . implode(', ', $metrics);
    }

    public static function setTimingHeader()
    {
        header(self::getTimingHeader());
    }

    public static function updateHeadersBeforeShutdown()
    {
        register_shutdown_function(function() {
            StopWatch::setTimingHeader();
        });
    }
}

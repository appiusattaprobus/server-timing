<?php

namespace Appiusattaprobus\ServerTiming;

class StopWatch
{
    const DEFAULT_NAME = 'default';

    private static $timers = [];

    /**
     * Get timer by name.
     *
     * @param string $name
     * @return Timer
     * @throws \Exception
     */
    public static function getTimer($name = '')
    {
        /** @var Timer|null $timer */
        $timer = isset(self::$timers[$name])
            ? self::$timers[$name]
            : null
        ;
        if (is_null($timer)) {
            throw new \Exception(__CLASS__ . ' timer "' . $name . '" not found');
        }

        return $timer;
    }

    /**
     * Create new empty timer.
     *
     * @param string $name
     * @return Timer
     * @throws \Exception
     */
    public static function createTimer($name = self::DEFAULT_NAME)
    {
        if (isset(self::$timers[$name])) {
            throw new \Exception(__CLASS__ . ' timer "' . $name . '" already exists');
        }
        self::$timers[$name] = (new Timer())->setName($name);

        return self::$timers[$name];
    }

    /**
     * Start timer.
     *
     * @param string $name
     * @return Timer
     * @throws \Exception
     */
    public static function start($name = self::DEFAULT_NAME)
    {
        /** @var Timer|null $timer */
        $timer = isset(self::$timers[$name])
            ? self::$timers[$name]
            : null
        ;
        if (is_null($timer)) {
            $timer = (new Timer())->setName($name);
            self::$timers[$name] = $timer;
        }
        $timer->start();

        return $timer;
    }

    /**
     * Stop timer.
     *
     * @param string $name
     * @return Timer
     * @throws \Exception
     */
    public static function stop($name = self::DEFAULT_NAME)
    {
        return self::getTimer($name)->stop();
    }

    /**
     * Set timer on pause.
     *
     * @param string $name
     * @return Timer
     * @throws \Exception
     */
    public static function pause($name = self::DEFAULT_NAME)
    {
        return self::getTimer($name)->pause();
    }

    /**
     * Resume timer.
     *
     * @param string $name
     * @return Timer
     * @throws \Exception
     */
    public static function resume($name = self::DEFAULT_NAME)
    {
        return self::getTimer($name)->resume();
    }

    /**
     * Method for measure closure function.
     *
     * @param $name
     * @param $func
     * @return Timer
     * @throws \Exception
     */
    public static function measureFunc($name, $func)
    {
        if (empty($name)) {
            throw new \Exception(__CLASS__ . ' measureFunc name cannot be empty');
        }
        if (!is_callable($func)) {
            throw new \Exception(__CLASS__ . ' measureFunc expect callable arg');
        }
        /** @var Timer $timer */
        $timer = self::start($name);
        $func();

        return $timer->stop();
    }

    /**
     * Get elapsed timer time.
     *
     * @param string $name
     * @param null $precision
     * @return float
     * @throws \Exception
     */
    public static function elapsed($name = self::DEFAULT_NAME, $precision = null)
    {
        return $precision
            ? round(self::getTimer($name)->spendTime(), $precision)
            : self::getTimer($name)->spendTime()
        ;
    }

    /**
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    public static function isStopped($name = self::DEFAULT_NAME)
    {
        return self::getTimer($name)->isStopped();
    }

    /**
     * Get string of Server-Timing header.
     * See https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Server-Timing
     *
     * @return string
     */
    public static function getTimingHeader()
    {
        $index = 0;
        $metrics = [];
        /** @var Timer $timer */
        foreach (self::$timers as $name => $timer) {
            $metrics[] = $index . ';desc="' .   $name . '";dur=' . $timer->spendTime() * 1000;
            $index++;
        }

        return 'Server-Timing: ' . implode(', ', $metrics);
    }

    /**
     * Set Server-Timing header
     */
    public static function setTimingHeader()
    {
        header(self::getTimingHeader());
    }

    /**
     * Method for auto set Server-Timing header before script end.
     * Be sure script not out any data before ends execution!
     */
    public static function updateHeadersBeforeShutdown()
    {
        register_shutdown_function(function() {
            StopWatch::setTimingHeader();
        });
    }
}

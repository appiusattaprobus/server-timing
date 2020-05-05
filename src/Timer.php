<?php

namespace Appiusattaprobus\ServerTiming;

class Timer
{
    private $name = 'unnamed';
    private $description = '';
    private $start_time = null;
    private $stop_time = null;
    private $addition_time = 0;
    private $on_pause = false;

    /**
     * Get current time
     *
     * @param bool $as_float
     * @return mixed
     */
    public static function now($as_float = true)
    {
        return microtime($as_float);
    }

    /**
     * Set name of timer
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set description of timer
     *
     * @param string $desc
     * @return $this
     */
    public function setDescription($desc)
    {
        $this->description = $desc;

        return $this;
    }

    /**
     * Set start value of timer.
     *
     * @param null|float $value - microseconds
     * @return $this
     * @throws \Exception
     */
    public function setStartValue($value = null)
    {
        if ($this->isInProgress()) {
            throw new \Exception(__CLASS__ . ' with name "' . $this->name . '" in progress');
        }
        $this->start_time = $value;

        return $this;
    }

    /**
     * Start timer. Set current time as start value.
     *
     * @return $this
     * @throws \Exception
     */
    public function start()
    {
        if (!is_null($this->start_time)) {
            throw new \Exception(__CLASS__ . ' with name "' . $this->name . '" already started');
        }
        $this->start_time = self::now();
        $this->on_pause = false;

        return $this;
    }

    /**
     * Stop timer. Set current time as stop value.
     *
     * @return $this
     * @throws \Exception
     */
    public function stop()
    {
        if ($this->isStopped()) {
            throw new \Exception(__CLASS__ . ' with name "' . $this->name . '" already stopped');
        }
        $this->stop_time = self::now();

        return $this;
    }

    /**
     * Stop timer if not stopped yet.
     *
     * @return $this
     * @throws \Exception
     */
    public function stopItNotStopped()
    {
        if (!$this->isStopped()) {
            $this->stop();
        }

        return $this;
    }

    /**
     * Reset all timer values by default.
     *
     * @return $this
     */
    public function reset()
    {
        $this->start_time = null;
        $this->stop_time = null;
        $this->addition_time = 0;

        return $this;
    }

    /**
     * Set timer on pause.
     *
     * @return $this
     * @throws \Exception
     */
    public function pause()
    {
        $this->on_pause = true;
        $this->stopItNotStopped();
        $spendTime = $this->spendTime();
        $this
            ->reset()
            ->addAdditionTime($spendTime)
        ;

        return $this;
    }

    /**
     * Resume timer.
     *
     * @return Timer
     * @throws \Exception
     */
    public function resume()
    {
        if (!$this->isOnPause()) {
            throw new \Exception(__CLASS__ . ' with name "' . $this->name . '" not on pause');
        }

        return $this->start();
    }

    /**
     * Add additional time to all spend time.
     *
     * @param int $ms
     * @return $this
     */
    public function addAdditionTime($ms = 0)
    {
        $this->addition_time += $ms;

        return $this;
    }

    /**
     * Get spend time.
     *
     * @return float
     */
    public function spendTime()
    {
        $time = $this->isStopped()
            ? $this->stop_time - $this->start_time
            : self::now() - $this->start_time
        ;

        return $time + $this->addition_time;
    }

    /**
     * @return bool
     */
    public function isStopped()
    {
        return $this->stop_time != null;
    }

    /**
     * @return bool
     */
    public function isOnPause()
    {
        return $this->on_pause;
    }

    /**
     * @return bool
     */
    public function isInProgress()
    {
        return $this->on_pause == false && $this->start_time != null;
    }
}

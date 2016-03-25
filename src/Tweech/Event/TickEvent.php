<?php

namespace Raideer\Tweech\Event;

class TickEvent extends Event
{
    protected $microtime;
    protected $time;

    public function __construct()
    {
        $this->microtime = microtime();
        $this->time = time();
    }

    public function getMicrotime()
    {
        return $this->microtime;
    }

    public function getTime()
    {
        return $this->time;
    }
}

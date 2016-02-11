<?php

namespace Raideer\Tweech\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;

class EventEmitter
{
    protected $dispatcher;

    protected function getDispatcher()
    {
        if (!$this->dispatcher) {
            $this->dispatcher = new EventDispatcher();
        }

        return $this->dispatcher;
    }

    public function addSubscriber($subscriber)
    {
        $this->getDispatcher()->addSubscriber($subscriber);
    }

    public function dispatch($name, $event)
    {
        $this->getDispatcher()->dispatch($name, $event);
    }

    public function listen($name, $callable)
    {
        $this->getDispatcher()->addListener($name, $callable);
    }
}

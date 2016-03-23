<?php

namespace Raideer\Tweech\Listeners;

use Raideer\Tweech\Event\EventEmitter;

class ListenerLoader
{
    protected $listeners = [];
    protected $loaded = [];
    protected $emitter;

    public function __construct(EventEmitter $emitter, array $listeners = [])
    {
        $this->listeners = $listeners;
        $this->emitter = $emitter;
    }

  /**
   * Adds to the listeners array.
   *
   * @param array/string $data
   */
  public function add($data)
  {
      if (is_array($data)) {
          $this->listeners = array_merge($this->listeners, $data);
      } elseif ($data instanceof \Symfony\Component\EventDispatcher\EventlistenerInterface) {
          if (!in_array(get_class($data), $this->loaded)) {
              $this->emitter->addListener($data);
              $this->loaded[] = get_class($data);
          }
      } else {
          if (is_string($data)) {
              $this->listeners[] = $data;
          }
      }
  }

    public function getListeners()
    {
        // return $this->emitter->getListeners();
    }

    public function loadAll()
    {
        /*
        * Get the list of stored listeners
        * @var array
        */
        $list = $this->listeners;

        /*
        * For each listener in the array of listeners
        */
        foreach ($list as $listener) {

            /*
            * Checking if the commmand is loaded
            * If it is, then we skip to the next one
            */
            if (in_array($listener, $this->loaded)) {
                continue;
            }

            /*
            * Checking if the class is actually a subclass of EventSubscriberInterface
            */
            $reflection = new \ReflectionClass($listener);
            if (!$reflection->isSubclassOf("Symfony\Component\EventDispatcher\EventSubscriberInterface")) {
                continue;
            }

            /*
            * Instantiating and adding the class to the event emitter (Client)
            */
            $this->emitter->addListener($reflection->newInstance());
            $this->loaded[] = $listener;
        }
    }
}

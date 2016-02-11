<?php

namespace Raideer\Tweech\Subscribers;

use Raideer\Tweech\Event\EventEmitter;

class SubscriberLoader
{
    protected $subscribers = [];
    protected $loaded = [];
    protected $emitter;

    public function __construct(EventEmitter $emitter, array $subscribers = [])
    {
        $this->subscribers = $subscribers;
        $this->emitter = $emitter;
    }

  /**
   * Adds to the subscribers array.
   *
   * @param array/string $data
   */
  public function add($data)
  {
      if (is_array($data)) {
          $this->subscribers = array_merge($this->subscribers, $data);
      } elseif ($data instanceof \Symfony\Component\EventDispatcher\EventSubscriberInterface) {
          if (!in_array(get_class($data), $this->loaded)) {
              $this->emitter->addSubscriber($data);
              $this->loaded[] = get_class($data);
          }
      } else {
          if (is_string($data)) {
              $this->subscribers[] = $data;
          }
      }
  }

    public function getListeners()
    {
        return $this->emitter->getListeners();
    }

    public function loadAll()
    {
        /*
     * Get the list of stored subscribers
     * @var array
     */
    $list = $this->subscribers;

    /*
     * For each subscriber in the array of subscribers
     */
    foreach ($list as $subscriber) {

      /*
       * Checking if the commmand is loaded
       * If it is, then we skip to the next one
       */
      if (in_array($subscriber, $this->loaded)) {
          continue;
      }

      /*
       * Checking if the class is actually a subclass of EventSubscriberInterface
       */
      $reflection = new \ReflectionClass($subscriber);
        if (!$reflection->isSubclassOf("Symfony\Component\EventDispatcher\EventSubscriberInterface")) {
            continue;
        }

      /*
       * Instantiating and adding the class to the event emitter (Client)
       */
      $this->emitter->addSubscriber($reflection->newInstance());
        $this->loaded[] = $subscriber;
    }
    }
}

<?php
namespace Raideer\Tweech\Subscribers;
use Raideer\Tweech\Event\EventEmitter;

class SubscriberLoader{

  protected $subscribers = [];
  protected $loaded = [];
  protected $emitter;

  public function __construct(EventEmitter $emitter, array $subscribers = array()){

    $this->subscribers = $subscribers;
    $this->emitter = $emitter;
  }

  /**
   * Adds to the subscribers array
   * @param array/string $data
   */
  public function add($data){
    if(is_array($data)){
      $this->subscribers = array_merge($this->subscribers, $data);
    }else{
      $this->subscribers[] = $path;
    }
  }

  public function loadAll(){
    /**
     * Get the list of stored subscribers
     * @var array
     */
    $list = $this->subscribers;

    /**
     * For each subscriber in the array of subscribers
     */
    foreach($list as $subscriber){
      $path = $subscriber;

      /**
       * Checking if the commmand is loaded
       * If it is, then we skip to the next one
       */
      if(in_array($subscriber, $this->loaded)){
        continue;
      }

      /**
       * Append ".php" if the path doesn't end with it
       */
      if(!ends_with($subscriber, ".php")){
        $path = $subscriber . ".php";
      }

      /**
       * Check if the file exists
       */
      if(!file_exists($path)) continue;

      /**
       * Get the class name from the file path
       */
      if(!preg_match('/[\\|\/](?P<class>[A-Za-z]+)\.php$/', $path, $match)) continue;

      /**
       * Requiring the class
       */
      require_once $path;
      $class = $match['class'];

      /**
       * Checking if the class is actually a subclass of EventSubscriberInterface
       */
      $reflection = new \ReflectionClass($class);
      if(!$reflection->isSubclassOf("Symfony\Component\EventDispatcher\EventSubscriberInterface")) continue;

      /**
       * Instantiating and adding the class to the event emitter (Client)
       */
      $this->emitter->addSubscriber(new $class());
      $this->loaded[] = $subscriber;
    }

  }

}

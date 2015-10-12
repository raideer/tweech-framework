<?php
namespace Raideer\Tweech\Subscriber;

class SubscriberLoader{

  protected $subscribers;

  public function __construct(array $subscribers = array()){

    $this->subscribers = $subscribers;
  }

  public function add($data){
    if(is_array($data)){
      $this->subscribers = array_merge($this->subscribers, $data);
    }else{
      $this->subscribers[] = $path;
    }
  }

  public function loadAll(){

    $list = $this->subscribers;
    print_r($list);

    foreach($list as $subscriber){

      if(ends_with($subscriber, ".php")){
        $path = $subscriber;
      }

      $path = "$subscriber.php";
      if(!file_exists($path)) continue;

      require_once $path;
      $class = "$subscriber";

      echo " s";

      $reflection = new \ReflectionClass($class);
      if($reflection->isSubclassOf("Symfony\Component\EventDispatcher\EventSubscriberInterface")){
        echo "subclass";
      }
      $subscriber = new $class();

      $this['client']->addSubscriber($subscriber);
    }

  }
}

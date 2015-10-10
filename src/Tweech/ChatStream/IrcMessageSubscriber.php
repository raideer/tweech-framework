<?php
namespace Raideer\Tweech\ChatStream;

use Raideer\Tweech\Event\EventSubscriber;

class IrcMessageSubscriber extends EventSubscriber{

  public static function getSubscribedEvents(){

    return array(
      'irc.message' => array(
        'onMessageReceived', 0
      )
    );

  }

  public function onMessageReceived(IrcMessageEvent $event){
    $message = $event->getMessage();

    print_r($message);
  }

}

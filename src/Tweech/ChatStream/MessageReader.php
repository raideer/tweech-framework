<?php
namespace Raideer\Tweech\ChatStream;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Raideer\Tweech\Event\IrcMessageEvent;

class MessageReader implements EventSubscriberInterface{

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

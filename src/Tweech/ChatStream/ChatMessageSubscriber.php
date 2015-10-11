<?php
use Raideer\Tweech\Event\EventSubscriber;
use Raideer\Tweech\Event\IrcMessageEvent;

class ChatMessageSubscriber extends EventSubscriber{

  public static function getSubscribedEvents(){

    return array(
      'irc.message' => array(
        'onMessageReceived', 0
      )
    );

  }

  public function onMessageReceived(IrcMessageEvent $event){
    $message = $event->getMessage();

  }

}

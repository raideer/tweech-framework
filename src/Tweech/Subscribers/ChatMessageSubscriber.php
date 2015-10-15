<?php
use Raideer\Tweech\Subscribers\EventSubscriber;
use Raideer\Tweech\Event\ChatMessageEvent;

class ChatMessageSubscriber extends EventSubscriber{

  public static function getSubscribedEvents()
  {

    return array(
      'chat.message' => array(
        'onMessageReceived', 0
      )
    );

  }

  public function onMessageReceived(ChatMessageEvent $event)
  {
    
  }

}

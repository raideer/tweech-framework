<?php
use Raideer\Tweech\Event\ChatMessageEvent;
use Raideer\Tweech\Subscribers\EventSubscriber;

class ChatMessageSubscriber extends EventSubscriber{

  protected $registry;

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
    $event->getChat()->receiveMessage($event);
  }

}

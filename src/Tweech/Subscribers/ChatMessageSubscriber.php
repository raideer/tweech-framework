<?php
namespace Raideer\Tweech\Subscribers;

use Raideer\Tweech\Event\ChatMessageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ChatMessageSubscriber implements EventSubscriberInterface{

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

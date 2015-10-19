<?php
use Raideer\Tweech\Subscribers\EventSubscriber;
use Raideer\Tweech\Event\ChatMessageEvent;
use Raideer\Tweech\Command\CommandRegistry;

class ChatMessageSubscriber extends EventSubscriber{

  protected $registry;

  public function __construct(){
    $this->registry = new CommandRegistry;
  }

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
    $message = $event->getMessage();
    if($this->registry->isCommand($message)){
      $this->registry->getCommandAndExecute($message);
    }
  }

}

<?php
use Raideer\Tweech\Event;
use Raideer\Tweech\Util\IrcEvents;

class IrcMessageSubscriber extends Event\EventSubscriber{

  public static function getSubscribedEvents(){

    return array(
      'irc.message' => array(
        'onMessageReceived', 0
      )
    );

  }

  public function onMessageReceived(Event\IrcMessageEvent $event){
    $message = $event->getMessage();
    $client = $event->getClient();

    if($name = IrcEvents::getName($message['command'])){

      $client->dispatch("irc.message.$name", new Event\IrcMessageEvent($message, $client));
    }

  }

}

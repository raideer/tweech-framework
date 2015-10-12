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

    if(!array_key_exists('command',$message)) return;

    if($name = IrcEvents::getName($message['command'])){

      $client->dispatch("irc.message.$name", new Event\IrcMessageEvent($message, $client));
    }else if(!is_numeric($message['command'])){

      $client->dispatch("irc.message." . $message['command'], new Event\IrcMessageEvent($message, $client));
    }

  }

}

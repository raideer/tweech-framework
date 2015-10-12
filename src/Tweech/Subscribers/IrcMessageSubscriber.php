<?php
use Raideer\Tweech\Event\IrcMessageEvent;
use Raideer\Tweech\Util\IrcEvents;
use Raideer\Tweech\Subscribers\EventSubscriber;

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
    $client = $event->getClient();

    if(!array_key_exists('command',$message)) return;

    if($name = IrcEvents::getName($message['command'])){

      $client->dispatch("irc.message.$name", new IrcMessageEvent($message, $client));
    }else if(!is_numeric($message['command'])){

      $client->dispatch("irc.message." . $message['command'], new IrcMessageEvent($message, $client));
    }

  }

}

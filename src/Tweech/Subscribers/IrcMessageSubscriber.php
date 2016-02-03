<?php
namespace Raideer\Tweech\Subscribers;

use Raideer\Tweech\Event\IrcMessageEvent;
use Raideer\Tweech\Event\ChatMessageEvent;
use Raideer\Tweech\Util\IrcEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IrcMessageSubscriber implements EventSubscriberInterface{

  public static function getSubscribedEvents(){

    return [
      'irc.message' => [
        'onMessageReceived', 0
      ]
    ];

  }

  public function onMessageReceived(IrcMessageEvent $event)
  {
    $response = $event->getResponse();
    $client = $event->getClient();

    /**
     * Check if the response contains an IRC command
     */
    if(!array_key_exists('command', $response)) return;

    /**
     * Check if the received command has an alias
     * See: Raideer\Tweech\Util\IrcEvents
     */

    if($name = IrcEvents::getName($response['command']))
    {
      $client->dispatch("irc.message.$name", new IrcMessageEvent($response, $client));
    }
    else if(!is_numeric($response['command']))
    {
      $name = $response['command'];

      switch($name)
      {
        /**
         * If we receive a ping then we want to pong it back
         */
        case "PING":
          $client->command("PONG", ":" . $response['server']);
          break;
        case "PRIVMSG":
          $client->dispatch('chat.message', new ChatMessageEvent($response, $client));
        default:
          $client->dispatch("irc.message.$name", new IrcMessageEvent($response, $client));
      }

    }

  }

}

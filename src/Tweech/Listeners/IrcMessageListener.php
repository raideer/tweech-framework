<?php

namespace Raideer\Tweech\Listeners;

use Raideer\Tweech\Event\ChatMessageEvent;
use Raideer\Tweech\Event\NewSubscriptionEvent;
use Raideer\Tweech\Event\IrcMessageEvent;
use Raideer\Tweech\Util\IrcEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IrcMessageListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'irc.message' => [
                'onMessageReceived', 0,
            ],
        ];
    }

    public function onMessageReceived(IrcMessageEvent $event)
    {
        $response = $event->getResponse();
        $client = $event->getClient();

        /*
        * Check if the response contains an IRC command
        */
        if (!array_key_exists('command', $response)) {
            return;
        }

        /*
        * Check if the received command has an alias
        * See: Raideer\Tweech\Util\IrcEvents
        */

        if ($name = IrcEvents::getName($response['command'])) {
            $client->dispatch("irc.message.$name", new IrcMessageEvent($response, $client));
        } elseif (!is_numeric($response['command'])) {
            $name = $response['command'];

            switch ($name) {
                /*
                * If we receive a ping then we want to pong it back
                */
                case 'PING':
                    // print_r($response);
                    $client->command('PONG', ':'.$response['params']);
                    break;
                case 'PRIVMSG':
                    if ($response['nick'] == 'twitchnotify') {
                        $client->dispatch('chat.subscription', new NewSubscriptionEvent($response, $client));
                    } else {
                        $client->dispatch('chat.message', new ChatMessageEvent($response, $client));
                    }

                default:
                    $client->dispatch("irc.message.$name", new IrcMessageEvent($response, $client));
            }
        }
    }
}

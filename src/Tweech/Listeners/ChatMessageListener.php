<?php

namespace Raideer\Tweech\Listeners;

use Raideer\Tweech\Event\ChatMessageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ChatMessageListener implements EventSubscriberInterface
{
    protected $registry;

    public static function getSubscribedEvents()
    {
        return [
          'chat.message' => [
            'onMessageReceived', 0,
          ],
        ];
    }

    public function onMessageReceived(ChatMessageEvent $event)
    {
        $event->getChat()->receiveMessage($event);
    }
}

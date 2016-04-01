<?php

namespace Raideer\Tweech\Chat;

use Raideer\Tweech\Client\Client;
use Raideer\Tweech\Command\CommandInterface;
use Raideer\Tweech\Command\CommandRegistry;
use Raideer\Tweech\Event\ChatMessageEvent;
use Raideer\Tweech\Event\CommandMessageEvent;

class Chat
{
    protected $chat;
    protected $client;
    protected $helper;
    protected $commandRegistry;
    protected $ignoredUsers;

    protected $commands = [];

    protected $joined = false;

    protected $secondsElapsed = 0;
    protected $messagesReceived = 0;
    protected $messagesReceivedTotal = 0;
    protected $messagesPerSecond = 0;

    public function __construct(Client $client, $chat)
    {
        if (!starts_with($chat, '#')) {
            $chat = "#$chat";
        }

        $this->client = $client;
        $this->chat = $chat;
        $this->helper = new ChatHelper($this);
        $this->commandRegistry = new CommandRegistry();
        // Ignoring self
        $this->ignoredUsers = new IgnoreList();
        $this->ignoredUsers->add($client->getConnection()->getNickname());
        $this->countMessages();
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->helper, $name)) {
            call_user_func_array([$this->helper, $name], $arguments);
        }
    }

    public function addCommand(CommandInterface $command)
    {
        $this->commandRegistry->register($command);
    }

    public function getCommands()
    {
        return $this->commandRegistry->getCommands();
    }

    public function getClient()
    {
        return $this->client;
    }

    public function countMessages()
    {
        $this->client->listen('tick.second', function () {
            $this->secondsElapsed++;

            if ($this->secondsElapsed >= 2) {
                $this->messagesPerSecond = $this->messagesReceived / $this->secondsElapsed;
            }
        });

        $this->client->listen('tick.minute', function () {
            $this->secondsElapsed = 0;
            $this->messagesReceived = 0;
        });
    }

    public function getMessagesPerSecond()
    {
        return round($this->messagesPerSecond, 2);
    }

    public function getTotalMessagesReceived()
    {
        return $this->messagesReceivedTotal;
    }

    public function getIgnoreList()
    {
        return $this->ignoredUsers;
    }

    public function receiveMessage(ChatMessageEvent $event)
    {
        $this->messagesReceived++;
        $this->messagesReceivedTotal++;

        if (!$this->ignoredUsers->has($event->getSenderName())) {
            $message = $event->getMessage();
            $command = $this->commandRegistry->getCommandIfExists($message);
            if ($command instanceof CommandInterface) {
                $commandEvent = new CommandMessageEvent($event->getResponse(), $event->getClient());
                $command->run($commandEvent);
            }
        }
    }

    public function privmsg($message)
    {
        $this->client->command('PRIVMSG', $this->getName().' :'.$message);
    }

    public function close()
    {
        $this->client->command('PART', $this->getName());
    }

    public function read()
    {
        $this->client->command('JOIN', $this->getName());
    }

    public function getName()
    {
        return $this->chat;
    }

    public function getHelper()
    {
        return $this->helper;
    }
}

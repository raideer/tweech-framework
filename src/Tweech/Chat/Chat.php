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

    protected $commands = [];

    protected $joined = false;

    public function __construct(Client $client, $chat)
    {
        if (!starts_with($chat, '#')) {
            $chat = "#$chat";
        }

        $this->client = $client;
        $this->chat = $chat;
        $this->helper = new ChatHelper($this);
        $this->commandRegistry = new CommandRegistry();
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

    public function receiveMessage(ChatMessageEvent $event)
    {
        $message = $event->getMessage();
        $command = $this->commandRegistry->getCommandIfExists($message);
        if ($command instanceof CommandInterface) {
            $commandEvent = new CommandMessageEvent($event->getResponse(), $event->getClient());
            $command->run($commandEvent);
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

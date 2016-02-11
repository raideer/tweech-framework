<?php

namespace Raideer\Tweech\Event;

use Raideer\Tweech\Client\Client;

class CommandMessageEvent extends ChatMessageEvent
{
    protected $message;
    protected $command;

    public function __construct($response, Client $client)
    {
        parent::__construct($response, $client);

        $this->setCommand($this->getMessage());
    }

    public function setCommand($raw)
    {
        if (preg_match("/^!([A-Za-z0-9]+)\s(.+)/", $raw, $matches)) {
            $this->message = $matches[2];
            $this->command = $matches[1];
        }
    }

    public function getCommand()
    {
        return $this->message;
    }
}

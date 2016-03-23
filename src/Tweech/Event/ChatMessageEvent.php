<?php

namespace Raideer\Tweech\Event;

use Raideer\Tweech\Client\Client;
use Raideer\Tweech\Chat\Sender;

class ChatMessageEvent extends Event
{
    protected $response;
    protected $client;
    protected $sender;

    public function __construct($response, Client $client)
    {
        $this->response = $response;
        $this->client = $client;
        $this->sender = new Sender($response['tags']);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getSenderName()
    {
        return $this->response['username'];
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getChatName()
    {
        return $this->response['chat'];
    }

    public function getChat()
    {
        return $this->client->getChat($this->response['chat']);
    }

    public function getMessage()
    {
        return $this->response['message'];
    }

    public function getClient()
    {
        return $this->client;
    }
}

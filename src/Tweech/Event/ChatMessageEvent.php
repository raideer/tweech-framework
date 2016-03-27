<?php

namespace Raideer\Tweech\Event;

use Raideer\Tweech\Client\Client;
use Raideer\Tweech\Components\Sender;
use Raideer\Tweech\Components\Emotes;

class ChatMessageEvent extends Event
{
    protected $response;
    protected $client;
    protected $sender;
    protected $emotes;
    protected $tags;

    public function __construct($response, Client $client)
    {
        $this->response = $response;
        $this->client = $client;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getSenderName()
    {
        return $this->getResponse()['nick'];
    }

    public function getEmotes()
    {
        if (!$this->emotes) {
            $this->emotes = new Emotes($this->getTags(), $this->getMessage());
        }

        return $this->emotes;
    }

    public function getTags()
    {
        if (!$this->tags) {
            $parts = explode(';', $this->getResponse()['tags']);
            $tags = [];
            foreach ($parts as $tag) {
                $values = explode(';', $tag);
                $tags[$values[0]] = $values[1];
            }

            $this->tags = $tags;
        }

        return $this->tags;
    }

    public function getSender()
    {
        if (!$this->sender) {
            $this->sender = new Sender($this->getTags());
        }

        return $this->sender;
    }

    public function getChatName()
    {
        return $this->getResponse()['chat'];
    }

    public function getChat()
    {
        return $this->client->getChat($this->getChatName());
    }

    public function getMessage()
    {
        return $this->getResponse()['message'];
    }

    public function getClient()
    {
        return $this->client;
    }
}

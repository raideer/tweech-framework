<?php

namespace Raideer\Tweech\Event;

use Raideer\Tweech\Client\Client;

class NewSubscriptionEvent extends Event
{
    protected $response;
    protected $client;
    protected $user;
    protected $resub = false;
    protected $row = 0;

    public function __construct($response, Client $client)
    {
        $this->response = $response;
        $this->client = $client;

        $subRegex = '/^(?P<user>[^ ]+) just subscribed/';
        $resubRegex = '/^(?P<user>[^ ]+) subscribed for (?P<time>[0-9]+)/';

        if (!preg_match($subRegex, $response['message'], $match)) {
            if (preg_match($resubRegex, $response['message'], $match)) {
                $this->resub = true;
                $this->user = $match['user'];
                $this->row = $match['time'];
            }
        } else {
            $this->user = $match['user'];
        }

    }

    public function isResub()
    {
        return $this->resub;
    }

    public function getMonthsInARow()
    {
        return $this->row;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getResponse()
    {
        return $this->response;
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

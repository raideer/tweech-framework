<?php
namespace Raideer\Tweech\Event;
use Raideer\Tweech\Client\Client;

class ChatMessageEvent extends Event{

  protected $response;
  protected $client;

  public function __construct($response, Client $client)
  {
    $this->response = $response;
    $this->client = $client;
  }

  public function getResponse()
  {
    return $this->response;
  }

  public function getSender()
  {
    return $this->response['username'];
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

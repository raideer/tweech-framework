<?php
namespace Raideer\Tweech\Event;

use Raideer\Tweech\Client\Client;

class IrcMessageEvent extends Event{

  protected $message;
  protected $client;

  public function __construct($message, Client $client){
    $this->message = $message;
    $this->client = $client;
  }

  public function getMessage(){
    return $this->message;
  }

  public function getClient(){
    return $this->client;
  }
}

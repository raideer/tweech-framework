<?php
namespace Raideer\Tweech\Event;
use Raideer\Tweech\Client\Client;

class IrcMessageEvent extends Event{

  protected $response;
  protected $client;

  public function __construct($response, Client $client){
    $this->response = $response;
    $this->client = $client;
  }

  public function getResponse(){
    return $this->response;
  }

  public function getClient(){
    return $this->client;
  }
}

<?php
namespace Raideer\Tweech\Client;

class ClientHelper{

  protected $client;
  protected $channel;

  public function __construct(Client $client){
    $this->client = $client;
  }

}

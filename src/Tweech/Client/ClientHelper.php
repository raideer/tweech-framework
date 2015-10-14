<?php
namespace Raideer\Tweech\Client;

class ClientHelper{

  protected $client;

  public function __construct(Client $client){
    $this->client = $client;
  }

  public function joinChannel($channel){
    if(!starts_with($channel, "#"))
    {
      $channel = "#$channel";
    }

    $this->client->command("JOIN", $channel);
  }
}

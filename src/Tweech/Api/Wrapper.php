<?php
namespace Raideer\Tweech\Api;

use GuzzleHttp\Client as Guzzle;

class Wrapper{

  protected $apiURL = "http://api.twitch.tv/kraken/";

  protected $client;

  public function __construct(){

    $this->client = new Guzzle(['base_uri' => $this->apiURL]);

  }

  public function get($target){
    $response = $this->client->request('GET', $target);

    return $response->getBody()->getContents();
  }


}

<?php
namespace Raideer\Tweech\Api;

use GuzzleHttp\Client as Guzzle;

class Wrapper{

  protected $apiURL = "https://api.twitch.tv/kraken/";

  protected $client;
  protected $resource = null;

  /**
   * API Resources
   */
  protected $channels;
  protected $chat;
  protected $follows;

  public function __construct(){

    /**
     * Creating a Guzzle Client and setting CaCert file location for SSL verification
     * @var GuzzleHttp\Client
     */
    $this->client = new Guzzle(
      [
        'base_uri' => $this->apiURL,
        'verify' => realpath( __DIR__ . "/cacert.pem")
      ]
    );

    $this->channels = new Channels($this);
    $this->chat = new Chat($this);
    $this->follows = new Follows($this);
  }

  public function resource($name = null){
    if(!$name){
      return $this->resource;
    }

    switch($name){
      case "channels":
        return $this->channels;
        break;
      case "chat":
        return $this->chat;
        break;
      case "follows":
        return $this->follows;
        break;
      default:
        return null;
        break;
    }
  }

  public function get($target){
    $response = $this->client->get($target);

    $body = json_decode($response->getBody()->getContents());

    return (json_last_error() == JSON_ERROR_NONE)?$body:$response->getBody()->getContents();
  }

}

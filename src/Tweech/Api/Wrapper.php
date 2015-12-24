<?php
namespace Raideer\Tweech\Api;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client as Guzzle;

/**
 * ONLY FOR UNAUTHORIZED REQUESTS
 */
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
  protected $games;
  protected $ingests;
  protected $search;
  protected $streams;
  protected $teams;

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

    $this->channels  = new Resources\Channels($this);
    $this->chat      = new Resources\Chat($this);
    $this->follows   = new Resources\Follows($this);
    $this->games     = new Resources\Games($this);
    $this->ingests   = new Resources\Ingests($this);
    $this->search    = new Resources\Search($this);
    $this->streams   = new Resources\Streams($this);
    $this->teams     = new Resources\Teams($this);
  }

  public function resource($name = null){

    switch($name){
      case "channels":
        $this->resource = $this->channels;
        break;

      case "chat":
        $this->resource = $this->chat;
        break;

      case "follows":
        $this->resource = $this->follows;
        break;

      case "games":
        $this->resource = $this->games;
        break;

      case "search":
        $this->resource = $this->search;
        break;

      case "streams":
        $this->resource = $this->streams;
        break;

      case "teams":
        $this->resource = $this->streams;
        break;
    }

    return $this->resource;
  }

  public function get($target, $options = []){

    try{
      $response = $this->client->get($target, $options);
    }catch(RequestException $e){
      if($e->hasResponse()) {
        $response = $e->getResponse();
      }else{
        return null;
      }
    }

    $body = json_decode($response->getBody()->getContents());

    return (json_last_error() == JSON_ERROR_NONE)?$body:$response->getBody()->getContents();
  }

}

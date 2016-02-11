<?php
namespace Raideer\Tweech\Components;

use GuzzleHttp\Client;

class Emotes{

  protected $globalApi = "http://twitchemotes.com/api_cache/v2/global.json";
  protected $subscriberApi = "http://twitchemotes.com/api_cache/v2/subscriber.json";

  protected $sql;

  public function __construct(){
    $this->sql = new \NoSQLite\NoSQLite('emotes.sqlite');
  }

  public function fetchGlobal(){

    $json = file_get_contents($this->globalApi);
    flushEcho("Emotes fetched [{$this->globalApi}]");

    $data = json_decode($json, true);

    if((json_last_error() == JSON_ERROR_NONE)){

      $store = $this->sql->getStore('global');

      $store->set("meta.generated_at", $data->meta->generated_at);
      $store->set("template.small", $data->template->small);
      $store->set("template.medium", $data->template->medium);
      $store->set("template.large", $data->template->large);
    }
  }

}

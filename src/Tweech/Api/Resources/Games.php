<?php
namespace Raideer\Tweech\Api\Resources;

class Games extends Resource{

  public function getTopGames($options = []){

    $defaults = [
      "limit" => 10,
      "offset" => 0
    ];

    return $this->wrapper->get("games/top", ['query' => $this->resolveOptions($options, $defaults)]);
  }


}

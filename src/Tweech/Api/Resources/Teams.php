<?php
namespace Raideer\Tweech\Api\Resources;

class Teams extends Resource{

  public function getTeams($options = []){

    $defaults = [
      "limit" => 25,
      "offset" => 0
    ];

    return $this->wrapper->get("teams", ['query' => $this->resolveOptions($options, $defaults)]);
  }

  public function getTeam($team){

    return $this->wrapper->get("teams/$team");
  }


}
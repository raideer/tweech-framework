<?php
namespace Raideer\Tweech\Api\Resources;

class Ingests extends Resource{

  public function getName(){
    return "ingests";
  }

  public function getIngests(){

    return $this->wrapper->get("ingests");
  }


}

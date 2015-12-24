<?php
namespace Raideer\Tweech\Api\Resources;

class Users extends Resource{

  public function getName(){
    return 'users';
  }

  public function getUser($name){

    return $this->wrapper->get("users/$name");
  }

}

<?php
namespace Raideer\Tweech\Command;

class CommandRegistry{

  protected $commands;

  public function __construct(array $commands = array()){

    $this->commands = $commands;
  }

  public function registerAll(){

    foreach($this->commands as $command){
      
    }
  }


}

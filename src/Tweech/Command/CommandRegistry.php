<?php
namespace Raideer\Tweech\Command;
use Raideer\Tweech\Command\CommandInterface;

class CommandRegistry{

  protected $commands = array();
  protected $registered = array();
  protected $registeredIdentifiers = array();

  public function register(CommandInterface $command){
    $name = $command->getCommand();
    if(array_key_exists($name, $this->commands)){
      throw new \Raideer\Tweech\Exception\CommandException("Command already exists");
    }

    $this->commands[$name] = $command;
    $registered = array();
    $registered[$name]['ids'] = $ids = $this->buildId($command);
    $this->registeredIdentifiers = array_merge($this->registeredIdentifiers, $ids);
    $this->registered[] = $registered;
  }

  protected function buildId(CommandInterface $command){
    $ids = array();
    foreach($command->getCommandIdentifier() as $id){
      $ids[] = $id . $command->getCommand();
    }

    return $ids;
  }

  public function getCommandAndExecute($command){

  }

  public function isCommand($string){
    foreach($this->registeredIdentifiers as $id){
      if(starts_with($id , $string)){
        return true;
      }
    }
    return false;
  }


}

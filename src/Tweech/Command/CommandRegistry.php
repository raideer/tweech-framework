<?php
namespace Raideer\Tweech\Command;
use Raideer\Tweech\Command\CommandInterface;

class CommandRegistry{

  protected $commands = [];
  protected $registeredIdentifiers = [];
  protected $ids = [];

  public function register(CommandInterface $command){
    $name = $command->getCommand();
    if(array_key_exists($name, $this->commands)){
      throw new CommandException("Command with name '$name' already registered");
      return;
    }

    $this->registeredIdentifiers[$name] = $ids = $command->getCommandIdentifier();
    $this->ids = array_merge($this->ids, $ids);
    $this->commands[$name] = $command;
  }

  public function getId($string){
    foreach($this->ids as $id){
      if(starts_with($string, $id)){
        return $id;
      }
    }

    return null;
  }

  public function getCommandIfExists($string){
    $id = $this->getId($string);
    if($id === null){
      return null;
    }

    foreach($this->registeredIdentifiers as $commandName => $ids){
      if(array_search($id, $ids) !== false){
        if(starts_with($string, $id . $commandName)){
          return $this->commands[$commandName];
        }
      }
    }
    return null;
  }
}

<?php
namespace Raideer\Tweech\Event;

class IrcMessageEvent extends Event{

  protected $message;

  public function __construct($message){
    $this->message = $message;
  }

  public function getMessage(){
    return $this->message;
  }
}

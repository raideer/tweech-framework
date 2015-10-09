<?php
namespace Raideer\Tweech\Event;

class ChatMessageEvent extends Event{

  protected $message;

  public function __construct($message){
    $this->message = $message;
  }

  public function getMessage(){
    return $this->message;
  }
}

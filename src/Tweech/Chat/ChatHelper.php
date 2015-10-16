<?php
namespace Raideer\Tweech\Chat;

class ChatHelper{

  protected $chat;

  public function __construct(Chat $chat){
    $this->chat = $chat;
  }

  public function send($message){
    $this->chat->privmsg($message);
  }

  public function message($message){
    $this->send($message);
  }

  public function whisper($user, $message){
    $this->send("/w $user $message");
  }

  public function w($user, $message){
    $this->whisper($user, $message);
  }

}

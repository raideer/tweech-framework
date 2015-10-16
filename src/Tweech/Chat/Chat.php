<?php
namespace Raideer\Tweech\Chat;
use Raideer\Tweech\Client\Client;

class Chat{

  protected $chat;
  protected $client;
  protected $helper;

  protected $joined = false;

  public function __construct(Client $client, $chat)
  {
    if(!starts_with($chat, "#"))
    {
      $chat = "#$chat";
    }

    $this->client = $client;
    $this->chat = $chat;
    $this->helper = new ChatHelper($this);
  }

  public function __call($name, $arguments){
    if(method_exists($this->helper, $name))
    {
      call_user_func_array(array($this->helper, $name), $arguments);
    }
  }

  public function privmsg($message){
    $this->client->command("PRIVMSG", $this->getName(). " :" . $message);
  }

  public function close(){
    $this->client->command("PART", $this->getName());
  }

  public function read(){
    $this->client->command("JOIN", $this->getName());
  }

  public function getName(){
    return $this->chat;
  }

}
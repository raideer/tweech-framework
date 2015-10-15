<?php
namespace Raideer\Tweech\Client;

class ClientHelper{

  protected $client;
  protected $channel;

  public function __construct(Client $client){
    $this->client = $client;
  }

  public function setChannel($channel)
  {
    if(!starts_with($channel, "#"))
    {
      $channel = "#$channel";
    }

    $this->channel = $channel;
  }

  public function getChannel()
  {
    return $this->channel;
  }

  protected function loadChannel($channel)
  {
    if($channel)
    {
      if($this->getChannel() != $channel)
      {
        $this->setChannel($channel);
      }
    }else{
      if(!$this->getChannel())
      {
        throw new \Exception("Channel not set! Can't send a message");
      }
    }

    return true;
  }

  /**
   * Alias for joinChannel
   */
  public function join($channel){
    $this->joinChannel($channel);
  }

  public function message($channel, $message = null){
    $this->chat($channel, $message);
  }

  public function w($user, $message, $channel = null){
    $this->whisper($user, $message, $channel);
  }

  public function joinChannel($channel){
    if(!starts_with($channel, "#"))
    {
      $channel = "#$channel";
    }
    $this->setChannel($channel);

    $this->client->command("JOIN", $channel);
  }

  public function chat($message, $channel = null){
    $this->loadChannel($channel);

    $channel = $this->getChannel();
    $this->client->command("PRIVMSG", "$channel $message");
  }

  public function whisper($user, $message, $channel = null){
    $this->chat("/w $user $message", $channel);
  }

}

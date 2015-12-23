<?php
namespace Raideer\Tweech\Api;

class Chat{

  protected $wrapper;

  public function __construct(Wrapper $wrapper){

    $this->wrapper = $wrapper;

  }

  public function getChat($channel){

    return $this->wrapper->get("chat/$channel");
  }

  public function getEmoticons(){
    return $this->wrapper->get("chat/emoticons");
  }

  public function getEmoticonImages(){
    return $this->wrapper->get("chat/emoticon_images");
  }

  public function getBadges($channel){
    return $this->wrapper->get("chat/$channel/badges");
  }

}

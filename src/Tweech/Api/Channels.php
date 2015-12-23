<?php
namespace Raideer\Tweech\Api;

class Channels{

  protected $wrapper;

  public function __construct(Wrapper $wrapper){

    $this->wrapper = $wrapper;

  }

  public function getChannel($name){

    return $this->wrapper->get("channels/$name");
  }

  public function getVideos($name){
    return $this->wrapper->get("channels/$name/videos");
  }

  public function getFollows($name){
    return $this->wrapper->get("channels/$name/follows");
  }

  public function getEditors($name){
    return $this->wrapper->get("channels/$name/editors");
  }

}
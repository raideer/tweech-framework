<?php
namespace Raideer\Tweech\Api;

class Follows{

  protected $wrapper;

  public function __construct(Wrapper $wrapper){

    $this->wrapper = $wrapper;

  }

  /**
   * Returns a list of follow objects
   *
   * https://github.com/justintv/Twitch-API/blob/master/v3_resources/follows.md#get-channelschannelfollows
   *
   * @param  string $channel Channel name
   * @param  array  $query   List of parameters
   * @return object
   */
  public function getFollowers($channel, $query = []){

    return $this->wrapper->get("channels/$channel/follows", ['query' => $query]);
  }

  /**
   * Returns a list of follows objects
   *
   * https://github.com/justintv/Twitch-API/blob/master/v3_resources/follows.md#get-usersuserfollowschannels
   *
   * @param  string $user    User
   * @param  array  $query   List of parameters
   * @return object
   */
  public function getFollows($user, $query = []){

    return $this->wrapper->get("users/$user/follows/channels");
  }

}

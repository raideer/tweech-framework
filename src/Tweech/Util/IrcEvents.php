<?php
namespace Raideer\Tweech\Util;

class IrcEvents{

  protected static $replies = array(
    "001" => "RPL_WELCOME",
    "002" => "RPL_YOURHOST",
    "003" => "RPL_CREATED",
    "004" => "RPL_MYINFO",
    "372" => "RPL_MOTD",
    "375" => "RPL_MOTDSTART",
    "376" => "RPL_ENDOFMOTD"
  );

  public static function getReplies(){
    return self::$replies;
  }

  public static function getName($code){

    if(array_key_exists($code, self::$replies)){
      return self::$replies[$code];
    }

    return null;
  }

}

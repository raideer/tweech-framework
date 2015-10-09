<?php
namespace Raideer\Tweech\Util;

class Parser{

  protected $regex;

  public function __construct(){
    $specialChars = preg_quote('\`_[]^|{}');
    $lineEnd = "[\r\n]";
    $nickname = "[A-Za-z0-9$specialChars]+";
    $hostname = "[A-Za-z0-9\.]+";
    $command = "[A-Za-z]+|[0-9]{3}";
    $space = "[\s\\x00]+?";
    $anything = "(.+)?";
    $prefix = "(((?P<nickname>$nickname)!$nickname@$nickname\.(?P<hostname>$hostname))|(?P<servername>$hostname))";
    $this->regex = "/^:(?P<prefix>$prefix)$space(?P<command>$command)$space:?(?P<params>$anything)?$lineEnd?/";
  }

  protected function removeIntegerKeys(array $array){
    foreach (array_keys($array) as $key) {
        if (is_int($key)) {
            unset($array[$key]);
        }
    }
    return $array;
  }

  public function parse($message){
    if(strpos($message, "\r\n") === false){
      // return null;
    }

    if(!preg_match($this->regex, $message, $parsed)){
      $parsed = array('invalid' => $message);
      return $parsed;
    }

    $parsed['full'] = $parsed[0];

    return $this->removeIntegerKeys($parsed);
  }
}

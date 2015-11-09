<?php
namespace Raideer\Tweech\Util;

class Parser{

  protected $messageRegex;
  protected $paramsRegex;

  public function __construct(){

    /**
     * Regex for parsing the message
     * http://tools.ietf.org/html/rfc1459#section-2.3.1
     */

    $space = " ";
    $null = "\\x00";
    $crlf = "\r\n";
    $letters = "A-Za-z";
    $numbers = "0-9";
    $special = preg_quote('[]\`_^{|}');

    $trailing = "[^$null$crlf]*";
    $username = "[$letters$numbers$special]+";
    $server = "(?:(?:[$letters$numbers\.]*)\.(?:[$letters$numbers]+)\.(?:[$letters]+))";

    $prefixFull = "((?P<username>$username)(!$username)?(@$username)?\.(?P<server>$server))";
    $prefixPart = "(?:(?P<usernamep>$username)\.(?P<serverp>$server))";
    $prefixSmall = "(?:(?P<servers>$server))";

    $command = "(?P<command>[$letters]+|[$numbers]{3})";

    $params = "(?P<params>$trailing)";

    $prefix = "(?:$prefixFull|$prefixPart|$prefixSmall)";
    $compiled = "(?P<prefix>:$prefix)?[$space]$command$space$params$crlf";

    $this->messageRegex = "/^$compiled$/U";

    $this->paramsRegex = array(
      'PRIVMSG' => "/^(?P<chat>#$username)[$space]?:(?P<message>$trailing)$/s",
      '372' => "/^(?P<username>$username)[$space]?:(?P<motd>$trailing)$/s",
      '001' => "/^(?P<username>$username)[$space]?:(?P<welcome>$trailing)$/s",
      '002' => "/^(?P<username>$username)[$space]?:(?P<host>$trailing)$/s",
      '033' => "/^(?P<username>$username)[$space]?:(?P<created>$trailing)$/s",
      // '353' => "/^(?P<chat>#$username)[$space]?:(?P<message>$trailing)$/s"
    );
  }

  protected function removeIntegerKeys(array $array){
    foreach (array_keys($array) as $key) {
        if (is_int($key)) {
            unset($array[$key]);
        }
    }
    return $array;
  }

  protected function copyAndDelete($from,$to,$parsed){
    if(array_key_exists($from,$parsed)){
      if($parsed[$to] != null){
        unset($parsed[$from]);
        return $parsed;
      }
      $parsed[$to] = $parsed[$from];
      unset($parsed[$from]);
    }

    return $parsed;
  }

  protected function parseParameters($parsed){
    $command = strtoupper($parsed['command']);
    if(!array_key_exists($command, $this->paramsRegex)) return $parsed;


    if(!preg_match($this->paramsRegex[$command], $parsed['params'], $params)) return $parsed;

    $parsed = array_merge($parsed, $params);

    return $this->removeIntegerKeys($parsed);
  }

  public function parse($message){
    if(strpos($message, "\r\n") === false){
      return null;
    }

    if(!preg_match($this->messageRegex, $message, $parsed)){
      $parsed = array('invalid' => $message);
      return $parsed;
    }

    $parsed = $this->copyAndDelete('usernamep','username', $parsed);
    $parsed = $this->copyAndDelete('serverp','server', $parsed);
    $parsed = $this->copyAndDelete('servers','server', $parsed);

    $parsed['full'] = $parsed[0];


    $parsed = $this->parseParameters($parsed);

    // return $this->removeIntegerKeys($parsed);
    return array_filter($this->removeIntegerKeys($parsed));
  }
}

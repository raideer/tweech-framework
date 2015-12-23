<?php
namespace Raideer\Tweech\Util;

class Parser{

  /**
   * Holds regex string for parsing the message
   * @var string
   */
  protected $messageRegex;
  protected $messageRegexBasic;

  /**
   * Holds regex array for parsing parameters
   * @var array
   */
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
    $prefixSmall = "(?:(?P<servers>$server|jtv))";

    $command = "(?P<command>[$letters]+|[$numbers]{3})";

    $params = "(?P<params>$trailing)";

    $prefix = "(?:$prefixFull|$prefixPart|$prefixSmall)";

    $compiled = "(?P<prefix>:$prefix)?[$space]$command$space$params$crlf";
    $basic = "(?:$command$space:(?P<server>$server))";

    /**
     * Regex for parsing the irc message
     * @var regex string
     */
    $this->messageRegex = "/^$compiled$/U";
    $this->messageRegexBasic = "/^$basic/U";
    /**
     * Command specific regex for parsing parameters
     * @var array
     */
    $this->paramsRegex = array(
      'PRIVMSG' => "/^(?P<chat>#$username)[$space]?:(?P<message>$trailing)$/s",
      '372' => "/^(?P<username>$username)[$space]?:(?P<motd>$trailing)$/s",
      '001' => "/^(?P<username>$username)[$space]?:(?P<welcome>$trailing)$/s",
      '002' => "/^(?P<username>$username)[$space]?:(?P<host>$trailing)$/s",
      '033' => "/^(?P<username>$username)[$space]?:(?P<created>$trailing)$/s",
      '353' => "/^($username)[$space]?\=[$space]?(?P<chat>#$username)[$space]?:(?P<users>$trailing)$$/s",
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

  /**
   * Removes cuts and pastes key value into a different key
   * Used here to remove duplicate keys
   * e.g. There should be only $parsed['server'] not $parsed['servers'] and $parsed['serverp']
   * @param  key $from
   * @param  key $to
   * @param  array $parsed
   * @return array
   */
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

  /**
   * Checks each message and runs the parameters through regex
   * @param  array $parsed
   * @return array
   */
  protected function parseParameters($parsed){
    $command = strtoupper($parsed['command']);
    if(!array_key_exists($command, $this->paramsRegex)) return $parsed;

    if(!preg_match($this->paramsRegex[$command], $parsed['params'], $params)) return $parsed;

    $parsed = array_merge($parsed, $params);

    if($command == 353 && array_key_exists('users', $parsed)){
      $parsed['users'] = explode(' ', $parsed['users']);
    }

    return $this->removeIntegerKeys($parsed);
  }

  /**
   * Main parsing function
   * @param  string $message Received irc message
   * @return array           Parsed
   */
  public function parse($message){
    if(strpos($message, "\r\n") === false){
      return null;
    }

    if(!preg_match($this->messageRegex, $message, $parsed)){
      if(!preg_match($this->messageRegexBasic, $message, $parsed)){

        $parsed = array('invalid' => $message);
        return $parsed;
      }
    }

    /**
     * Removing duplicates
     * usernamep -> username
     * serverp -> server
     * servers -> server
     * @var [type]
     */
    $parsed = $this->copyAndDelete('usernamep','username', $parsed);
    $parsed = $this->copyAndDelete('serverp','server', $parsed);
    $parsed = $this->copyAndDelete('servers','server', $parsed);

    /**
     * Raw message
     */
    $parsed['raw'] = $parsed[0];

    $parsed = $this->parseParameters($parsed);

    return array_filter($this->removeIntegerKeys($parsed));
  }
}

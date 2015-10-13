<?php
namespace Raideer\Tweech\Connection;
use Raideer\Tweech\Exception\SocketConnectionException;

use Raideer\Tweech\Event\EventEmitter;
use Raideer\Tweech\Event\Event;
use Raideer\Tweech\Event\IrcMessageEvent;

use Raideer\Tweech\ChatStream\StreamReader;

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Client extends EventEmitter{

  /**
   * Connection details
   * @var \Raideer\Tweech\Connection\Connection;
   */
  protected $connection;

  /**
   * Holds the socket
   * @var Socket
   */
  protected $socket;

  protected $logger;

  protected $loggedIn = false;
  protected $loggedInCallbacks;


  public function __construct(Connection $connection){
    $this->connection = $connection;
  }

  /**
   * Creates the socket
   *
   */
  public function connectToTwitch(){
    $socket = $this->createSocket($this->connection->getHostname(), $this->connection->getPort());
    $this->setSocket($socket);
  }

  protected function setSocket($socket){
    $this->socket = $socket;
  }

  public function getSocket(){
    return $this->socket;
  }

  protected function setLogIn(){
    if($this->isLogged()) return;

    fire_callbacks($this->loggedInCallbacks, $this);

    $this->loggedIn = true;
  }

  public function whenLogged(\Closure $callback){
    $this->loggedInCallbacks[] = $callback;

    if($this->isLogged()) fire_callbacks($this->loggedInCallbacks, $this);
  }

  public function isLogged(){
    return $this->loggedIn;
  }


  public function command($code, $value){
    $command = strtoupper($code) . " $value\n";

    $socket = $this->getSocket();
    if(!$socket){
      throw new SocketConnectionException("Not connected to any socket! Can't send the command");
      return;
    }

    fputs($socket, $command);
  }

  public function read(){
    $socket = $this->getSocket();
    if(!$socket){
      throw new SocketConnectionException("Not connected to any socket! Can't read anything");
      return;
    }

    return fgets($socket);
  }

  public function run(){
    $this->command("PASS", $this->connection->getPassword());
    $this->command("NICK", $this->connection->getNickname());

    $this->setLogIn();
    $this->dispatch("tweech.authenticated", new Event());

    $chatreader = new StreamReader($this);
    $chatreader->run();
  }

  protected function createSocket($server, $port){
    $socket = fsockopen($server, $port, $errid, $error);

    if(!$socket){
      throw new SocketConnectionException('Unable to connect to '.$server.':'.$port."! Error ($errid): ".$error);

      return null;
    }

    return $socket;
  }

}

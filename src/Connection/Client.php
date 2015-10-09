<?php
namespace Raideer\Tweech\Connection;
use Raideer\Tweech\Exception;

use Raideer\Tweech\Event\EventEmitter;
use Raideer\Tweech\Event\Event;
use Raideer\Tweech\Event\IrcMessageEvent;

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

  public function command($code, $value){
    $command = strtoupper($code) . " $value\n";

    $socket = $this->getSocket();
    if(!$socket){
      throw new SocketConnectionException("Not connected to any socket! Can't send the command");
      return;
    }

    $this->getLogger()->addDebug("Sending command: " . $command);
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
    $this->command("JOIN", $this->connection->getChannel());

    $this->dispatch("tweech.authenticated", new Event());

    stream_set_timeout($this->getSocket(), 1);

    while(1){
      while($data = $this->read()){
        flush();
        $this->dispatch("tweech.irc.message", new IrcMessageEvent($data));
      }

      if (!feof($this->getSocket())) {
        continue;
      }
      sleep(1);
    }
  }

  protected function createSocket($server, $port){
    $socket = fsockopen($server, $port, $errid, $error);

    if(!$socket){
      throw new SocketConnectionException('Unable to connect to '.$server.':'.$port."! Error ($errid): ".$error);

      return null;
    }

    return $socket;
  }

  public function getLogger(){
    if(!$this->logger){
      $name = get_class($this);

      if (preg_match('@\\\\([\w]+)$@', $name, $matches)) {
          $name = $matches[1];
      }

      $logger = new Logger($name);
      $logger->pushHandler(new StreamHandler(__DIR__."/../Logs/$name.log", Logger::DEBUG));

      $this->logger = $logger;
    }

    return $this->logger;
  }
  //
  // public function setLogger(LoggerInterface $logger){
  //   $this->logger = $logger;
  // }

}

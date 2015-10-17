<?php
namespace Raideer\Tweech\Client;
use Raideer\Tweech\Connection\Connection;
use Raideer\Tweech\Connection\Socket;

use Raideer\Tweech\Event\EventEmitter;
use Raideer\Tweech\Event\Event;
use Raideer\Tweech\Event\IrcMessageEvent;

use Raideer\Tweech\Chat\Chat;
use Raideer\Tweech\Chat\ChatReader;

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
  protected $chats = array();

  protected $helper;

  protected $logger;

  protected $loggedIn = false;
  protected $loggedInCallbacks = array();


  public function __construct(Connection $connection){
    $this->connection = $connection;
    $this->helper = new ClientHelper($this);
  }

  public function setConnection(Connection $connection){
    $this->connection = $connection;
  }

  public function getConnection(){
    return $this->connection;
  }

  public function __call($name, $arguments){
    if(method_exists($this->helper, $name))
    {
      call_user_func_array(array($this->helper, $name), $arguments);
    }
  }

  /**
   * Creates the socket
   */
  public function connect(){
    $socket = $this->createSocket($this->connection->getHostname(), $this->connection->getPort());
    $this->setSocket($socket);
  }

  protected function setLogIn(){
    if($this->isLogged()) return;

    fire_callbacks($this->loggedInCallbacks, $this);

    $this->loggedIn = true;
  }

  public function whenLogged($callback){
    $this->loggedInCallbacks[] = $callback;

    if($this->isLogged()) fire_callbacks($this->loggedInCallbacks, $this);
  }

  public function isLogged(){
    return $this->loggedIn;
  }

  public function joinChat($name){
    if(!starts_with($name, "#"))
    {
      $name = "#$name";
    }

    if(array_key_exists($name, $this->chats)){
      return $this->chats[$name];
    }

    $chat = new Chat($this, $name);
    $this->chats[$name] = $chat;
    return $chat;
  }

  public function getChat($name){
    if(!starts_with($name, "#"))
    {
      $name = "#$name";
    }

    if(array_key_exists($name, $this->chats)){
      return $this->chats[$name];
    }
    return null;
  }

  public function command($code, $value){
    $command = strtoupper($code) . " $value\n";
    $this->socket->send($command);
  }

  public function run(){
    $this->command("PASS", $this->connection->getPassword());
    $this->command("NICK", $this->connection->getNickname());

    $this->setLogIn();
    $this->dispatch("tweech.authenticated", new Event());

    $chatreader = new ChatReader($this);
    $chatreader->run();
  }

  protected function createSocket($server, $port){
    $socket = new Socket($server, $port);

    return $socket;
  }

  public function setSocket(Socket $socket){
    $this->socket = $socket;
  }

  public function getSocket(){
    return $this->socket;
  }

}

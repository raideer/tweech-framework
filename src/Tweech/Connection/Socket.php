<?php
namespace Raideer\Tweech\Connection;

class Socket{

  protected $server;
  protected $port;

  protected $socket;

  public function __construct($server, $port = 6667)
  {
    $this->server = $server;
    $this->port = $port;

    $this->socket = $this->create();
  }

  public function command(){
    $command = strtoupper($code) . " $value\n";
    $this->send($command);
  }

  public function getServer(){
    return $this->server;
  }

  public function getPort(){
    return $this->port;
  }

  protected function create()
  {
    $socket = fsockopen($this->server, $this->port, $errid, $error);

    if(!$socket){
      throw new SocketConnectionException('Unable to connect to '.$this->server.':'.$this->port."! Error ($errid): ".$error);

      return null;
    }
    return $socket;
  }

  public function get(){
    return $this->socket;
  }

  public function send($command){
    $socket = $this->get();
    if(!$socket){
      throw new SocketConnectionException("Not connected to any socket! Can't send the command");
      return;
    }

    fputs($socket, $command);
  }

  public function command($code, $value){
    $this->send(strtoupper($code) . " $value\n");
  }

  public function read(){
    $socket = $this->get();
    if(!$socket){
      throw new SocketConnectionException("Not connected to any socket! Can't read anything");
      return;
    }

    return fgets($socket);
  }

}

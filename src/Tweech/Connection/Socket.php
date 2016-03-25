<?php

namespace Raideer\Tweech\Connection;

class Socket
{
    protected $server;
    protected $port;

    protected $socket;
    protected $throttle;

    public function __construct($server, $port = 6667, $throttle = null)
    {
        $this->server = $server;
        $this->port = $port;

        $this->socket = $this->create();
        $this->throttle = $throttle;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getPort()
    {
        return $this->port;
    }

    protected function create()
    {
        $socket = fsockopen($this->server, $this->port, $errid, $error);
        stream_set_blocking($socket, false);

        if (!$socket) {
            throw new SocketConnectionException('Unable to connect to '.$this->server.':'.$this->port."! Error ($errid): ".$error);

            return;
        }

        return $socket;
    }

    public function get()
    {
        return $this->socket;
    }

    public function send($command)
    {
        $socket = $this->get();
        if (!$socket) {
            throw new SocketConnectionException("Not connected to any socket! Can't send the command");

            return;
        }

        if ($this->throttle) {
            // Limiting to 20 messages per 30 seconds
      // https://github.com/justintv/Twitch-API/blob/master/IRC.md#command--message-limit
      $this->throttle->throttle('message', 20, 30000);
        }
        fwrite($socket, $command);
    }

    public function command($code, $value)
    {
        $this->send(strtoupper($code)." $value\n");
    }

    public function read()
    {
        $socket = $this->get();
        if (!$socket) {
            throw new SocketConnectionException("Not connected to any socket! Can't read anything");

            return;
        }

        return fgets($socket);
    }
}

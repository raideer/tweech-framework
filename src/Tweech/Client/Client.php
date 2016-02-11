<?php

namespace Raideer\Tweech\Client;

use Raideer\Tweech\Chat\Chat;
use Raideer\Tweech\Chat\ChatReader;
use Raideer\Tweech\Connection\Connection;
use Raideer\Tweech\Connection\Socket;
use Raideer\Tweech\Event\Event;
use Raideer\Tweech\Event\EventEmitter;
use Raideer\Tweech\Subscribers\SubscriberLoader;
use Stiphle\Throttle\LeakyBucket;

class Client extends EventEmitter
{
    /**
   * Connection details.
   *
   * @var \Raideer\Tweech\Connection\Connection;
   */
  protected $connection;

  /**
   * Holds the socket.
   *
   * @var Socket
   */
  protected $socket;

  /**
   * Holds the opened chat instances.
   *
   * @var array
   */
  protected $chats = [];

  /**
   * Holds the helper class
   * (Currently empty).
   *
   * @var ClientHelper
   */
  protected $helper;

  /**
   * Used to check/set wether the client has logged on or not.
   *
   * @var bool
   */
  protected $loggedIn = false;
  /**
   * Array of callable functions that are called when the client logs in.
   *
   * @var array
   */
  protected $loggedInCallbacks = [];

    protected $subscriberLoader;

    protected $grants = [];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->helper = new ClientHelper($this);
        $this->subscriberLoader = new SubscriberLoader($this);
    }

    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

  /**
   * Magic function for implementing the functions in the helper class.
   *
   * @param  string $name      Name of the function
   * @param  array $arguments  Array of function arguments
   *
   * @return void
   */
  public function __call($name, $arguments)
  {
      if (method_exists($this->helper, $name)) {
          call_user_func_array([$this->helper, $name], $arguments);
      }
  }

  /**
   * Creates and binds the Socket.
   *
   * @return void
   */
  public function connect()
  {
      $socket = $this->createSocket(
      $this->connection->getHostname(),
      $this->connection->getPort(),
      new LeakyBucket()
    );
      $this->setSocket($socket);
  }

  /**
   * Changes the state of the Client to Logged in
   * Fires the callbacks.
   */
  protected function setLogIn()
  {
      if ($this->isLogged()) {
          return;
      }

      fire_callbacks($this->loggedInCallbacks, $this);

      $this->loggedIn = true;
  }

  /**
   * Adds the callback function to the loggedInCallbacks list
   * If client is already logged in, then the function is called.
   *
   * @param  callable $callback
   *
   * @return void
   */
  public function whenLogged($callback)
  {
      $this->loggedInCallbacks[] = $callback;

      if ($this->isLogged()) {
          fire_callbacks($this->loggedInCallbacks, $this);
      }
  }

    public function isLogged()
    {
        return $this->loggedIn;
    }

  /**
   * Creates and returns a chat instanca.
   *
   * @param  string $name Twitch chat name
   *
   * @return Chat
   */
  public function joinChat($name)
  {
      if (!starts_with($name, '#')) {
          $name = "#$name";
      }

    /*
     * If a Chat with the given name already exists
     * Then return it
     */
    if (array_key_exists($name, $this->chats)) {
        return $this->chats[$name];
    }

      \Logger::info("Joining Chat $name");

      $chat = new Chat($this, $name);
      $this->chats[$name] = $chat;

      return $chat;
  }

  /**
   * Returns Chat instance or null.
   *
   * @param  string $name Chat name
   *
   * @return Chat or null
   */
  public function getChat($name)
  {
      if (!starts_with($name, '#')) {
          $name = "#$name";
      }

      if (array_key_exists($name, $this->chats)) {
          return $this->chats[$name];
      }

      return;
  }

  /**
   * Sends a command.
   *
   * @param  string $code  Command
   * @param  string $value Command value
   *
   * @return void
   */
  public function command($code, $value)
  {
      $command = strtoupper($code)." $value\n";
      $this->socket->send($command);
  }

  /**
   * Sends a raw command.
   *
   * @param  string $command Command
   *
   * @return void
   */
  public function rawcommand($command)
  {
      if (preg_match('/(.+)[\n]$/', $command)) {
          $this->socket->send("$command");
      } else {
          $this->socket->send("$command\n");
      }
  }

    public function hasGrant($name)
    {
        return in_array($name, $this->grants);
    }

    public function grantMembership()
    {
        $this->rawcommand('CAP REQ :twitch.tv/membership');
        $this->grants[] = 'membership';
    }

    public function grantCommands()
    {
        $this->rawcommand('CAP REQ :twitch.tv/commands');
        $this->grants[] = 'commands';
    }

    public function grantTags()
    {
        $this->rawcommand('CAP REQ :twitch.tv/tags');
        $this->grants[] = 'tags';
    }

    public function grantAll()
    {
        $this->grantMembership();
        $this->grantCommands();
        $this->grantTags();
    }

  /**
   * Runs the Client
   * Authenticates, requests membership, starts reading messages.
   *
   * @return void
   */
  public function run()
  {
      $this->command('PASS', $this->connection->getPassword());
      $this->command('NICK', $this->connection->getNickname());

      $this->grantAll();

      $this->setLogIn();
      $this->dispatch('tweech.authenticated', new Event());

      $chatreader = new ChatReader($this);
      $chatreader->run();
  }

    public function registerEventSubscriber($subscriber)
    {
        $this->subscriberLoader->add($subscriber);
        $this->subscriberLoader->loadAll();
    }

    protected function createSocket($server, $port, $throttle = null)
    {
        $socket = new Socket($server, $port, $throttle);

        return $socket;
    }

    public function setSocket(Socket $socket)
    {
        $this->socket = $socket;
    }

    public function getSocket()
    {
        return $this->socket;
    }
}

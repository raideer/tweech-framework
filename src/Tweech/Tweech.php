<?php

namespace Raideer\Tweech;

use Raideer\Tweech\Client\Client;
use Raideer\Tweech\Connection\Connection;

class Tweech extends Container
{
    protected $booted = false;
    protected $bootCallbacks = [];

    /**
    * Run the application.
    */
    public function run()
    {
        $this->registerInstance();
        $this->createConnection();
        $this->createClient();

        $this->loadEventListeners();

        $this->boot();
        $this->runClient();
    }

    protected function registerInstance()
    {
        static::setInstance($this);
    }

    /**
    * Set Tweech as booted
    * Runs whenBooted callbacks.
    *
    * @return void
    */
    protected function boot()
    {
        if ($this->booted) {
            return;
        }

        fire_callbacks($this->bootCallbacks, $this);

        $this->booted = true;
    }

    /**
    * Run callbacks that are waiting for Tweech to boot.
    *
    * @param  Closure $callback Callback function
    *
    * @return void
    */
    public function whenBooted(\Closure $callback)
    {
        $this->bootCallbacks[] = $callback;

        if ($this->isBooted()) {
            fire_callbacks([$callback], $this);
        }
    }

    /**
    * [isBooted description].
    *
    * @return bool isBooted
    */
    public function isBooted()
    {
        return $this->booted;
    }

  /**
   * Loads Event Listeners.
   *
   * @return void
   */
    protected function loadEventListeners()
    {
        $coreListeners = [
            \Raideer\Tweech\Listeners\IrcMessageListener::class,
            \Raideer\Tweech\Listeners\ChatMessageListener::class,
        ];

        $this['client']->registerEventListener($coreListeners);
    }

  /**
   * Saves application paths to the container.
   *
   * @param  array  $paths List of paths
   */
  public function saveApplicationPaths(array $paths)
  {
      foreach ($paths as $key => $value) {
          $this->applyInstance("path.$key", realpath($value));
      }
  }

  /**
   * Creates the Client instance and attaches it to the container.
   *
   * @return void
   */
  protected function createClient()
  {
      $client = new Client($this['connection']);

      $this->applyInstance('client', $client);
  }

  /**
   * [createConnection description].
   *
   * @return void
   */
  protected function createConnection()
  {
      $config = $this['config'];
    /*
     * Attaches the Connection instance to the container
     */
    $this->applyInstance('connection', new Connection(
        $config['app.connection.username'],
        $config['app.connection.oauth'],
        $config['app.connection.ircServer.hostname'],
        $config['app.connection.ircServer.port']
      )
    );
  }

  /**
   * Runs the client
   * Enters an infinite loop.
   *
   * @return void
   */
  protected function runClient()
  {
      $client = $this['client'];

      \Logger::info('Starting the loop');
      $client->connect();
      $client->run();
  }
}

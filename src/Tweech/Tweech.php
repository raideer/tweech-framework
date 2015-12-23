<?php
namespace Raideer\Tweech;
use Raideer\Tweech\Connection\Connection;
use Raideer\Tweech\Client\Client;
use Raideer\Tweech\Subscribers\SubscriberLoader;


class Tweech extends Container{

  protected $booted = false;
  protected $bootCallbacks = array();
  protected $subscribers = array();

  /**
   * Run the application
   */
  public function run(){
    $this->createConnection();
    $this->createClient();

    $this->loadEventSubscribers();

    $this->boot();
    $this->runClient();
  }

  /**
   * Set Tweech as booted
   * Runs whenBooted callbacks
   * @return void
   */
  protected function boot(){
    if($this->booted) return;

    fire_callbacks($this->bootCallbacks, $this);

    $this->booted = true;
  }

  /**
   * Run callbacks that are waiting for Tweech to boot
   * @param  Closure $callback Callback function
   * @return void
   */
  public function whenBooted(\Closure $callback){

    $this->bootCallbacks[] = $callback;

    if($this->isBooted()) fire_callbacks(array($callback), $this);
  }

  /**
   * [isBooted description]
   * @return boolean isBooted
   */
  public function isBooted(){
    return $this->booted;
  }

  /**
   * Loads Event Subscribers
   * @return void
   */
  protected function loadEventSubscribers(){
    
    $coreSubscribers = array(
      __DIR__."/Subscribers/IrcMessageSubscriber",
      __DIR__."/Subscribers/ChatMessageSubscriber"
    );

    $client = $this['client'];
    $client->registerEventSubscriber($coreSubscribers);
    $client->registerEventSubscriber($subscribers);

  }

  /**
   * Saves application paths to the container
   * @param  array  $paths List of paths
   */
  public function saveApplicationPaths(array $paths){

    foreach($paths as $key => $value){
      $this->addToInstance("path.$key", realpath($value));
    }

  }

  /**
   * Creates the Client instance and attaches it to the container
   * @return void
   */
  protected function createClient(){
    $client = new Client($this['connection']);

    $this->addToInstance('client', $client);
  }

  /**
   * [createConnection description]
   * @return void
   */
  protected function createConnection(){
    $config = $this['config'];
    /**
     * Attaches the Connection instance to the container
     */
    $this->addToInstance('connection', new Connection(
                        $config['app.connection.username'],
                        $config['app.connection.oauth'],
                        $config['app.connection.ircServer.hostname'],
                        $config['app.connection.ircServer.port']
                      ));
  }

  /**
   * Runs the client
   * Enters an infinite loop
   * @return void
   */
  protected function runClient(){
    $client = $this['client'];

    $client->connect();
    $client->run();
  }

}

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

  protected function boot(){
    if($this->booted) return;

    fire_callbacks($this->bootCallbacks, $this);

    $this->booted = true;
  }

  public function whenBooted(\Closure $callback){

    $this->bootCallbacks[] = $callback;

    if($this->isBooted()) fire_callbacks(array($callback), $this);
  }

  public function isBooted(){
    return $this->booted;
  }

  protected function loadEventSubscribers(){
    $coreSubscribers = array(
      __DIR__."/Subscribers/IrcMessageSubscriber",
      __DIR__."/Subscribers/ChatMessageSubscriber"
    );

    $subscribers =  $this['config']['subscribers'];

    foreach($subscribers as $i => $subscriber){
      $subscribers[$i] = $this['path.app'] . "/subscribers/$subscriber";
    }

    $loader = new SubscriberLoader($this['client']);
    $loader->add($coreSubscribers);
    $loader->add($subscribers);
    $loader->loadAll();

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

  protected function createClient(){
    $client = new Client($this['connection']);

    $this->addToInstance('client', $client);
  }

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

  protected function runClient(){
    $client = $this['client'];

    $client->connectToTwitch();
    $client->run();
  }

}

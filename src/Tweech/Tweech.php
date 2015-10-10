<?php
namespace Raideer\Tweech;
use Raideer\Tweech\Config\Config;
use Raideer\Tweech\Config\ConfigLoader;
use Raideer\Tweech\Connection\Connection;
use Raideer\Tweech\Connection\Client;
use Raideer\Tweech\Event\SubscriberLoader;


class Tweech extends Container{

  protected $booted = false;

  protected $bootCallbacks = array();
  protected $subscribers = array();

  /**
   * Run the application
   */
  public function run(){
    /**
     * Creates a new config loader and specifies the config directory
     * @var ConfigLoader
     */
    $configLoader = new ConfigLoader($this['path.config']);

    /**
     * Attaching the Config class to the container
     */
    $this->addToInstance('config', new Config($configLoader));

    $this->loadCoreSubscribers();
    $this->loadEventSubscribers();

    $this->createConnection();
    $this->createClient();

    $this->boot();
    $this->runClient();
  }

  protected function boot(){
    if($this->booted) return;

    $this->fireCallbacks($this->bootCallbacks);

    $this->booted = true;
  }

  public function waitBooted($callback){

    $this->bootCallbacks[] = $callback;

    if($this->isBooted()) $this->fireCallbacks(array($callback));
  }

  protected function fireCallbacks(array $callbacks){
    foreach ($callbacks as $callback)
		{
			call_user_func($callback, $this);
		}
  }

  public function isBooted(){
    return $this->booted;
  }

  protected function loadEventSubscribers(){
    $subscribers =  $this['config']['subscribers'];

    $basePath = $this['path.app'] . "/subscribers";

    $this->loadSubscribers($subscribers, $basePath);
  }

  protected function loadCoreSubscribers(){
    $coreSubscribers = array(
      "IrcMessageSubscriber",
      "ChatMessageSubscriber"
    );

    $basePath = __DIR__ . "/ChatStream";

    $this->loadSubscribers($coreSubscribers, $basePath);
  }

  protected function loadSubscribers($list, $basePath){

    foreach($list as $subscriber){
      $path = "$basePath/$subscriber.php";
      if(!file_exists($path)) continue;

      // require $path;
      //
      // $class = "Raideer\\Tweech\\ChatStream\\$subscriber";
      //
      // $subscriber = $class();
      // print_r($subscriber::getSubscribedEvents());
    }
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
                        $config['connection.username'],
                        $config['connection.oauth'],
                        $config['connection.ircServer.hostname'],
                        $config['connection.ircServer.port']
                      ));
  }

  protected function runClient(){
    $client = $this['client'];

    $client->connectToTwitch();
    $client->run();
  }

}

<?php
namespace Raideer\Tweech\Chat;
use Raideer\Tweech\Util\Parser;

use Raideer\Tweech\Event\EventEmitter;
use Raideer\Tweech\Event\IrcMessageEvent;

class ChatReader{
  /**
   * Stores the client
   * @var \Raideer\Tweech\Connection\Client
   */
  protected $client;

  /**
   * Message parser
   * @var \Raideer\Tweech\Util\Parser
   */
  protected $parser;

  /**
   * Is loop running
   * @var boolean
   */
  protected $running;

  public function __construct(\Raideer\Tweech\Client\Client $client){
    $this->client = $client;
    $this->parser = new Parser();

    // $this->run();
  }

  public function run(){
    $this->running = true;

    while($this->running){

      while($message = $this->client->getSocket()->read()){
        $this->handleMessage($message);
      }

    }
  }

  public function stop(){
    $this->running = false;
  }

  protected function handleMessage($message){
    $data = $this->parser->parse($message);
    if(!$data) return;

    $this->client->dispatch("irc.message", new IrcMessageEvent($data, $this->client));
  }
}

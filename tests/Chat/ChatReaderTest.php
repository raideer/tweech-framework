<?php
use Raideer\Tweech\Chat\ChatReader;
use Mockery as m;

class ChatReaderTest extends PHPUnit_Framework_TestCase{

  protected $reader;
  protected $client;

  protected function setUp(){
    $this->client = $client = m::mock('Raideer\Tweech\Client\Client');
    $this->reader = new ChatReader($client);
  }

  protected function tearDown(){
    m::close();
  }

  public function testHandleMessage(){
    
  }
}

<?php
use Raideer\Tweech\Connection\Socket;

class SocketTest extends PHPUnit_Framework_TestCase{

  public function testConstructor(){
    $socket = new Socket("irc.test.tv", 1234);

    $this->setExpectedException("SocketConnectionException");
    $this->assertSame("irc.test.tv", $socket->getServer());
    $this->assertSame(1234, $socket->getPort());
  }

}

<?php
use Raideer\Tweech\Connection\Connection;

class ConnectionTest extends PHPUnit_Framework_TestCase{

  protected $connection;

  protected function setUp(){
    $this->connection = new Connection("test", "oauth:pas3213s");
  }

  public function testConstructor(){
    $nickname = "sodapoppin";
    $hostname = "irc.test.tv";
    $port = 1234;
    $password = "oauth:password123";

    $connection = new Connection($nickname, $password, $hostname, $port);

    $this->assertSame($nickname, $connection->getNickname());
    $this->assertSame($hostname, $connection->getHostname());
    $this->assertSame($port, $connection->getPort());
    $this->assertSame($password, $connection->getPassword());

    $connection = new Connection($nickname, $password);

    $this->assertSame("irc.twitch.tv", $connection->getHostname());
    $this->assertSame(6667, $connection->getPort());
  }

  public function testSetNickname(){
    $this->connection->setNickname("GabeN");
    $this->assertSame("GabeN", $this->connection->getNickname());
  }

  public function testSetHostname(){
    $this->connection->setNickname("irc.tweech.php");
    $this->assertSame("irc.tweech.php", $this->connection->getNickname());
  }

  public function testSetPassword(){
    $this->connection->setPassword("oauth:password123");
    $this->assertSame("oauth:password123", $this->connection->getPassword());
  }

  public function testSetPasswordError(){
    $this->setExpectedException("InvalidArgumentException");
    $this->connection->setPassword("invalidpass");
  }

  public function testSetPort(){
    $this->connection->setPort(1234);
    $this->assertSame(1234, $this->connection->getPort());
  }
}

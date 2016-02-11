<?php

use Mockery as m;
use Raideer\Tweech\Client\Client;

class ClientTest extends PHPUnit_Framework_TestCase
{
    protected $client;
    protected $connection;

    protected function setUp()
    {
        $this->connection = $connection = m::mock("Raideer\Tweech\Connection\Connection");
        $this->client = new Client($connection);
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testGetConnection()
    {
        $this->assertSame($this->connection, $this->client->getConnection());
    }

    public function testSetConnection()
    {
        $mock = m::mock("Raideer\Tweech\Connection\Connection");
        $this->client->setConnection($mock);

        $this->assertSame($mock, $this->client->getConnection());
    }

    public function testRun()
    {
        // $this->assertFalse($this->client->isLogged());
    // $this->connection->shouldReceive('getPassword')->once()->andReturn("oauth:password");
    // $this->connection->shouldReceive('getNickname')->once()->andReturn("foobar");
    //
    // $socket = m::mock("Raideer\Tweech\Connection\Socket");
    // $this->client->setSocket($socket);
    // $this->client->getSocket()->shouldReceive('send')
    //                           ->twice();
    // $this->client->run();
    }

    public function testWhenLogged()
    {
        // $callback = m::mock('stdClass')->shouldReceive('callback')->once();
    // $this->client->whenLogged(array($callback, 'callback'));
    // $this->client->setLogIn();
    }
}

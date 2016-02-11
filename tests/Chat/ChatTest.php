<?php

use Mockery as m;
use Raideer\Tweech\Chat\Chat;

class ChatTest extends PHPUnit_Framework_TestCase
{
    protected $chat;
    protected $client;

    protected function setUp()
    {
        $this->client = $client = m::mock('Raideer\Tweech\Client\Client');
        $this->chat = new Chat($client, 'foo');
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testConstructor()
    {
        $client = m::mock('Raideer\Tweech\Client\Client');
        $chat = new Chat($client, 'bar');

        $this->assertSame('#bar', $chat->getName());
    }

    public function testHelper()
    {
        $this->client->shouldReceive('command')->once()->with('PRIVMSG', '#foo :bar');
        $this->chat->send('bar');

        $this->client->shouldReceive('command')->once()->with('PRIVMSG', '#foo :bar');
        $this->chat->message('bar');

        $this->client->shouldReceive('command')->once()->with('PRIVMSG', '#foo :/w foo bar');
        $this->chat->whisper('foo', 'bar');

        $this->client->shouldReceive('command')->once()->with('PRIVMSG', '#foo :/w foo bar');
        $this->chat->w('foo', 'bar');
    }

    public function testPrivmsg()
    {
        $this->client->shouldReceive('command')->once()->with('PRIVMSG', '#foo :bar');
        $this->chat->privmsg('bar');
    }

    public function testClose()
    {
        $this->client->shouldReceive('command')->once()->with('PART', '#foo');
        $this->chat->close();
    }

    public function testRead()
    {
        $this->client->shouldReceive('command')->once()->with('JOIN', '#foo');
        $this->chat->read();
    }

    public function testGetName()
    {
        $this->assertSame('#foo', $this->chat->getName());
    }
}

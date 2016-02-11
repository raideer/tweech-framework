<?php

use Mockery as m;
use Raideer\Tweech\Chat\ChatHelper;

class ChatHelperTest extends PHPUnit_Framework_TestCase
{
    protected $helper;
    protected $chat;

    protected function setUp()
    {
        $this->chat = $chat = m::mock('Raideer\Tweech\Chat\Chat');
        $this->helper = new ChatHelper($chat);
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testSend()
    {
        $this->chat->shouldReceive('privmsg')->once()->with('foo');
        $this->helper->send('foo');
    }

    public function testMessage()
    {
        $this->chat->shouldReceive('privmsg')->once()->with('foo');
        $this->helper->message('foo');
    }

    public function testWhisper()
    {
        $this->chat->shouldReceive('privmsg')->once()->with('/w foo bar');
        $this->helper->whisper('foo', 'bar');
    }

    public function testWhisperAlias()
    {
        $this->chat->shouldReceive('privmsg')->once()->with('/w foo bar');
        $this->helper->w('foo', 'bar');
    }
}

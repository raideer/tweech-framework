<?php

namespace Raideer\Tweech\Chat;

use Raideer\Tweech\Event\IrcMessageEvent;
use Raideer\Tweech\Event\TickEvent;
use Raideer\Tweech\Parser;

class ChatReader
{
    /**
   * Stores the client.
   *
   * @var \Raideer\Tweech\Connection\Client
   */
  protected $client;

  /**
   * Message parser.
   *
   * @var \Raideer\Tweech\Util\Parser
   */
  protected $parser;

  /**
   * Is loop running.
   *
   * @var bool
   */
  protected $running;

    public function __construct(\Raideer\Tweech\Client\Client $client)
    {
        $this->client = $client;
        $this->parser = new Parser();
    }

    public function run()
    {
        $this->running = true;

        while ($this->running) {
            $this->client->dispatch('tick', new TickEvent);
        }
    }

    public function stop()
    {
        $this->running = false;
    }

    public function handleSockets()
    {
        $this->client->listen('tick', function () {
            if ($message = $this->client->getSocket()->read()) {
                $this->handleMessage($message);
            }
        });
    }

    protected function handleMessage($message)
    {
        $data = $this->parser->parse($message);
        if (!$data) {
            return;
        }

        $this->client->dispatch('irc.message', new IrcMessageEvent($data, $this->client));
    }
}

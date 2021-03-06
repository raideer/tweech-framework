<?php

namespace Raideer\Tweech\Util;

use Raideer\Tweech\Client\Client;

class Ticker
{
    protected $client;

    protected $lastSecond = 0;
    protected $lastMinute = 0;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run()
    {
        $this->client->listen('tick', function () {
            $time = microtime(true);

            if ($time - $this->lastSecond >= 1) {
                $this->lastSecond = microtime(true);
                $this->client->dispatch('tick.second', null);
            }

            if ($time - $this->lastMinute >= 60) {
                $this->lastMinute = microtime(true);
                $this->client->dispatch('tick.minute', null);
            }
        });
    }
}

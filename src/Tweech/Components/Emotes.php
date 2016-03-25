<?php

namespace Raideer\Tweech\Components;

class Emotes
{
    protected $globalApi = 'http://twitchemotes.com/api_cache/v2/global.json';
    protected $subscriberApi = 'http://twitchemotes.com/api_cache/v2/subscriber.json';

    protected $tags;
    protected $message;

    public function __construct($tags, $message)
    {
        $this->tags = $tags;
        $this->message = $message;
    }
}

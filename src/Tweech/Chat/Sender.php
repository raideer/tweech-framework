<?php

namespace Raideer\Tweech\Chat;

class Sender
{
    protected $name;
    protected $color;
    protected $subscriber = false;
    protected $turbo = false;
    protected $userId;

    public function __construct($tagsRaw)
    {
        $parts = explode(";", $tagsRaw);
        $tags = [];
        foreach ($parts as $tag) {
            $values = explode("=", $tag);
            $tags[$values[0]] = $values[1];
        }

        $this->name = $tags['display-name'];
        $this->color = $tags['color'];
        $this->subscriber = (bool) $tags['subscriber'];
        $this->turbo = (bool) $tags['turbo'];
        $this->userId = $tags['user-id'];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getId()
    {
        return $this->userId;
    }

    public function isSubscribed()
    {
        return $this->subscriber;
    }

    public function isTurbo()
    {
        return $this->turbo;
    }
}

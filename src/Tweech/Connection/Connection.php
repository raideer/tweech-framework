<?php

namespace Raideer\Tweech\Connection;

use Raideer\Tweech\Util\Validator;

class Connection implements ConnectionInterface
{
    protected $hostname;
    protected $port;
    protected $password;
    protected $nickname;

    public function __construct($nickname, $password, $hostname = 'irc.twitch.tv', $port = 6667)
    {
        $this->setNickname($nickname);
        $this->setPassword($password);
        $this->setHostname($hostname);
        $this->setPort($port);
    }

    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    public function getHostname()
    {
        return $this->hostname;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPassword($password)
    {
        if (!Validator::isValidPassword($password)) {
            throw new \InvalidArgumentException('Invalid password format! (http://www.twitchapps.com/tmi)');
        }
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    public function getNickname()
    {
        return $this->nickname;
    }
}

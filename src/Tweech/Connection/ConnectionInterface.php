<?php

namespace Raideer\Tweech\Connection;

interface ConnectionInterface
{
    public function setHostname($hostname);

    public function getHostname();

    public function setPort($port);

    public function getPort();

    public function setPassword($password);

    public function getPassword();

    public function setNickname($nickname);

    public function getNickname();
}

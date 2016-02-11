<?php

namespace Raideer\Tweech\Util;

class Validator
{
    public static function isValidPassword($password)
    {
        $match = preg_match('/oauth:([a-z0-9]+)/', strtolower($password));

        return ($match) ? true : false;
    }
}

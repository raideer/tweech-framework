<?php

namespace Raideer\Tweech\Chat;

class IgnoreList implements \ArrayAccess
{
    protected $ignored = [];

    public function add($string)
    {
        if (!$this->has($string)) {
            $this->ignored[] = $string;
        }
    }

    public function remove($string)
    {
        if ($key = array_search($string, $this->ignored) !== false) {
            unset($this->ignored[$key]);
        }
    }

    public function has($string)
    {
        return in_array($string, $this->ignored);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return array_search($string, $this->ignored);
    }

    public function offsetSet($offset, $value)
    {
        return $this->add($value);
    }

    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }
}

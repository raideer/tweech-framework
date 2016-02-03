<?php
namespace Raideer\Tweech;
use ArrayAccess;
use Encase\Container as IocContainer;

abstract class Container extends IocContainer implements ArrayAccess{

  protected $container;

  public function applyInstance($id, $instance){
    $this->object($id, $instance);
  }

  public function offsetSet($offset, $value) {
    $this->applyInstance($offset, $value);
  }

  public function offsetExists($offset) {
    return $this->contains($offset);
  }

  public function offsetUnset($offset) {
    $this->unregister($offset);
  }

  public function offsetGet($offset) {
    return $this->lookup($offset);
  }
}

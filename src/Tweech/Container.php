<?php
namespace Raideer\Tweech;
use ArrayAccess;
use Encase\Container as IocContainer;

class Container implements ArrayAccess{

  protected $container;

  public function __construct(){
    $this->container = new IocContainer();
  }

  public function __call($method, $params){
    call_user_func_array(array($this->container, $method), $params);
  }

  public function instance($id, $instance){
    $this->container->object($id, $instance);
  }

  public function offsetSet($offset, $value) {
    $this->instance($offset, $value);
      // if (is_null($offset)) {
      //     $this->container[] = $value;
      // } else {
      //     $this->container[$offset] = $value;
      // }
  }

  public function offsetExists($offset) {
    return $this->container->contains($offset);
      // return isset($this->container[$offset]);
  }

  public function offsetUnset($offset) {
    $this->container->unregister($offset);
      // unset($this->container[$offset]);
  }

  public function offsetGet($offset) {
    return $this->container->lookup($offset);
      // return isset($this->container[$offset]) ? $this->container[$offset] : null;
  }
}

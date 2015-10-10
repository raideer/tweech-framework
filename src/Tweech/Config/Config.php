<?php
namespace Raideer\Tweech\Config;
use ArrayAccess;

class Config implements ArrayAccess{

  protected $loader;

  protected $items;

  public function __construct(ConfigLoader $loader){
    $this->loader = $loader;
  }

  public function get($key, $default = null){

    list($file,$property) = $this->parseKey($key);

    $this->load($file, $property);

    return array_get($this->items, $key);
  }

  public function load($file, $property){

    $items = $this->loader->load($file,$property);

    $this->items[$file] = $items;
  }

  public function parseKey($key){
    $exploded = explode(".", $key);
    $elements = count($exploded);

    if($elements == 1){
      return array($exploded[0], null);
    }

    return $exploded;
  }

  public function offsetSet($offset, $value) {

  }

  public function offsetExists($offset) {

  }

  public function offsetUnset($offset) {

  }

  public function offsetGet($offset) {
    return $this->get($offset);
  }

}

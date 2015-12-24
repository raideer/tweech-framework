<?php
namespace Raideer\Tweech\Api\Resources;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Raideer\Tweech\Api\Wrapper;

abstract class Resource{

  protected $wrapper;

  public function __construct(Wrapper $wrapper){

    $this->wrapper = $wrapper;

  }

  public function resolveOptions($options, $defaults, $required = [], $allowedTypes = []){

    $resolver = new OptionsResolver();
    $resolver->setDefaults($defaults);

    if(!empty($required)){
      foreach($required as $item){
        $resolver->setRequired($item);
      }
    }

    if(!empty($allowedTypes)){
      foreach($allowedTypes as $name => $type){
        $resolver->setAllowedTypes($name, $type);
      }
    }

    return $resolver->resolve($options);

  }

}

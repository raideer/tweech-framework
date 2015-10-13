<?php

use Mockery as m;

class TweechTest extends PHPUnit_Framework_TestCase{

  public function testContainer(){
    $application = new Raideer\Tweech\Tweech;
    $application['foo'] = "bar";

    $this->assertEquals($application['foo'], 'bar');
  }
}

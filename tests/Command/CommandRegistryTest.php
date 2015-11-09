<?php
use Mockery as m;
use Raideer\Tweech\Command\CommandRegistry;

class CommandRegistryTest extends PHPUnit_Framework_TestCase{

  protected $registry;
  protected $command;

  protected function setUp(){
    $this->registry = new CommandRegistry;
    $command = m::mock('Raideer\Tweech\Command\CommandInterface');
    $command->shouldReceive('getCommand')->andReturn('foobar');

    $this->command = $command;
    $this->registry->register($command);
  }

  protected function tearDown(){
    m::close();
  }

  public function test_getCommandIfExists(){
    $string = "!foobar test";

    $command = $this->registry->getCommandIfExists($string);

    $this->assertSame($this->command, $command);
  }

}

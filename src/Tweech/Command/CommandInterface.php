<?php
namespace Raideer\Tweech\Command;

interface CommandInterface{

  public function getCommand();

  public function run($event);

}

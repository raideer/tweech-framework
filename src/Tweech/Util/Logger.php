<?php
namespace Raideer\Tweech\Util;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Formatter\LineFormatter;

class Logger {

  protected $monolog;

  protected $levels = array(
		'debug',
		'info',
		'notice',
		'warning',
		'error',
		'critical',
		'alert',
		'emergency',
	);

  public function __construct(MonologLogger $logger)
  {
      $this->monolog = $logger;
  }

  protected function callMonolog($method, $parameters)
	{
		if (is_array($parameters[0]))
		{
			$parameters[0] = json_encode($parameters[0]);
		}

		return call_user_func_array(array($this->monolog, $method), $parameters);
	}

  public function getLogger()
  {
    return $this->monolog;
  }

  public function logToFiles($path, $defaultLevel = 'debug')
  {
      $level = $this->parseLevel($defaultLevel);

      $this->monolog->pushHandler($handler = new StreamHandler($path, $level));

      $handler->setFormatter(new LineFormatter());
  }

  public function logToDailyFiles($path, $keepFiles = 0, $defaultLevel = 'debug')
  {
      $level = $this->parseLevel($defaultLevel);

      $this->monolog->pushHandler($handler = new RotatingFileHandler($path, $keepFiles, $level));

      $handler->setFormatter(new LineFormatter());
  }

  protected function parseLevel($level)
  {
    switch ($level){
			case 'debug':
				return MonologLogger::DEBUG;

			case 'info':
				return MonologLogger::INFO;

			case 'notice':
				return MonologLogger::NOTICE;

			case 'warning':
				return MonologLogger::WARNING;

			case 'error':
				return MonologLogger::ERROR;

			case 'critical':
				return MonologLogger::CRITICAL;

			case 'alert':
				return MonologLogger::ALERT;

			case 'emergency':
				return MonologLogger::EMERGENCY;

			default:
				throw new \InvalidArgumentException("Invalid log level.");
		}
  }

  public function __call($method, $parameters)
	{
		if (in_array($method, $this->levels))
		{

			$method = 'add'.ucfirst($method);

			return $this->callMonolog($method, $parameters);
		}

		throw new \BadMethodCallException("Method [$method] does not exist.");
	}

}

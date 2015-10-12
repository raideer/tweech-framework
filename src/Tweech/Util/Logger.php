<?php
namespace Raideer\Tweech\Util;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Formatter\LineFormatter;

class Logger {

  protected $monolog;

  public function __construct(MonologLogger $logger)
  {
      $this->monolog = $logger;
  }

  public function getLogger()
  {
    return $this->monolog;
  }

  public function logToFiles($path, $defaultLevel = 'debug')
  {
      $level = $this->parseLevel($defaultLevel);

      $this->monolog->pushHandler($handler = new StreamHandler($path, $level));

      $handler->setFormater(new LineFormatter());
  }

  public function logToDailyFiles($path, $keepFiles = 0, $defaultLevel = 'debug')
  {
      $level = $this->parseLevel($defaultLevel);

      $this->monolog->pushHandler($handler = new RotatingFileHandler($path, $keepFiles, $level));

      $handler->setFormater(new LineFormatter());
  }

  public function logToErrorFiles($path, $messageType = ErrorLogHandler::OPERATING_SYSTEM)
  {
      $level = $this->parseLevel($defaultLevel);

      $this->monolog->pushHandler($handler = new RotatingFileHandler($path, $level));

      $handler->setFormater(new LineFormatter());
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


}

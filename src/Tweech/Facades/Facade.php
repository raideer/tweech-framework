<?php
namespace Raideer\Tweech\Facades;

abstract class Facade{

  protected static $app;
  protected static $resolvedInstance;

  public static function setApplication($app)
  {
    static::$app = $app;
  }

  protected static function getFacadeName()
  {
    throw new \RuntimeException("Facade name not set.");
  }

  public static function getFacadeRoot()
	{
		return static::resolveFacadeInstance(static::getFacadeName());
	}

  public static function clearResolvedInstance($name)
	{
		unset(static::$resolvedInstance[$name]);
	}

  public static function clearResolvedInstances()
	{
		static::$resolvedInstance = array();
	}

  protected static function resolveFacadeInstance($name)
	{
		if (is_object($name)) return $name;

		if (isset(static::$resolvedInstance[$name]))
		{
			return static::$resolvedInstance[$name];
		}

		return static::$resolvedInstance[$name] = static::$app[$name];
	}

  public static function getApplication()
  {
    return static::$app;
  }

  public static function __callStatic($method, $args)
	{
		$instance = static::getFacadeRoot();

		switch (count($args))
		{
			case 0:
				return $instance->$method();

			case 1:
				return $instance->$method($args[0]);

			case 2:
				return $instance->$method($args[0], $args[1]);

			case 3:
				return $instance->$method($args[0], $args[1], $args[2]);

			case 4:
				return $instance->$method($args[0], $args[1], $args[2], $args[3]);

			default:
				return call_user_func_array(array($instance, $method), $args);
		}
	}
}

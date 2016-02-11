<?php

use Raideer\Tweech\Container;

if (! function_exists('tweech_app')) {

    function tweech_app(){
        return Container::getInstance();
    }
}

if (! function_exists('tweech_get_path')) {

    function tweech_get_path($name){
        return tweech_app()->offsetGet("path.$name");
    }

}


if (!function_exists('array_get'))
{
	/**
	 *
	 * Get an item from an array using "dot" notation.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	function array_get($array, $key, $default = null)
	{
		if (is_null($key)) return $array;
		if (isset($array[$key])) return $array[$key];
		foreach (explode('.', $key) as $segment)
		{
			if ( ! is_array($array) || ! array_key_exists($segment, $array))
			{
				return value($default);
			}
			$array = $array[$segment];
		}
		return $array;
	}

}

if ( ! function_exists('array_set'))
{
	/**
	 * Set an array item to a given value using "dot" notation.
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return array
	 */
	function array_set(&$array, $key, $value)
	{
		if (is_null($key)) return $array = $value;
		$keys = explode('.', $key);
		while (count($keys) > 1)
		{
			$key = array_shift($keys);
			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if ( ! isset($array[$key]) || ! is_array($array[$key]))
			{
				$array[$key] = array();
			}
			$array =& $array[$key];
		}
		$array[array_shift($keys)] = $value;
		return $array;
	}
}

if ( ! function_exists('keyFlattener'))
{
	/**
	 * Flattens a multidimensional array to a singledimensional array.
	 * Keys are concatenated with a dot.
	 *
	 * @param  array $array
	 * @param  string $prefix Key prefix
	 * @return array
	 */
	function keyFlattener($array, $prefix = "") {
	    $result = [];

	    foreach($array as $key => $value) {
	        if(is_array($value)) {
	            $result = $result + keyFlattener($value, $prefix . $key . '.');
	        }else{
							$result[$prefix . $key] = $value;
	        }
	    }
	    return $result;
	}
}

if ( ! function_exists('flushEcho'))
{
	function flushEcho($string, $newLine = true) {
	    echo $string;
			if($newLine) echo PHP_EOL;
			flush();
	}
}

if ( ! function_exists('value'))
{
	/**
	 * Return the default value of the given value.
	 *
	 * @param  mixed  $value
	 * @return mixed
	 */
	function value($value)
	{
		return $value instanceof Closure ? $value() : $value;
	}
}

if ( ! function_exists('fire_callbacks'))
{
	/**
	 * Return the default value of the given value.
	 *
	 * @param  mixed  $value
	 * @return mixed
	 */
	function fire_callbacks(array $list, $instance)
	{
		foreach ($list as $callback)
		{
			call_user_func($callback, $instance);
		}
	}
}

if ( ! function_exists('remove_command_str'))
{
	function remove_command_str($message, $command)
	{
		return substr($message, strlen("$command "));
	}
}

if ( ! function_exists('with'))
{
	/**
	 * Return the given object. Useful for chaining.
	 *
	 * @param  mixed  $object
	 * @return mixed
	 */
	function with($object)
	{
		return $object;
	}
}
if ( ! function_exists('starts_with'))
{
	function starts_with($haystack, $needle) {
	    // search backwards starting from haystack length characters from the end
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
}

if ( ! function_exists('ends_with'))
{
	function ends_with($haystack, $needle) {
	    // search forward starting from end minus needle length characters
	    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}
}

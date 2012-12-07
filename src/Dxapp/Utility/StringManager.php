<?php

/**
 * String Manager
 *
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @package Dx
 * @subpackage String
 * @link http://labs.madayaw.com
 */

namespace Dxapp\Utility;

class StringManager
{

	/**
	 * Convert an associative array to a string
	 * @param array $string 
	 * return string
	 */
	public static function arrayToString(array $array, $glue = '')
	{
		if (is_array($array))
		{
			return implode($glue, $array);
		}
		return $array;
	}

	/**
	 * Convert a string into camelcased Sttring
	 * my_field = myField
	 * @param string $string
	 * @return string
	 */
	public static function camelCase($str, $capFirstLetter = FALSE)
	{
		$str = strtolower($str);
		if ($capFirstLetter)
		{
			$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str);
	}

	/**
	 * Convert a string into underscore
	 * MyField = my_field
	 * @param string $string
	 * @return string
	 */
	public static function underscore($str)
	{
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}
	
	/**
	 * Properly camelcase a string
	 * usually used on setters/getter
	 * @param string $str
	 * @return string
	 */
	public static function ucc($str, $capFirstLetter = FALSE)
	{
		return self::camelCase(self::underscore($str), $capFirstLetter);
	}

}

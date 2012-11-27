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

}

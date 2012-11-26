<?php

/**
 * Array Manager
 *
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @package Dx
 * @subpackage ArrayManager
 * @link http://labs.madayaw.com
 */

namespace Dxapp\Utility;

class ArrayManager
{
/**
     * Merges any number of arrays / parameters recursively, replacing 
     * entries with string keys with values from latter arrays. 
     * If the entry or the next value to be assigned is an array, then it 
     * automagically treats both arguments as an array.
     * Numeric entries are appended, not replaced, but only if they are 
     * unique
     *
     * calling: result = array_merge_recursive_distinct(a1, a2, ... aN)
     * */
    public static function merge()
    {
        $arrays = func_get_args();
        $base = array_shift($arrays);
        if (!is_array($base))
            $base = empty($base) ? array() : array($base);
        foreach ($arrays as $append)
        {
            if (!is_array($append))
                $append = array($append);
            foreach ($append as $key => $value)
            {
                if (!array_key_exists($key, $base) and !is_numeric($key))
                {
                    $base[$key] = $append[$key];
                    continue;
                }
                if (is_array($value) or (isset($base[$key]) && is_array($base[$key])))
                {
                    $base[$key] = self::merge($base[$key], $append[$key]);
                }
                else if (is_numeric($key))
                {
                    if (!in_array($value, $base))
                        $base[] = $value;
                } else
                {
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }
	
	/**
	 * Insert an associative array to specified position after or before the given $key
	 * if $key is not found, $data will be inserted before or after the $array
	 * 
	 * @param array $array Associative array
	 * @param string $key $data will be inserted after this key
	 * @param array $data Associative array to be inserted
	 * @param string $pos If to insert after or before the given $key
	 * 
	 * @return array The new arranged associative array
	 */
	public static function array_insert(array $array, $key, $data, $pos = 'after')
	{
		$k = key($array);

		if (array_key_exists($key, $array) === TRUE)
		{
			if($pos == 'after')
			{
				$key = array_search($key, array_keys($array)) + 1;
			} else {
				$key = array_search($key, array_keys($array));
			}
			$array = array_slice($array, NULL, $key, TRUE) + $data + array_slice($array, $key, NULL, TRUE);

			while ($k != key($array))
			{
				next($array);
			}
		} else {
			if($pos == 'after')
			{
				$array = array_merge($array, $data);
			} else {
				$array = array_merge($data, $array);
			}
		}
		return $array;
	}

	
}

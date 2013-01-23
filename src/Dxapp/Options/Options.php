<?php

namespace Dxapp\Options;

use Zend\Stdlib\AbstractOptions;

class Options extends AbstractOptions
{

	/**
	 * Set/Get attribute wrapper
	 *
	 * @param   string $method
	 * @param   array $args
	 * @return  mixed
	 */
	public function __call($method, $args)
	{
		switch (substr($method, 0, 3))
		{
			case 'get' :
				$property = \Dxapp\Utility\StringManager::ucc(substr($method, 3));
				if (method_exists($this, 'get' . ucfirst($property)))
				{
					$method = 'get' . ucfirst($property);
					return $this->{$method}();
				}
				if (isset($this->{$property}))
				{
					return $this->{$property};
				}
				break;
			case 'set':
				$property = \Dxapp\Utility\StringManager::ucc(substr($method, 3));
				if (method_exists($this, 'set' . ucfirst($property)))
				{
					$method = 'set' . ucfirst($property);
					return $this->{$method}(isset($args[0]) ? $args[0] : NULL);
				}
				if (isset($this->{$property}))
				{
					return $this->{$property} = isset($args[0]) ? $args[0] : NULL;
				}
				break;
			default;
		}
		throw new \Dxapp\Exception\BadMethodCallException('Method("' . $method . '") and Property ("' . $property . '") doesn\'t exist.');
	}

	/**
	 * Setter Magic
	 * @param type $key
	 * @param type $value
	 * @return type
	 */
	public function __set($key, $value)
	{
		$property =  \Dxapp\Utility\StringManager::ucc($key);
		if (method_exists($this, 'set' . ucfirst($property)))
		{
			$method = 'set' . ucfirst($property);
			return $this->{$method}($value);
		}
		if (isset($this->{$property}))
		{
			return $this->{$property} = $value;
		}
	}
	
	/**
	 * Getter Magic
	 * @param type $key
	 * @return type
	 */
	public function __get($key)
	{
		$property = \Dxapp\Utility\StringManager::ucc($key);
		if (method_exists($this, 'get' . ucfirst($property)))
		{
			$method = 'get' . ucfirst($property);
			return $this->{$method}();
		}
		if (isset($this->{$property}))
		{
			return $this->{$property};
		}
	}
}
<?php

/**
 * Config
 * 
 * Get the config
 *  
 */

namespace Dxapp\View\Helper;

use Dx\Config as DxConfig;
use Dx\View\AbstractHelper;

class User extends AbstractHelper
{

	public function __invoke()
	{
		return $this;
	}
	
	/**
	 *  
	 */
	public function isAdmin()
	{
		return FALSE;
	}

}

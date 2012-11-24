<?php

/**
 * Dx View Helper
 * 
 */

namespace Dxapp\View\Helper;

use Dxapp\View\AbstractHelper;

class Dx extends AbstractHelper
{
	public function __invoke()
	{
		return $this;
	}
}

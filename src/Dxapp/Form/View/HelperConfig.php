<?php

namespace Dxapp\Form\View;

use DluTwBootstrap\Form\View\HelperConfig as DluHelperConfig;
use DluTwBootstrap\GenUtil;
use DluTwBootstrap\Form\FormUtil;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * HelperConfig
 * Service manager configuration for form view helpers
 * @package DluTwBootstrap
 * @copyright David Lukas (c) - http://www.zfdaily.com
 * @license http://www.zfdaily.com/code/license New BSD License
 * @link http://www.zfdaily.com
 * @link https://bitbucket.org/dlu/dlutwbootstrap
 */
class HelperConfig extends DluHelperConfig
{
	/**
	 * Returns an array of view helper factories
	 * @return array
	 */
	protected function getFactories()
	{
		$parentFactories = parent::getFactories();
		$genUtil = $this->genUtil;
		$formUtil = $this->formUtil;
		$parentFactories['formrowtwb'] = function() use ($genUtil, $formUtil)
				{
					$instance = new \Dxapp\Form\View\Helper\FormRowTwb($genUtil, $formUtil);
					return $instance;
				};
		return $parentFactories;
	}

}

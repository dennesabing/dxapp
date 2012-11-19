<?php

namespace Dxapp\View;

use Zend\View\Helper\AbstractHelper as ZendAbstractHelper;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractHelper extends ZendAbstractHelper implements ServiceManagerAwareInterface
{

	/**
	 * Retrieve service manager instance
	 *
	 * @return ServiceManager
	 */
	public function getServiceManager()
	{
		return $this->serviceManager->getServiceLocator();
	}

	/**
	 * Set service manager instance
	 *
	 * @param ServiceManager $locator
	 * @return void
	 */
	public function setServiceManager(ServiceManager $serviceManager)
	{
		$this->serviceManager = $serviceManager;
	}

	/**
	 * Get Module Options
	 * @see Module.php
	 *
	 * @return Zend\Stdlib\AbstractOptions
	 */
	public function getModuleOptions($modulePrefix)
	{
		if ($modulePrefix !== NULL)
		{
			if ($this->getServiceManager()->has($modulePrefix . '_module_options'))
			{
				return $this->getServiceManager()->get($modulePrefix . '_module_options');
			}
		}
		return $this->getServiceManager()->get('dxapp_module_options');
	}

	/**
	 * Proxy to $this->getModuleOptions();
	 * @param type $modulePrefix
	 * @return type 
	 */
	public function getOptions($modulePrefix = NULL)
	{
		return $this->getModuleOptions($modulePrefix);
	}

}
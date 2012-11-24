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
	public function getModuleOptions($modulePrefix = NULL)
	{
		return $this->getDxService()->getModuleOptions($modulePrefix);
	}
	
	/**
	 * Return the DxService
	 * @return type
	 */
	public function getDxService()
	{
		return $this->getServiceManager()->get('dxService');
	}

}
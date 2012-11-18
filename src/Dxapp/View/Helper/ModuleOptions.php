<?php

/**
 * Get the Module Options
 * 
 * Get the config
 *  
 */

namespace Dxapp\View\Helper;

use Dx\View\AbstractHelper;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class ModuleOptions extends AbstractHelper implements ServiceManagerAwareInterface
{
	/**
	 * The Service Manager Instance
	 * @var type 
	 */
	protected $serviceManager = NULL;

	/**
	 * The Module Prefix
	 * @var string
	 */
	protected $modulePrefix = NULL;
	
	public function __invoke()
	{
		return $this;
	}
	
	/**
	 * Get Module Options
	 * @see Module.php
	 *
	 * @return Zend\Stdlib\AbstractOptions
	 */
	public function getOptions($modulePrefix)
	{
		return $this->getServiceManager()->get($modulePrefix . '_module_options');
	}
	
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
}

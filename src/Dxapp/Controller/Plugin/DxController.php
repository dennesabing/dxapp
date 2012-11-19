<?php

namespace Dxapp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class DxController extends AbstractPlugin implements ServiceManagerAwareInterface
{

	/**
	 * @var ServiceManager
	 */
	protected $serviceManager;

	/**
	 * The Module Options
	 * @var Zend\Stdlib\AbstractOptions
	 */
	protected $moduleOptions = NULL;

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
	 * Get Session Manager
	 * @param type $modulePrefix
	 * @return \Zend\Session\Container 
	 */
	public function getSession($modulePrefix = NULL)
	{
		if ($modulePrefix !== NULL)
		{
			if ($this->getServiceManager()->get($modulePrefix . '_session') instanceof \Zend\Session\Container)
			{
				return $this->getServiceManager()->get($modulePrefix . '_session');
			}
			return new \Zend\Session\Container($modulePrefix . '_session');
		}
		return new \Zend\Session\Container('dxApp_session');
	}

	/**
	 * Get Module Options
	 * @see Module.php
	 *
	 * @return Zend\Stdlib\AbstractOptions
	 */
	public function getModuleOptions($modulePrefix = NULL)
	{
		$this->setModuleOptions($this->getServiceManager()->get($modulePrefix . '_module_options'));
		return $this->options;
	}

	/**
	 * Set Module Options
	 *
	 * @param AbstractOptions $options
	 * @return $this
	 */
	public function setModuleOptions($options)
	{
		$this->options = $options;
		return $this;
	}
	
	/**
	 * set Status code NotFound or 404 
	 */
	public function notFound()
	{
		$this->getController()->getResponse()->setStatusCode(404);
	}
}

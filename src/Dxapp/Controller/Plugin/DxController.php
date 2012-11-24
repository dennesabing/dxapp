<?php

namespace Dxapp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;
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
	 * Return the Entity Manager
	 * @return DoctrineEntityManager
	 */
	public function getEntityManager()
	{
		if (NULL === $this->em)
		{
			$this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
		}
		return $this->em;
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
	
	/**
	 * Return the DxService
	 * @return type
	 */
	public function getDxService()
	{
		return $this->getServiceManager()->get('dxService');
	}

	/**
	 * Get Session Manager
	 * @param type $modulePrefix
	 * @return \Zend\Session\Container 
	 */
	public function getSession($modulePrefix = NULL)
	{
		return $this->getDxService()->getSession($modulePrefix);
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
	 * Proxy
	 * Return the ZfcUser Authentication plugin
	 * @return object
	 */
	public function getAuth()
	{
		return $this->getDxService()->getAuth();
	}
	
	/**
	 * set Status code NotFound or 404 
	 */
	public function notFound()
	{
		$this->getController()->getResponse()->setStatusCode(404);
	}
	
	/**
	 * Add an error message
	 * @param type $msg The Message
	 * @param type $type The Type of Message
	 */
	public function addMessage($msg, $type = 'error', $session = FALSE)
	{
		$viewModel = new ViewModel();
		$viewModel->dxAlert($msg, $type, $session);
	}
}

<?php

namespace Dxapp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Parameters;

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
	 * Get the value of the requested param
	 * @param mixed $param The key to look
	 * @param mixed $default The default value to return if key not found
	 * 
	 * @return mixed
	 */
	public function getParam($param, $default = FALSE)
	{
		return $this->getController()->getEvent()->getRouteMatch()->getParam($param, $default);	
	}
	
	/**
	 * Get the RouteMatch
	 * @return type
	 */
	public function getRouteMatch()
	{
		return $this->getController()->getEvent()->getRouteMatch();
	}
	
	/**
	 * Return all params
	 * @return array
	 */
	public function getParams()
	{
		return $this->getController()->getEvent()->getRouteMatch()->getParams();	
	}
	
	/**
	 * Return the Entity Manager
	 * @return DoctrineEntityManager
	 */
	public function getEntityManager()
	{
		return $this->getDxService()->getEntityManager();
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
	 * REturn a service
	 */
	public function getService($service)
	{
		return $this->getDxService()->get($service);
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
	 * Return the DxService
	 * @return type
	 */
	public function getUserService()
	{
		return $this->getDxService()->get('dxuser_service_user');
	}
	
	/**
	 * Check if a user is loggedIn
	 * @param object $user DxUser\Entity\User
	 * @return boolean
	 */
	public function isLogin($user = NULL)
	{
		return $this->getUserService()->isLogin($user);
	}
	
	/**
	 * Check if the given user is an admin
	 * @param object $user DxUser\Entity\User
	 * @return boolean
	 */
	public function isAdmin($user = NULL)
	{
		return $this->getUserService()->isAdmin($user);
	}
	
	/**
	 * set Status code NotFound or 404 
	 */
	public function pageNotFound()
	{
		$this->getController()->getResponse()->setStatusCode(404);
	}

	/**
	 * Goto login page and redirect afterwards
	 * @param string $uri the Redirect Page
	 */
	public function gotoLogin()
	{
		$uri = $this->getController()->getRequest()->getUri();
		$redirect = '?redirect=' . $uri;
		return $this->getController()->redirect()->toUrl('/login' . $redirect);
	}
	
	/**
	 * Access error
	 */
	public function gotoAccessError()
	{
		$this->pageNotFound();
	}
	
	/**
	 * Go to error page showing that a service is not available
	 * or module is not installed
	 */
	public function gotoServiceNotFound()
	{
		
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

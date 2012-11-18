<?php

namespace DxApp\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
	/**
	 * Enable email sending
	 * @var boolean
	 */
	protected $emailSending = TRUE;
	/**
	 * The Main breadcrumb
	 * @var array
	 */
	protected $breadcrumbMain = array(
		'home' => array(
			'anchor' => 'Home',
			'url' => '/'
		)
	);
	
	/**
	 * The Main route to redirect user if a logged-in user goes to a non-logged-in pages.
	 * @var string
	 */
	protected $routeMain = 'dx-user-account';
	
	
	/**
	 * Set the Main route 
	 * @param string $route
	 * @return \DxUser\Options\ModuleOptions 
	 */
	protected function setRouteMain($route)
	{
		$this->routeMain = $route;
		return $this;
	}
	
	/**
	 * Get the main route
	 * @return string
	 */
	public function getRouteMain()
	{
		return $this->routeMain;
	}
	
	/**
	 * Enable/Disable email sending
	 * @param boolean $flag
	 * @return \DxUser\Options\ModuleOptions 
	 */
	public function setEmailSending($flag)
	{
		$this->emailSending = $flag;
		return $this;
	}
	
	/**
	 * Return email sending status
	 * @return boolean
	 */
	public function getEmailSending()
	{
		return $this->emailSending;
	}
	
	/**
	 * Set the main breadcrumb
	 * @param array $crumb
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setBreadcrumbMain($crumb)
	{
		$this->breadcrumbMain = $crumb;
		return $this;
	}
	
	/**
	 * Get the main Breadcrumb
	 * @return type 
	 */
	public function getBreadcrumbMain()
	{
		return $this->breadcrumbMain;
	}
}

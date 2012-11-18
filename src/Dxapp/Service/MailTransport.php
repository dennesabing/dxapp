<?php

namespace Dxapp\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Mail\Transport\Sendmail as SendmailTransport;

class MailTransport implements ServiceManagerAwareInterface
{

	/**
	 * Module Options
	 * @var type 
	 */
	protected $options = NULL;

	public function __construct($serviceManager)
	{
		$this->setServiceManager($serviceManager);
		$this->setOptions($serviceManager->get('dxapp_module_options'));
	}

	/**
	 * Send message
	 * @TODO Save message to MAilQueue
	 * @param object $message 
	 */
	public function send($message)
	{
		if ($this->getOptions()->getEmailSending())
		{
			$transport = new SendmailTransport();
			$transport->send($message);
		}
		else
		{
			
		}
	}

	/**
	 * Get the Module OPtions from SM
	 *
	 * @return UserServiceOptionsInterface
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * set service options
	 *
	 * @param UserServiceOptionsInterface $options
	 */
	public function setOptions($options)
	{
		$this->options = $options;
	}

	/**
	 * Retrieve service manager instance
	 *
	 * @return ServiceManager
	 */
	public function getServiceManager()
	{
		return $this->serviceManager;
	}

	/**
	 * Set service manager instance
	 *
	 * @param ServiceManager $locator
	 * @return User
	 */
	public function setServiceManager(ServiceManager $serviceManager)
	{
		$this->serviceManager = $serviceManager;
		return $this;
	}

}
<?php

namespace Dxapp\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Dxapp\EventManager\EventProvider;

class Dx extends EventProvider implements ServiceManagerAwareInterface
{
	
	/**
	 * The datetime formats
	 * @var string
	 */
	const DATETIMEFORMAT_SHORT = 'm/d/Y h:m a';
	const DATETIMEFORMAT_MEDIUM = 'm/d/Y h:m a';
	const DATETIMEFORMAT_LONGER = 'm/d/Y h:m a';
	const DATETIMEFORMAT_SQL = 'Y-m-d H:m:s';
	
	/**
	 * The View Renderer
	 * @var object
	 */
	protected $renderer = NULL;

	/**
	 * The Doctrine Entity Manager
	 * @var type 
	 */
	protected $em = NULL;

	/**
	 * the Module Options
	 * @var type 
	 */
	protected $options = NULL;

	/**
	 * The Auth service
	 * @var type 
	 */
	protected $authService = NULL;
	
	/**
	 * Set the ViewRenderer Object
	 * @param type $viewRenderer
	 * @return \DxUser\Service\User 
	 */
	public function setViewRenderer($viewRenderer)
	{
		$this->renderer = $viewRenderer;
		return $this;
	}

	/**
	 * Return the View Renderer
	 * @return string
	 */
	public function getViewRenderer()
	{
		return $this->renderer;
	}
	
	/**
	 * Set Doctrine Entity Manager
	 * @return User
	 */
	public function setEntityManager($em)
	{
		$this->em = $em;
		return $this;
	}

	/**
	 * Return the Entity Manager
	 * @return type 
	 */
	public function getEntityManager()
	{
		return $this->em;
	}
	
	/**
	 * Get the requested service
	 * @param type $serviceIndex
	 * @return boolean
	 */
	public function get($serviceIndex)
	{
		if ($serviceIndex !== NULL)
		{
			if ($this->getServiceManager()->has($serviceIndex))
			{
				return $this->getServiceManager()->get($serviceIndex);
			}
		}
		return FALSE;
	}
	
	/**
	 * Return the User Service
	 * @return object
	 */
	public function getUserService()
	{
		return $this->get('dxuser_service_user');
	}
	
	/**
	 * Return the AuthService
	 * @return type
	 */
	public function getAuth()
	{
		return $this->get('zfcuser_auth_service');
	}

	/**
	 * Return the Session Container
	 * @return \Zend\Session\Container
	 */
	public function getSession($modulePrefix = NULL)
	{
		if ($modulePrefix !== NULL)
		{
			if ($this->get($modulePrefix . '_session') instanceof \Zend\Session\Container)
			{
				return $this->get($modulePrefix . '_session');
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
		if ($modulePrefix !== NULL)
		{
			if ($this->getServiceManager()->has($modulePrefix))
			{
				return $this->get($modulePrefix);
			}
			if ($this->getServiceManager()->has($modulePrefix . '_module_options'))
			{
				return $this->get($modulePrefix . '_module_options');
			}
			return FALSE;
		}
		return $this->get('dxapp_module_options');
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
	 * Check for Cache Existence in a File
	 * @param type $key
	 * @param type $options
	 * @return boolean 
	 */
	public function hasCacheFromFile($key, $options = array())
	{
		$cache = $this->get('dxFilecache')->getFileCache()->setOptions($options);
		if ($cache->hasItem($key))
		{
			return $cache->getItem($key);
		}
		return FALSE;
	}

	/**
	 * Check for cache existence in Memory
	 * @param type $key
	 * @param type $options
	 * @return boolean 
	 */
	public function hasCacheFromMemory($key, $options = array())
	{
		$cache = $this->get('dxFilecache')->getMemoryCache()->setOptions($options);
		if ($cache->hasItem($key))
		{
			return $cache->getItem($key);
		}
		return FALSE;
	}

	/**
	 * Cache $data with $key to Cache\Filesystem
	 * @param type $key
	 * @param type $data
	 * @param type $options
	 * @return type 
	 */
	public function cacheToFile($key, $data, $options = array())
	{
		$cache = $this->get('dxFilecache')->getFileCache()->setOptions($options);
		return $cache->setItem($key, $data);
	}

	/**
	 * Cache $data with $key to Cache\Memcache
	 * @param type $key
	 * @param type $data
	 * @param type $options
	 * @return type 
	 */
	public function cacheToMemory($key, $data, $options = array())
	{
		$cache = $this->get('dxFilecache')->getMemoryCache()->setOptions($options);
		return $cache->setItem($key, $data);
	}

	/**
	 * Cache a File data
	 * @param type $key
	 * @param type $data
	 * @param type $options
	 * @return mixed
	 */
	public function cacheAFile($key, $data, $options = array())
	{
		$cache = $this->get('dxFilecache')->getFileCache()->setOptions($options);
		return $cache->setItem($key, $data);
	}

	/**
	 * Cache an Ojbect Data
	 * @param type $key
	 * @param type $data
	 * @param type $options
	 * @return object
	 */
	public function cacheAnObject($key, $data, $options = array())
	{
		$cache = $this->get('dxFilecache')->getFileCache()->setOptions($options);
		return $cache->setItem($key, $data);
	}

	/**
	 * CAche an Array Data
	 * @param type $key
	 * @param type $data
	 * @param type $options
	 * @return array 
	 */
	public function cacheAnArray($key, $data, $options = array())
	{
		$cache = $this->get('dxFilecache')->getFileCache()->setOptions($options);
		return $cache->setItem($key, $data);
	}

	/**
	 * Send message
	 * @TODO Save message to MAilQueue
	 * @param object $message 
	 */
	public function send($message)
	{
		if ($this->getModuleOptions()->getEmailSending())
		{
			$transport = new SendmailTransport();
			$transport->send($message);
		}
		else
		{
			
		}
	}
	
	/**
	 * 
	 * DATES
	 * 
	 */
	
	/**
	 * All dates in DB is saved in UTC Timezone
	 * Parse date to be SQL-oriented format
	 * @param string $date
	 * @param string $time
	 * @param string|array $options If string was given, then it is the timezone
	 * @return object \DateTime
	 */
	public function dateTimeToSql($date, $time, $options = array())
	{
		$tz = $this->get('dxoptions')->getDbTimezone();
		$date = \DateTime::createFromFormat(self::DATETIMEFORMAT_SHORT, $date . ' ' . $time, new \DateTimeZone($tz));
		return $date;
	}
	
	/**
	 * Parse date from SQL
	 * @param string $dateTime The Date from SQL
	 * @param string $tz The Timezone that this date was saved
	 * @return object \DateTime
	 */
	public function dateTimeFromSql($dateTime, $tz = FALSE)
	{
		if($dateTime instanceof \DateTime)
		{
			$dateTime = $dateTime->format(self::DATETIMEFORMAT_SQL);
		}
		if(!$tz)
		{
			$tz = 'UTC';
		}
		return \DateTime::createFromFormat(self::DATETIMEFORMAT_SQL, $dateTime, new \DateTimeZone($tz));
	}
	
	/**
	 * REturn the current Date time from UTC
	 */
	public function utcDateTime()
	{
		return new \DateTime('now', new \DateTimezone('UTC'));
	}
	
	/**
	 * REturn the Current Date time based on Website Set Timezone
	 */
	public function siteDateTime()
	{
		return new \DateTime('now', new \DateTimezone($this->get('dxoptions')->getSiteTimezone()));
	}
	
	/**
	 * Return Date time based on Server PHP Settings
	 */
	public function serverDateTime()
	{
		return new \DateTime('now', new \DateTimezone($this->defaultTimezone()));
	}
	
	/**
	 * Return current time based on local timezone
	 */
	public function localDateTime()
	{
		
	}
	
	
	/**
	 * return The Default time zone from PHP Settings
	 * @return string
	 */
	public function defaultTimezone()
	{
		return date_default_timezone_get();
	}
	
	/**
	 * 
	 * DATES
	 * 
	 */
	
	
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

	/**
	 * Retrieve service manager instance
	 *
	 * @return ServiceManager
	 */
	public function getServiceManager()
	{
		return $this->serviceManager;
	}

}

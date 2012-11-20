<?php

namespace Dxapp\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

class ProvidesEventsInputFilter extends InputFilter
{

	/**
	 * The service manager
	 * @var type 
	 */
	protected $serviceManager = NULL;

	/**
	 * The Module Options
	 * @var array
	 */
	protected $moduleOptions = array();

	/**
	 * Form Display Options
	 * @var array
	 */
	protected $displayOptions = array();

	/**
	 * Array or XML Filename that has the form information
	 * @var string|array
	 */
	protected $xmlForm = NULL;

	/**
	 * @var EventManagerInterface
	 */
	protected $events;

	/**
	 * Set the event manager instance used by this context
	 *
	 * @param  EventManagerInterface $events
	 * @return mixed
	 */
	public function setEventManager(EventManagerInterface $events)
	{
		$this->events = $events;
		return $this;
	}

	/**
	 * Retrieve the event manager
	 *
	 * Lazy-loads an EventManager instance if none registered.
	 *
	 * @return EventManagerInterface
	 */
	public function getEventManager()
	{
		if (!$this->events instanceof EventManagerInterface)
		{
			$identifiers = array(__CLASS__, get_called_class());
			if (isset($this->eventIdentifier))
			{
				if ((is_string($this->eventIdentifier))
						|| (is_array($this->eventIdentifier))
						|| ($this->eventIdentifier instanceof Traversable)
				)
				{
					$identifiers = array_unique($identifiers + (array) $this->eventIdentifier);
				}
				elseif (is_object($this->eventIdentifier))
				{
					$identifiers[] = $this->eventIdentifier;
				}
				// silently ignore invalid eventIdentifier types
			}
			$this->setEventManager(new EventManager($identifiers));
		}
		return $this->events;
	}

	/**
	 * Set service manager
	 * @param type $sm
	 * @return \Dxapp\Form\Base
	 */
	public function setServiceManager($sm)
	{
		$this->serviceManager = $sm;
		return $this;
	}

	public function setModuleOptions($moduleOptions)
	{
		$this->moduleOptions = $moduleOptions;
		return $this;
	}

	/**
	 * Return the Module Options
	 * @return array
	 */
	public function getModuleOptions()
	{
		return $this->moduleOptions;
	}

	/**
	 * Set the XML Form filename or array
	 * @param string|array $xmlForm Array of Form information or XML Filename
	 * @return \Dx\Form 
	 */
	public function setXmlForm($xmlForm)
	{
		$this->xmlForm = $xmlForm;
		return $this;
	}

	/**
	 * Get the XML Filename or Array
	 * @return string|array
	 */
	public function getXmlForm()
	{
		return $this->xmlForm;
	}

	/**
	 * Form Setup from XML
	 * @param array $xmlFile
	 * @return type 
	 */
	public function formFromXml($xml = NULL)
	{
		if (!empty($xml))
		{
			$this->setXmlForm($xml);
		}
		$xml = $this->getXmlForm();
		if (!is_array($xml))
		{
			$xml = \Dx\Reader\Xml::toArray($xml);
		}

		if ($xml && is_array($xml) && !empty($xml))
		{
			if (isset($xml['form']['inputFilter']['fieldsets']) && !empty($xml['form']['inputFilter']['fieldsets']))
			{
				$fieldsets = $xml['form']['inputFilter']['fieldsets'];
				foreach ($fieldsets as $fsName => $fs)
				{
					$filter = new InputFilter();
					foreach ($fs as $eleName => $ele)
					{
						if (isset($ele['name']))
						{
							$ele['name'] = $eleName;
						}
						if (isset($ele['required']) && $ele['required'])
						{
							if (!isset($ele['validators']['not_empty']))
							{
								$ele['validators']['not_empty'] = array(
									'name' => 'not_empty',
									'options' => array(
										'messages' => array(
											'isEmpty' => 'Required.'
										)));
							}
						}
						if (isset($ele['validators']))
						{
							$validators = array();
							foreach ($ele['validators'] as $vName => $v)
							{
								$validators[] = $this->processValidator($vName, $v);
							}
							$ele['validators'] = $validators;
						}
						$filter->add($ele);
					}
					$this->add($filter, $fsName);
				}
			}
		}
	}

	/**
	 * Process a single validator
	 * Only used if a given inputFilter was from an xmlFile or array
	 * @param type $v
	 */
	protected function processValidator($key, $v)
	{
		if (!isset($v['name']))
		{
			$v['name'] = $key;
		}
		switch ($key)
		{
			case 'record_exists':
				$mapper = $v['options']['mapper'];
				if (FALSE !== strpos($mapper, 'getServiceManager'))
				{
					$smOptions = explode('|', $mapper);
					$mapper = $this->serviceManager->get(trim($smOptions[1]));
					$v['options']['mapper'] = $mapper;
					return new \ZfcUser\Validator\RecordExists(array(
								'mapper' => $mapper,
								'key' => $v['options']['key']
							));
				}
				break;
			case 'no_record_exists':
				$mapper = $v['options']['mapper'];
				if (FALSE !== strpos($mapper, 'getServiceManager'))
				{
					$smOptions = explode('|', $mapper);
					$mapper = $this->serviceManager->get(trim($smOptions[1]));
					$v['options']['mapper'] = $mapper;
					return new \ZfcUser\Validator\NoRecordExists(array(
								'mapper' => $mapper,
								'key' => $v['options']['key']
							));
				}
				break;
			default;
		}
		return $v;
	}

}

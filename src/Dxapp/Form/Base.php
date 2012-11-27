<?php

/**
 * Form Manager - Proxy to Zend Form
 *
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @package Dx
 * @subpackage Form
 * @link http://labs.madayaw.com
 */

namespace Dxapp\Form;

use Zend\Form\Form as ZendForm;
use Dxapp\InputFilter\ProvidesEventsInputFilter;

class Base extends ZendForm
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
	 * Transform an XML file to array
	 * @param string $xmlFile
	 * @return type
	 */
	protected function xmlToArray($xmlFile)
	{
		$data = FALSE;
		if (file_exists($xmlFile))
		{
			$reader = new \Zend\Config\Reader\Xml();
			$xml = $reader->fromFile($xmlFile);
			if ($xml)
			{
				$data = $xml;
			}
		}
		return $data;
	}

	/**
	 * Form Setup from XML
	 * @param string|array $xmlFile
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
			$xml = $this->xmlToArray($xml);
		}
		$fieldsets = array();
		$elements = array();
		if ($xml && is_array($xml))
		{
			if (isset($xml['form']['name']))
			{
				$this->setName($xml['form']['name']);
			}
			if (isset($xml['form']['attributes']))
			{
				foreach ($xml['form']['attributes'] as $key => $val)
				{
					$this->setAttribute($key, $val);
				}
			}
			if (isset($xml['form']['displayOptions']))
			{
				$this->setDisplayOptions($xml['form']['displayOptions']);
			}
			if (isset($xml['form']['displayOptions']))
			{
				$this->setDisplayOptions($xml['form']['displayOptions']);
			}

			if (isset($xml['form']['fieldsets']) && !empty($xml['form']['fieldsets']))
			{
				foreach ($xml['form']['fieldsets'] as $fsName => $fs)
				{
					$fieldsets = $this->orderElement($fsName, $fieldsets, $fs);
				}
			}



			if (isset($xml['form']['elements']) && !empty($xml['form']['elements']))
			{
				foreach ($xml['form']['elements'] as $eleName => $ele)
				{
					if (isset($ele['fieldset']) && !empty($ele['fieldset']))
					{
						if (!isset($ele['name']))
						{
							$ele['name'] = $eleName;
						}
						$eleArr = array('spec' => $ele);
						$fieldsets[$ele['fieldset']]['elements'][] = $eleArr;
					}
					else
					{
						$elements = $this->orderElement($eleName, $elements, $ele);
					}
				}
			}
		}

		if (!empty($fieldsets))
		{
			foreach ($fieldsets as $name => $fs)
			{
				$add = TRUE;
				if (empty($fs['elements']))
				{
					$add = FALSE;
				}
				if (isset($fs['enable']) && (int) $fs['enable'] == 0)
				{
					$add = FALSE;
				}
				if ($add)
				{
					$this->add($this->xprepareFieldsets($fs));
				}
			}
		}
		if (!empty($elements))
		{
			foreach ($elements as $name => $ele)
			{
				$add = TRUE;
				if (isset($ele['enable']) && (int) $ele['enable'] == 0)
				{
					$add = FALSE;
				}
				if ($add)
				{
					$this->add($this->xprepareElement($ele));
				}
			}
		}
	}

	/**
	 * Set a single element attribute
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 * @return Element|ElementInterface
	 */
	public function setAttribute($key, $value)
	{
		if ($key == 'action')
		{
			if (is_array($value))
			{
				foreach ($value as $key => $val)
				{
					if ($key == 'route')
					{
						$view = $this->getServiceManager()->get('ViewRenderer');
						$action = $view->url($val);
						return parent::setAttribute('action', $action);
					}
				}
			}
		}
		return parent::setAttribute($key, $value);
	}

	/**
	 * Parse and Prepare fieldset before adding to form
	 * @param array $fs The fieldset with elements
	 * @return array
	 */
	public function xprepareFieldsets($fs)
	{
		if (isset($fs['elements']))
		{
			foreach ($fs['elements'] as $eleName => $ele)
			{
				$fs['elements'][$eleName] = $this->xprepareElement($ele);
			}
		}
		return $fs;
	}

	/**
	 * Prepare elements before adding to form
	 * @param array $ele
	 * @return array
	 */
	public function xprepareElement($ele)
	{
		if (isset($ele['spec']['options']['value_options']))
		{
			$valueOptions = $ele['spec']['options']['value_options'];
			if (!is_array($valueOptions))
			{
				if (FALSE !== strpos($valueOptions, 'getServiceManager'))
				{
					$callback = explode('|', $valueOptions);
					$serviceIndex = $callback[1];
					$method = $callback[2];
					$valueOptions = $this->getServiceManager()->get($serviceIndex)->$method();
					$ele['spec']['options']['value_options'] = $valueOptions;
				}
			}
		}
		return $ele;
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

	/**
	 * Return the service manager
	 */
	public function getServiceManager()
	{
		return $this->serviceManager;
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
	 * Position an Element
	 * @param array $elements Array of Elements
	 * @param array $ele The Element to insert
	 * @return array The new Array of Elements
	 */
	public function orderElement($name, $elements, $ele)
	{
		$positions = array('after', 'before');
		if (!isset($ele['name']))
		{
			$ele['name'] = $name;
		}
		foreach ($positions as $pos)
		{
			if (isset($ele[$pos]))
			{
				if (isset($elements[$ele[$pos]]))
				{
					$keyPos = $ele[$pos];
					unset($ele[$pos]);
					$elements = \Dxapp\Utility\ArrayManager::array_insert($elements, $keyPos, array($name => $ele), $pos);
					return $elements;
				}
			}
		}
		$elements[$name] = $ele;
		return $elements;
	}

	/**
	 * Set the Display Options
	 * @param type $displayOptions
	 * @return \Dxapp\Form\Base 
	 */
	public function setDisplayOptions($displayOptions)
	{
		$this->displayOptions = $displayOptions;
		return $this;
	}

	/**
	 * Return the Form Display Options 
	 */
	public function getDisplayOptions()
	{
		return $this->displayOptions;
	}

}

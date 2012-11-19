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

class Base extends ZendForm
{

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
			$xml = \Dx\Reader\Xml::toArray($xml);
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
			if (isset($xml['form']['inputFilter']))
			{
				$this->filterFromXml($xml['form']['inputFilters']);
			}
			if (isset($xml['form']['displayOptions']))
			{
				$this->setDisplayOptions($xml['form']['displayOptions']);
			}
			if (isset($xml['form']['fieldset']) && !empty($xml['form']['fieldset']))
			{
				foreach ($xml['form']['fieldset'] as $key => $fs)
				{
					if (isset($fs['name']))
					{
						$fieldsets = $this->orderElement($fieldsets, $fs);
					}
				}
			}
			if (isset($xml['form']['element']) && !empty($xml['form']['element']))
			{
				foreach ($xml['form']['element'] as $ele)
				{
					if (isset($ele['fieldset']) && !empty($ele['fieldset']))
					{
						$eleArr = array('spec' => $ele);
						$fieldsets[$ele['fieldset']]['elements'][] = $eleArr;
					}
					else
					{
						if (isset($ele['name']))
						{
							$elements = $this->orderElement($elements, $ele);
						}
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
					$this->add($fs);
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
					$this->add($ele);
				}
			}
		}
	}

/**
	 * Form Setup from XML
	 * @param string|array $xmlFile
	 * @return type 
	 */
	public function filterFromXml($xml = NULL)
	{
		if (!empty($xml))
		{
			$this->setXml($xml);
		}
		$xml = $this->getXml();
		if (!is_array($xml))
		{
			$xml = \Dx\Reader\Xml::toArray($xml);
		}

		if ($xml && is_array($xml) && !empty($xml))
		{
			if (isset($xml['fieldset']) && !empty($xml['fieldset']))
			{
				foreach ($xml as $f)
				{
					$filter = new InputFilter();
					$this->add($filter);
				}
			}
			else
			{
				foreach ($xml as $f)
				{
					$this->add($f);
				}
			}
		}
	}
	
	/**
	 * Position an Element
	 * @param array $elements Array of Elements
	 * @param array $ele The Element to insert
	 * @return array The new Array of Elements
	 */
	public function orderElement($elements, $ele)
	{
		$positions = array('after', 'before');
		foreach ($positions as $pos)
		{
			if (isset($ele[$pos]))
			{
				if (isset($elements[$ele[$pos]]))
				{
					$keyPos = $ele[$pos];
					unset($ele[$pos]);
					$elements = \Dx\ArrayManager::array_insert($elements, $keyPos, array($ele['name'] => $ele), $pos);
					return $elements;
				}
			}
		}
		$elements[$ele['name']] = $ele;
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

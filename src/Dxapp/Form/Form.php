<?php

namespace Dxapp\Form;

use Dxapp\Form\ProvidesEventsForm;

class Form extends ProvidesEventsForm
{
	/**
	 * Constructor
	 * @param string|array $xmlFile array or filename of the xmlFile
	 */
	public function __construct($formName = NULL, $xmlFile = NULL, $moduleOptions = array(), $serviceManager = NULL)
	{
		parent::__construct();
		if($serviceManager !== NULL)
		{
			$this->setServiceManager($serviceManager);
		}
		$this->setName($formName);
		$this->setModuleOptions($moduleOptions);
		$this->formFromXml($this->getModuleOptions()->getXmlFormFolder() . '/' . $xmlFile);
		$this->getEventManager()->trigger('init', $this);
		return $this;
	}
}

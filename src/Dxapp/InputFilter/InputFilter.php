<?php

namespace Dxapp\InputFilter;

use Dxapp\InputFilter\ProvidesEventsInputFilter;

class InputFilter extends ProvidesEventsInputFilter
{
	public function __construct($xmlFile = NULL, $moduleOptions = array(), $serviceManager = NULL)
	{
		if($serviceManager !== NULL)
		{
			$this->setServiceManager($serviceManager);
		}
		$this->setModuleOptions($moduleOptions);
		$this->formFromXml($this->getModuleOptions()->getXmlFormFolder() . '/' . $xmlFile);
		$this->getEventManager()->trigger('init', $this);
	}		
}
<?php
/**
 * Config
 * 
 * Get the config
 *  
 */
namespace Dxapp\View\Helper;

use Dx\View\AbstractHelper;

class Html extends AbstractHelper
{
	protected $bodyClass = NULL;
	
    public function __invoke()
    {
		$controller = \Dx::currentController();
		$controller = explode('\\', $controller);
		$action = \Dx::currentAction();
		if(isset($controller[2]))
		{
			$this->bodyClass = strtolower($controller[2]) . '-' . strtolower($action);
		}
        return $this;
    }
	
	/**
	 * Obfuscate an HTML code
	 * @param string $str
	 * @return string
	 */
	public function obfuscate($str)
	{
		return $str;
	}
	
	/**
	 * body tag pseudo-class name
	 * @param type $bodyClass
	 * @return \Dx\View\Helper\Html 
	 */
	public function setBodyClass($bodyClass)
	{
		$this->bodyClass .= (!empty($this->bodyClass) ? ' ' : '') . $bodyClass;
		return $this;
	}
	
	/**
	 * Get the Body Class
	 * @return string
	 */
	public function getBodyClass()
	{
		return $this->bodyClass;
	}
}

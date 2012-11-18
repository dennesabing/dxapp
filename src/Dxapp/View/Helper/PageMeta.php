<?php
/**
 * PageMeta
 * 
 * Set the Page Meta
 *  
 */
namespace Dxapp\View\Helper;

use Dx\View\AbstractHelper;

class PageMeta extends AbstractHelper
{
    public function __invoke()
    {
        return $this;
    }
	
	/**
	 * Set page title
	 * @param string|array $title The title to set
	 * 
	 * @return 
	 */
	public function setTitle($title)
	{
		$this->view->headTitle($title);
		return $this;
	}
	
	/**
	 * Set page metas
	 * @param type $key
	 * @param type $value
	 * @return \Dx\View\Helper\PageMeta 
	 */
	public function setMeta($key, $value)
	{
		return $this;
	}
}

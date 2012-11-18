<?php

/**
 * Config
 * 
 * Get the config
 *  
 */

namespace Dxapp\View\Helper;

use Dx\Config as DxConfig;
use Dx\View\AbstractHelper;

class Config extends AbstractHelper
{

	public function __invoke()
	{
		return $this;
	}

	/**
	 * Proxy to \Dx::getBaseDir
	 * @param type $type
	 * @return type 
	 */
	public function getBaseDir($type = NULL)
	{
		return \Dx::getBaseDir($type);
	}

	/**
	 * Proxy to \Dx\Config::getBaseUrl
	 * @param type $type
	 * @return type 
	 */
	public function getBaseUrl($type = NULL)
	{
		return \Dx::getBaseUrl($type);
	}

	/**
	 * Proxy to \Dx\Config::getAppConfig
	 * @param type $type
	 * @return type 
	 */
	public function getAppConfig($resource = NULL)
	{
		return \Dx::getAppConfig($resource);
	}

	/**
	 * Proxy to \Dx\Config::getAppTheme
	 * @return type 
	 */
	public function getAppTheme()
	{
		return DxConfig::getAppTheme();
	}

	/**
	 * Proxy to \Dx\Config::getAppSectionTheme
	 * @return type 
	 */
	public function getAppSectionTheme()
	{
		return DxConfig::getAppSectionTheme();
	}

	/**
	 * Get the URL of a css file
	 * @param mixed string|array $file
	 * @param boolean $theme Get url relative to the theme or relative to the Library
	 */
	public function getStylesheetUrl($stylesheet = NULL, $theme = TRUE)
	{
		if ($theme)
		{
			return $this->getBaseUrl('skin') . $this->getAppSectionTheme() . '/css/' . $stylesheet;
		}
		else
		{
			return $this->getBaseUrl('assets') . 'css/' . $stylesheet;
		}
	}

	/**
	 * Get the URL of a js file
	 * @param mixed string|array $file
	 * @param boolean $theme Get url relative to the theme or relative to the Library
	 */
	public function getJavascriptUrl($js = NULL, $theme = TRUE)
	{
		if ($theme)
		{
			return $this->getBaseUrl('skin') . $this->getAppSectionTheme() . '/js/' . $js;
		}
		else
		{
			return $this->getBaseUrl('assets') . 'js/' . $js;
		}
	}

	/**
	 * Get the URL of an image file
	 * @param mixed string|array $img
	 * @param boolean $theme Get url relative to the theme or relative to the Library
	 */
	public function getImageUrl($img = NULL, $theme = TRUE)
	{
		if ($theme)
		{
			return $this->getBaseUrl('skin') . $this->getAppSectionTheme() . '/img/' . $img;
		}
		else
		{
			return $this->getBaseUrl('assets') . 'img/' . $img;
		}
	}
	
	/**
	 * Get the jQuery supporting files; 
	 */
	public function getjQuery()
	{
		return $this->getBaseUrl('assets') . 'jquery/js/jquery-1.8.0.min.js';
	}
	
	/**
	 * Get the jQuery supporting files; 
	 */
	public function getjQueryUi()
	{
		return $this->getBaseUrl('assets') . 'jquery/js/jquery-ui-1.8.23.custom.min.js';
	}
	
	/**
	 * Get the jQuery UI Styles
	 */
	public function getjQueryStyle()
	{
		return $this->getBaseUrl('assets') . 'jquery/css/cupertino/jquery-ui-1.8.23.custom.css';
	}

}

<?php

/**
 * Config
 * 
 * Get the config
 *  
 */

namespace Dxapp\View\Helper;

use Dxapp\View\AbstractHelper;

class Html extends AbstractHelper
{

	/**
	 * The class attached to the body tag
	 * @var string
	 */
	protected $bodyClass = NULL;

	/**
	 * Use absolute URL
	 * @var boolean
	 */
	protected $useAbsoluteUrl = FALSE;

	/**
	 * USE Secure URL
	 * @var boolean
	 */
	protected $useSecureUrl = FALSE;

	/**
	 * The Header 1 Title
	 * @var string
	 */
	protected $title = NULL;

	public function __invoke()
	{
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
	
	/**
	 * Get asset URL
	 * @param mixed string|array $file
	 * @param boolean $theme Get url relative to the theme or relative to the doc root
	 * 
	 * @return string the URL
	 */
	public function getAssetUrl($asset)
	{
		return $this->getBaseUrl('assets') . '/' . $asset;
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
			return $this->getBaseUrl('skin') . '/' . $this->getSection() . '/' . $this->getTheme() . '/css/' . $stylesheet;
		}
		else
		{
			return $this->getBaseUrl('assets') . '/css/' . $stylesheet;
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
			return $this->getBaseUrl('skin') . '/' . $this->getTheme() . '/js/' . $js;
		}
		else
		{
			return $this->getBaseUrl('assets') . '/js/' . $js;
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
			return $this->getBaseUrl('skin') . '/' . $this->getTheme() . '/img/' . $img;
		}
		else
		{
			return $this->getBaseUrl('assets') . '/img/' . $img;
		}
	}

	/**
	 * Get the jQuery supporting files; 
	 */
	public function getjQuery()
	{
		return $this->getBaseUrl('assets') . '/jquery/js/jquery-1.8.0.min.js';
	}

	/**
	 * Get the jQuery supporting files; 
	 */
	public function getjQueryUi()
	{
		return $this->getBaseUrl('assets') . '/jquery/js/jquery-ui-1.8.23.custom.min.js';
	}

	/**
	 * Get the jQuery UI Styles
	 */
	public function getjQueryStyle()
	{
		return $this->getBaseUrl('assets') . '/jquery/css/cupertino/jquery-ui-1.8.23.custom.css';
	}

	/**
	 * Proxy to \Dx\Config::getBaseUrl
	 * @param type $type
	 * @return type 
	 */
	public function getBaseUrl($type = NULL, $useAbsolute = NULL, $useSecure = NULL)
	{
		if ($useAbsolute === NULL)
		{
			$useAbsolute = $this->getUseAbsoluteUrl();
		}
		if ($useSecure && $this->getUseSecureUrl())
		{
			$useAbsolute = TRUE;
		}
		else
		{
			$useSecure = FALSE;
		}
		if ($type === NULL)
		{
			$url = $this->getModuleOptions('dxapp')->getBaseUrl();
		}
		$option = $this->getModuleOptions('dxapp');
		if ($type !== NULL)
		{
			$url = $option->getBaseUrl() . $option->getApplicationPrefix() . $type;
		}
		else
		{
			$url = $option->getBaseUrl();
			if ($url == '/')
			{
				$url = '';
			}
		}
		$url = str_replace('//', '/', $url);
		$http = '';
		if ($useAbsolute)
		{
			$http = 'http://' . $this->getOptions()->getDomain();
			if ($useSecure)
			{
				$http = 'https://' . $this->getOptions()->getDomain();
			}
		}
		$url = $http . $url;
		return $url;
	}

	/**
	 * Get the Application Section Theme
	 * @return type 
	 */
	public function getTheme()
	{
		$config = $this->getModuleOptions('dxapp');
		if ($this->getSection() == 'back')
		{
			return $config->getBackendTheme();
		}
		return $config->getFrontendTheme();
	}

	/**
	 * GEt the Current Application section
	 * @return string
	 */
	public function getSection()
	{
		$config = $this->getModuleOptions('dxapp');
		return $config->getApplicationSection();
	}

	/**
	 * SEt the ABsolute URl Flag
	 * @param boolean $flag
	 * @return \Dxapp\View\Helper\Html 
	 */
	public function setUseAbsoluteUrl($flag)
	{
		$this->useAbsoluteUrl = $flag;
		return $this;
	}

	/**
	 * Get the Absolute URL flag
	 * @return boolean
	 */
	public function getUseAbsoluteUrl()
	{
		return $this->useAbsoluteUrl;
	}

	/**
	 * SEt Secure URL Flag
	 * @param true $flag
	 * @return \Dxapp\View\Helper\Html 
	 */
	public function setUseSecureUrl($flag)
	{
		$this->useSecureUrl = $flag;
		return $this;
	}

	/**
	 * Get if to use Secure Url
	 * @return \Dxapp\View\Helper\Html 
	 */
	public function getUseSecureUrl()
	{
		return $this->useSecureUrl;
	}

	/**
	 * Generates an url given the name of a route.
	 *
	 * @see    Zend\Mvc\Router\RouteInterface::assemble()
	 * @param  string  $name               Name of the route
	 * @param  array   $params             Parameters for the link
	 * @param  array   $options            Options for the route
	 * @param  boolean $reuseMatchedParams Whether to reuse matched parameters
	 * @return string Url                  For the link href attribute
	 * @throws Exception\RuntimeException  If no RouteStackInterface was provided
	 * @throws Exception\RuntimeException  If no RouteMatch was provided
	 * @throws Exception\RuntimeException  If RouteMatch didn't contain a matched route name
	 */
	public function url($name = null, array $params = array(), $options = array(), $reuseMatchedParams = FALSE)
	{
		$absolute = isset($options['useAbsoluteUrl']) ? $options['useAbsoluteUrl'] : FALSE;
		$secure = isset($options['useSecureUrl']) ? $options['useSecureUrl'] : FALSE;
		$url = $this->getBaseUrl(NULL, $absolute, $secure) . $this->getView()->url($name, $params, $options, $reuseMatchedParams);
		return $url;
	}

	/**
	 * Set Page Title Header 1
	 * @param string $title
	 * @return \Dxapp\View\Helper\Html 
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * Return the Page Title
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * Render a social login button
	 * @param type $provider
	 * @return type
	 */
	public function socialSignInButton($provider)
	{
		return '<a class="btn" href="'
            . $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider))
            . '">' . ucfirst($provider) . '</a>';
	}

}

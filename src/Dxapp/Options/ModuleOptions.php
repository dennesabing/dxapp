<?php

namespace Dxapp\Options;

use Dxapp\Options\Options;

class ModuleOptions extends Options
{

	/**
	 * The Project Name
	 * @var type 
	 */
	protected $projectName = 'Dx Application';

	/**
	 * The Application prefix
	 * Use to prefix all base folders
	 * Also use to uniquely identify this application among other applications
	 * @var string
	 */
	protected $applicationPrefix = 'dx';

	/**
	 * The Current section of the application back or front
	 * @var string
	 */
	protected $applicationSection = 'front';

	/**
	 * The Site Domain
	 * @var string
	 */
	protected $domain = NULL;

	/**
	 * The base url
	 * @var string
	 */
	protected $baseUrl = '/';

	/**
	 * If to use Absolute URL on all links sitewide
	 * @var boolean
	 */
	protected $useAbsoluteUrl = FALSE;

	/**
	 * If to use Secured URL on all links sitewide
	 * @var boolean
	 */
	protected $useSecureUrl = FALSE;

	/**
	 * The Template Maps
	 * @var array
	 */
	protected $templateMaps = array();

	/**
	 * Location of theme folders
	 * @var array
	 */
	protected $themeFolders = array();

	/**
	 * The Frontend theme
	 * @var string
	 */
	protected $frontendTheme = 'dxdefault';

	/**
	 * Collection of Frontend themes
	 * @var array
	 */
	protected $frontendThemes = array();

	/**
	 * The front theme scheme
	 * @TODO Feature
	 * @var string
	 */
	protected $frontendThemeScheme = NULL;

	/**
	 * The BackendTheme
	 * @var string
	 */
	protected $backendTheme = 'dxdefault';

	/**
	 * The backend theme scheme
	 * @TODO Feature
	 * @var string
	 */
	protected $backendThemeScheme = NULL;

	/**
	 * Path to the Application Path
	 * @var string
	 */
	protected $applicationPath = NULL;

	/**
	 * Path to the Public Accessible Folder
	 * @var string
	 */
	protected $sitePath = NULL;

	/**
	 * Enable email sending
	 * @var boolean
	 */
	protected $emailSending = TRUE;

	/**
	 * The email address of the sender of the email verification
	 * @var string
	 */
	protected $emailNoReplySender = 'no-reply@localhost';

	/**
	 * The name of the sender of the email verification
	 * @var string
	 */
	protected $emailNoReplySenderName = 'No Reply';

	/**
	 * The Main breadcrumb
	 * @var array
	 */
	protected $breadcrumbMain = array(
		'home' => array(
			'anchor' => 'Home',
			'url' => '/'
		)
	);

	/**
	 * Timezone to use when saving dates in DB
	 * Note: not used in Doctrine's Timestampable
	 * @var string
	 */
	protected $dbTimezone = 'UTC';

	/**
	 * The default timezone of the website.
	 * @var string
	 */
	protected $siteTimezone = 'Asia/Manila';

	/**
	 * The SiteWide DEfault maxRows
	 * Can be set on a per module basis.
	 * @var integer
	 */
	protected $defaultMaxRows = 15;

	/**
	 * The SiteWide defaultSorting
	 * Can be set also on a per module basis.
	 * @var string layout-maxrows-orderby-direction
	 */
	protected $defaultSorting = 'rows-15-pid-desc';

	/**
	 * The SiteWide DEfault layout
	 * Can be set also on a per module basis.
	 * @var string rows|grid
	 */
	protected $defaultLayout = 'rows';

	/**
	 * Common form type Layout based on \DluTwBootstrap\Form\FormUtil::FORM_TYPE_VERTICAL
	 * @var string
	 */
	protected $formTypeLayout = 'vertical';

	/**
	 * If to enable Dlu on form rendering
	 * @var boolean
	 */
	protected $enableFormDlu = TRUE;

	/**
	 * Set if to enable/disable Dlu on form rendering
	 * @param boolean $flag
	 * @return \Dxapp\Options\ModuleOptions
	 */
	public function setEnableFormDlu($flag)
	{
		$this->enableFormDlu = $flag;
		return $this;
	}

	/**
	 * Return if to enable/disable Dlu on form rendering
	 * @return boolean
	 */
	public function getEnableFormDlu()
	{
		return $this->enableFormDlu;
	}

	/**
	 * Set the Name of the sender for the email verification
	 * @param string $name The Name of the sender
	 * @return \DxUser\Options\ModuleOptions 
	 */
	public function setEmailNoReplySenderName($name)
	{
		$this->emailNoReplySenderName = $name;
		return $this;
	}

	/**
	 * Get the name of the sender of the email verification
	 * @return string
	 */
	public function getEmailNoReplySenderName()
	{
		return $this->emailNoReplySenderName;
	}

	/**
	 * Set the sender - email of the Email verifcation
	 * @param string $email The email address
	 * @return \DxUser\Options\ModuleOptions 
	 */
	public function setEmailNoReplySender($email)
	{
		$this->emailNoReplySender = $email;
		return $this;
	}

	/**
	 * Return the email address of the sender of email verify
	 * @return string
	 */
	public function getEmailNoReplySender()
	{
		return $this->emailNoReplySender;
	}

	/**
	 * Set the themeFolders
	 * @param type $themeFolder
	 * @return \Dxapp\Options\ModuleOptions
	 */
	public function setThemeFolders($themeFolder)
	{

		if (file_exists($themeFolder))
		{
			$folder = new \DirectoryIterator($themeFolder);
			while ($folder->valid())
			{
				$in = $folder->getFilename();
				if ($in != '.' && $in != '..')
				{
					$path = $folder->getPath();
					$configFile = $path . '/' . $in . '/theme.config.php';
					if (file_exists($configFile))
					{
						$themeConfig = include_once $configFile;
						$this->addTemplateMap($in, $themeConfig);
					}
				}
				$folder->next();
			}
		}
		return $this;
	}

	/**
	 * Add a theme folder
	 * @param type $themeFolder
	 */
	public function addThemeFolder($themeFolder)
	{
		$this->setThemeFolders($themeFolder);
		return $this;
	}

	/**
	 * Add a template map to maps of the themes
	 * @param string $name The theme name
	 * @param array $config theme config
	 */
	public function addTemplateMap($name, $config)
	{
		if (!array_key_exists($name, $this->getTemplateMaps()))
		{
			$this->addFrontendTheme($name);
			$this->templateMaps['front'][$name] = $config;
		}
	}

	/**
	 * Add to frontEndThemes
	 * @param type $name
	 */
	public function addFrontendTheme($name)
	{
		$this->frontendThemes[] = $name;
	}

	/**
	 * REturn the collected themes
	 * @return array
	 */
	public function getFrontendThemes()
	{
		return $this->frontendThemes;
	}

	/**
	 * Return all the template maps
	 * @return array
	 */
	public function getTemplateMaps()
	{
		return $this->templateMaps;
	}

	/**
	 * Return the Theme Folders
	 * @return array
	 */
	public function getThemeFolders()
	{
		//if (empty($this->themeFolders))
		//{
		$this->setThemeFolders(__DIR__ . '../../../../view/layout');
		//}
		return $this->themeFolders;
	}

	/**
	 * Set the Project Name
	 * @param string $projectName
	 * @return \Dxapp\Options\ModuleOptions
	 */
	public function setProjectName($projectName)
	{
		$this->setThemeFolders(__DIR__ . '../../../../view/layout');
		$this->projectName = $projectName;
		return $this;
	}

	/**
	 * Return the Project Name
	 * @return string
	 */
	public function getProjectName()
	{
		return $this->projectName;
	}

	/**
	 * SEt the Form Type Layout
	 * @param string $type
	 * @return \Dxapp\Options\ModuleOptions 
	 */
	public function setFormTypeLayout($type)
	{
		$this->formTypeLayout = $type;
		return $this;
	}

	/**
	 * GEt the form Type LAyout
	 * @return type 
	 */
	public function getFormTypeLayout()
	{
		return $this->formTypeLayout;
	}

	/**
	 * Set if to use Absolute URL on all links sitewide
	 * @param boolean $flag
	 * @return \Dxapp\Options\ModuleOptions 
	 */
	public function setUseAbsoluteUrl($flag)
	{
		$this->useAbsoluteUrl = $flag;
		return $this;
	}

	/**
	 * Get if to use Absolute URL on all links sitewide
	 * @return boolean
	 */
	public function getUseAbsoluteUrl()
	{
		return $this->useAbsoluteUrl;
	}

	/**
	 * Set if to use secured url on all links sitewide
	 * @param boolean $flag
	 * @return \Dxapp\Options\ModuleOptions 
	 */
	public function setUseSecureUrl($flag)
	{
		$this->useSecureUrl = $flag;
		return $this;
	}

	/**
	 * GEt if to use secure url on all links sitewide
	 * @return boolean
	 */
	public function getUseSecureUrl()
	{
		return $this->useSecureUrl;
	}

	/**
	 * Get the Application Environment
	 * @return string  
	 */
	public function getEnvironment()
	{
		return APP_ENV;
	}

	/**
	 * Is the Application in Development Mode?
	 * @return boolean 
	 */
	public function inDevelopment()
	{
		if ($this->getEnvironment() == 'development' || $this->getEnvironment() == 'staging')
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Set the default maxrows
	 * @param integer $maxRows The number of rows to display
	 * @return \DxCdRace\Options\Module 
	 */
	public function setDefaultMaxRows($maxRows)
	{
		$this->defaultMaxRows = $maxRows;
		return $this;
	}

	/**
	 * Return the Default MaxRows
	 * @return integer
	 */
	public function getDefaultMaxRows()
	{
		return $this->defaultMaxRows;
	}

	/**
	 * Set Default Sorting
	 * @param string $sorting e.g. layout-maxrows-date-desc
	 * @return \DxCdRace\Options\Module 
	 */
	public function setDefaultSorting($sorting)
	{
		$this->defaultSorting = $sorting;
		return $this;
	}

	/**
	 * Return the default sorting
	 * @return string
	 */
	public function getDefaultSorting()
	{
		return $this->defaultSorting;
	}

	/**
	 * Set the Default Layout
	 * @param string $layout e.g. rows or grid
	 * @return \DxCdRace\Options\Module 
	 */
	public function setDefaultLayout($layout)
	{
		$this->defaultLayout = $layout;
		return $this;
	}

	/**
	 * Return the default Layout
	 * @return string
	 */
	public function getDefaultLayout()
	{
		return $this->defaultLayout();
	}

	/**
	 * The Main route to redirect user if a logged-in user goes to a non-logged-in pages.
	 * @var string
	 */
	protected $routeMain = 'dx-user-account';

	/**
	 * SEt the Application SEction back or front
	 * @param string $section The Appplication section
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setApplicationSection($section)
	{
		if ($section == 'admin')
		{
			$section = 'back';
		}
		$this->applicationSection = $section;
		return $this;
	}

	/**
	 * GEt the application section
	 * @return string
	 */
	public function getApplicationSection()
	{
		return $this->applicationSection;
	}

	/**
	 * Set the frontend theme schem
	 * @param string $scheme
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setFrontendThemeScheme($scheme)
	{
		$this->frontendThemeScheme = $scheme;
		return $this;
	}

	/**
	 * Return the frontend theme scheme
	 * @return string
	 */
	public function getFrontendThemeScheme()
	{
		return $this->frontendThemeScheme;
	}

	/**
	 * Set the backend theme schem
	 * @param string $scheme
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setBackendThemeScheme($scheme)
	{
		$this->backendThemeScheme = $scheme;
		return $this;
	}

	/**
	 * Return the Backend theme scheme
	 * @return string
	 */
	public function getBackendThemeScheme()
	{
		return $this->backendThemeScheme;
	}

	/**
	 * Set the frontEnd theme
	 * @param string $theme The frontend Theme
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setFrontendTheme($theme)
	{
		$this->frontendTheme = $theme;
		return $this;
	}

	/**
	 * Get the frontend theme
	 * @return string
	 */
	public function getFrontendTheme()
	{
		return $this->frontendTheme;
	}

	/**
	 * Set the backend theme
	 * @param string $theme The frontend Theme
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setBackendTheme($theme)
	{
		$this->backendTheme = $theme;
		return $this;
	}

	/**
	 * Get the backend theme
	 * @return string
	 */
	public function getBackendTheme()
	{
		return $this->backendTheme;
	}

	/**
	 * SEt the Application Prefix
	 * @param string $prefix The prefix that will uniquely identify this application
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setApplicationPrefix($prefix)
	{
		$this->applicationPrefix = $prefix;
		return $this;
	}

	/**
	 * REturn the Application Prefix
	 * @return string
	 */
	public function getApplicationPrefix()
	{
		return APP_PREFIX;
	}

	/**
	 * Set the Base Url
	 * @param string $url The Base Url e.g. /
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setBaseUrl($url)
	{
		$this->baseUrl = $url;
		return $this;
	}

	/**
	 * REturn the base Url
	 * @return string
	 */
	public function getBaseUrl()
	{
		return $this->baseUrl;
	}

	/**
	 * SEt the Domain
	 * @param string $domain The website domain
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setDomain($domain)
	{
		$this->domain = $domain;
		return $this;
	}

	/**
	 * Return the website domain
	 * @return string
	 */
	public function getDomain()
	{
		if (empty($this->domain))
		{
			return $_SERVER['HTTP_HOST'];
		}
		return $this->domain;
	}

	/**
	 * SEt the Application Path
	 * @param string $path The Path to the Application Folder
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setApplicationPath($path)
	{
		if (is_dir($path))
		{
			$this->applicationPath = $path;
		}
		return $this;
	}

	/**
	 * Get the Application Path
	 * @return string 
	 */
	public function getApplicationPath()
	{
		return $this->applicationPath;
	}

	/**
	 * Set the Site Public Folder
	 * @param string $path Path to the Public accessible folder
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setSitePath($path)
	{
		if (is_dir($path))
		{
			$this->sitePath = $path;
		}
		return $this;
	}

	/**
	 * Get the Public accessible folder
	 * @return string
	 */
	public function getSitePath()
	{
		return $this->sitePath;
	}

	/**
	 * Set the Main route 
	 * @param string $route
	 * @return \DxUser\Options\ModuleOptions 
	 */
	protected function setRouteMain($route)
	{
		$this->routeMain = $route;
		return $this;
	}

	/**
	 * Get the main route
	 * @return string
	 */
	public function getRouteMain()
	{
		return $this->routeMain;
	}

	/**
	 * Enable/Disable email sending
	 * @param boolean $flag
	 * @return \DxUser\Options\ModuleOptions 
	 */
	public function setEmailSending($flag)
	{
		$this->emailSending = $flag;
		return $this;
	}

	/**
	 * Return email sending status
	 * @return boolean
	 */
	public function getEmailSending()
	{
		return $this->emailSending;
	}

	/**
	 * Set the main breadcrumb
	 * @param array $crumb
	 * @return \DxApp\Options\ModuleOptions 
	 */
	public function setBreadcrumbMain($crumb)
	{
		$this->breadcrumbMain = $crumb;
		return $this;
	}

	/**
	 * Get the main Breadcrumb
	 * @return type 
	 */
	public function getBreadcrumbMain()
	{
		return $this->breadcrumbMain;
	}

}

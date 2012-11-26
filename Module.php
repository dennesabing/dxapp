<?php

namespace Dxapp;

use Doctrine\Common\Annotations\AnnotationRegistry,
	Zend\Mvc\MvcEvent,
	Zend\EventManager\EventInterface as Event;

class Module
{

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function init()
	{
		$namespace = 'Gedmo\Mapping\Annotation';
		$libPath = 'vendor/Gedmo/doctrine-extension/lib';
		AnnotationRegistry::registerAutoloadNamespace($namespace, $libPath);
	}

	public function onBootstrap(Event $e)
	{
		$application = $e->getApplication();
		$eventManager = $application->getEventManager();
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'setApplicationSection'));
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'));
		$eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'setLayout'));

		$app = $e->getParam('application');
		$sm = $app->getServiceManager();
		$evm = $sm->get('doctrine.eventmanager.orm_default');

		$tablePrefix = new \Dxapp\Doctrine\Extension\TablePrefix('dx_');
		$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

		$cache = $sm->get('doctrine.cache.memcache');
		$annotationReader = new \Doctrine\Common\Annotations\AnnotationReader;
		$cachedAnnotationReader = new \Doctrine\Common\Annotations\CachedReader(
						$annotationReader,
						$cache
		);


		$sluggableListener = new \Gedmo\Sluggable\SluggableListener;
		$sluggableListener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($sluggableListener);

		$treeListener = new \Gedmo\Tree\TreeListener;
		$treeListener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($treeListener);

		$timestampableListener = new \Gedmo\Timestampable\TimestampableListener;
		$timestampableListener->setAnnotationReader($cachedAnnotationReader);
		$evm->addEventSubscriber($timestampableListener);

		$translatableListener = new \Gedmo\Translatable\TranslatableListener;
		$translatableListener->setAnnotationReader($cachedAnnotationReader);
		$translatableListener->setTranslatableLocale('en');
		$translatableListener->setDefaultLocale('en');
		$evm->addEventSubscriber($translatableListener);
	}

	/**
	 * Set the Layout based on themes-scheme
	 */
	public function setLayout(MvcEvent $e)
	{
		$app = $e->getParam('application');
		$sm = $app->getServiceManager();
		$config = $sm->get('dxapp_module_options');
		$viewModel = $e->getViewModel();
		$template = $viewModel->getTemplate();
		$templateMaps = $config->getTemplateMaps();
		$frontendTheme = $config->getFrontendTheme();
		$viewResolver = $sm->get('ViewResolver');
		$viewThemeResolver = new \Zend\View\Resolver\AggregateResolver();
		$templateMapResolver = new \Zend\View\Resolver\TemplateMapResolver();
		$pathResolver = new \Zend\View\Resolver\TemplatePathStack();
		$frontendThemes = array('dxdefault');
		if (!in_array($frontendTheme, $frontendThemes))
		{
			$frontendThemes[] = $frontendTheme;
		}
		foreach ($frontendThemes as $frontendTheme)
		{
			if (isset($templateMaps['front'][$frontendTheme]['view_manager']['template_map']))
			{
				$templateMapResolver->add($templateMaps['front'][$frontendTheme]['view_manager']['template_map']);
			}
			if (isset($templateMaps['front'][$frontendTheme]['view_manager']['template_path_stack']))
			{
				$pathResolver->addPaths($templateMaps['front'][$frontendTheme]['view_manager']['template_path_stack']);
				$defaultPathStack = $sm->get('ViewTemplatePathStack');
				$pathResolver->setDefaultSuffix($defaultPathStack->getDefaultSuffix());
				$viewThemeResolver->attach($pathResolver);
			}
			if (isset($templateMaps['front'][$frontendTheme]['assetic_configuration']))
			{
				$themeAssets = $sm->get('dxThemeAssets');
				$themeAssets->renderThemeAssets($frontendTheme, $templateMaps['front'][$frontendTheme]['assetic_configuration']);
			}
		}
		$viewThemeResolver->attach($templateMapResolver);
		$viewResolver->attach($viewThemeResolver, 100);
		$section = $config->getApplicationSection();
		if ($section == 'admin')
		{
			if (FALSE === strpos($template, 'admin-'))
			{
				$template = str_replace('/', '/admin-', $template);
			}
		}
		$viewModel->setTemplate($template);
	}

	/**
	 * Set the Application section based on route.
	 * Application section can be back or front
	 *
	 * @param  MvcEvent $e
	 * @return void
	 */
	public function setApplicationSection(MvcEvent $e)
	{
		$app = $e->getParam('application');
		$sm = $app->getServiceManager();
		$config = $sm->get('dxapp_module_options');
		$match = $e->getRouteMatch();
		if (!$match instanceof RouteMatch || 0 !== strpos($match->getMatchedRouteName(), $config->getApplicationPrefix() . 'admin'))
		{
			$section = 'front';
		}
		else
		{
			$section = 'back';
		}
		$config->setApplicationSection($section);
	}

	function getServiceConfig()
	{
		return array(
			'factories' => array(
				'dxapp_module_options' => function ($sm)
				{
					$config = $sm->get('Config');
					return new \Dxapp\Options\ModuleOptions(isset($config['dxapp']) ? $config['dxapp'] : array());
				},
				'dxMemcache' => function($sm)
				{
					$memcache = new \Memcache();
					$memcache->connect('localhost', 11211);
					return $memcache;
				},
				'dxService' => function($sm)
				{
					$dxService = new \Dxapp\Service\Dx();
					$dxService->setEntityManager($sm->get('doctrine.entitymanager.orm_default'));
					$dxService->setViewRenderer($sm->get('ViewRenderer'));
					return $dxService;
				},
				'dxFilecache' => function($sm)
				{
					$config = $sm->get('dxapp_module_options');
					$fileOptions = array(
						'cache_dir' => $config->getApplicationPath() . 'var/cache'
					);
					return \Zend\Cache\StorageFactory::factory(array(
								'adapter' => array(
									'name' => 'filesystem',
									'options' => $fileOptions
								),
								'plugins' => array(
									'ExceptionHandler' => array(
										'throw_exceptions' => false
									),
									'Serializer'
								)
							));
				},
				'dxThemeAssets' => function($sm)
				{
					$configuration = $sm->get('Configuration');

					$asseticConfig = new \AsseticBundle\Configuration($configuration['assetic_configuration'], $sm);
					$asseticAssetManager = $sm->get('Assetic\AssetManager');
					$asseticFilterManager = $sm->get('Assetic\FilterManager');

					$asseticService = new Service\ThemeAssets($asseticConfig);
					$asseticService->setAssetManager($asseticAssetManager);
					$asseticService->setFilterManager($asseticFilterManager);
					return $asseticService;
				}
			)
		);
	}

	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'dx' => function($sm)
				{
					$config = new \Dxapp\View\Helper\Dx();
					$config->setServiceManager($sm);
					return $config;
				},
				'dxBreadcrumb' => function()
				{
					return new \Dxapp\View\Helper\Breadcrumb();
				},
				'dxHtml' => function($sm)
				{
					$config = $sm->get('dx')->getModuleOptions();
					$html = new \Dxapp\View\Helper\Html();
					$html->setUseAbsoluteUrl($config->getUseAbsoluteUrl());
					$html->setUseSecureUrl($config->getUseSecureUrl());
					return $html;
				},
				'dxSidebar' => function()
				{
					return new \Dxapp\View\Helper\Sidebar();
				},
				'dxAlert' => function()
				{
					return new \Dxapp\View\Helper\Alert();
				},
			),
		);
	}

}

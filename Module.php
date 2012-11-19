<?php

namespace Dxapp;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\ClassLoader as DoctrineClassLoader;
use Zend\Mvc\ModuleRouteListener;
use Zend\Module\Manager,
	Zend\EventManager\StaticEventManager,
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
		$services = $application->getServiceManager();
		$eventManager = $application->getEventManager();
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'setApplicationSection'));

		$app = $e->getParam('application');
		$sm = $app->getServiceManager();
		$evm = $sm->get('doctrine.eventmanager.orm_default');

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
				'dxSession' => function($sm)
				{
                    return new \Zend\Session\Container('dxbuysell');
				}
			)
		);
	}

	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'dxConfig' => function($sm)
				{
					$config = new \Dxapp\View\Helper\Config();
					$config->setServiceManager($sm);
					return $config;
				},
				'dxUser' => function($sm)
				{
					return new \Dxapp\View\Helper\User();
				},
				'dxBreadcrumb' => function($sm)
				{
					return new \Dxapp\View\Helper\Breadcrumb();
				},
				'dxHtml' => function($sm)
				{
					$config = $sm->get('dxConfig')->getOptions();
					$html = new \Dxapp\View\Helper\Html();
					$html->setUseAbsoluteUrl($config->getUseAbsoluteUrl());
					$html->setUseSecureUrl($config->getUseSecureUrl());
					return $html;
				},
				'dxSidebar' => function($sm)
				{
					return new \Dxapp\View\Helper\Sidebar();
				},
				'dxPageMeta' => function($sm)
				{
					return new \Dxapp\View\Helper\PageMeta();
				},
				'dxAlert' => function($sm)
				{
					return new \Dxapp\View\Helper\Alert();
				},
			),
		);
	}

}

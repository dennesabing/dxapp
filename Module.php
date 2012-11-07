<?php

namespace Dxapp;

use Dx\Module as xModule;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\ClassLoader as DoctrineClassLoader;
use Zend\Mvc\ModuleRouteListener;
use Zend\Module\Manager,
	Zend\EventManager\StaticEventManager,
	Zend\EventManager\EventInterface as Event;

class Module extends xModule
{

	public $namespace = __NAMESPACE__;
	public $dir = __DIR__;

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
		$services->get('translator');
		$eventManager = $application->getEventManager();
		$routeListener = new \Dx\Mvc\Listener\Route();
		$routeListener->attach($eventManager);
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);

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

	function getServiceConfig()
	{
		return array(
			'factories' => array(
				'dx_memcache' => function($sm)
				{
					$memcache = new \Memcache();
					$memcache->connect('localhost', 11211);
					return $memcache;
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
					return new \Dx\View\Helper\Config();
				},
				'dxEscapeSlash' => function($sm)
				{
					return new \Dx\View\Helper\EscapeSlash();
				},
				'dxUser' => function($sm)
				{
					return new \Dx\View\Helper\User();
				},
				'dxBreadcrumb' => function($sm)
				{
					return new \Dx\View\Helper\Breadcrumb();
				},
				'dxHtml' => function($sm)
				{
					return new \Dx\View\Helper\Html();
				},
				'dxSidebar' => function($sm)
				{
					return new \Dx\View\Helper\Sidebar();
				},
			),
		);
	}

}

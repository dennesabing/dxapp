<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
$arr = array(
	'doctrine' => array(
		'configuration' => array(
			'orm_default' => array(
//				'metadata_cache' => 'filesystem',
//				'query_cache' => 'filesystem',
//				'result_cache' => 'filesystem',
				'metadata_cache' => 'memcache',
				'query_cache' => 'memcache',
				'result_cache' => 'memcache',
				'proxy_dir' => 'var/proxy/DoctrineORMModule',
			)
		),
		'cache' => array(
			'filesystem' => array(
				'directory' => 'var/cache/DoctrineORMModule',
			),
			'memcache' => array(
				'instance' => 'dx_memcache',
			),
		)
	),
	'router' => array(
		'routes' => array(
			'home' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/',
					'defaults' => array(
						'controller' => 'Dxapp\Controller\Index',
						'action' => 'index',
					),
				),
			),
		),
	),
	'service_manager' => array(
		'factories' => array(
			'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
		),
	),
	'translator' => array(
		'locale' => 'en_US',
		'translation_file_patterns' => array(
			array(
				'type' => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern' => '%s.mo',
			),
		),
	),
	'controllers' => array(
		'invokables' => array(
			'Dxapp\Controller\Index' => 'Dxapp\Controller\IndexController'
		),
	),
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array(
			'layout/layout' => \Dx::getBaseDir('app') . 'design/' . \Dx::getSection() . '/' . \Dx\Config::getAppTheme() . '/layout/1column.phtml',
			'layout/1column' => \Dx::getBaseDir('app') . 'design/' . \Dx::getSection() . '/' . \Dx\Config::getAppTheme() . '/layout/1column.phtml',
			'layout/2column-leftbar' => \Dx::getBaseDir('app') . 'design/' . \Dx::getSection() . '/' . \Dx\Config::getAppTheme() . '/layout/2column-leftbar.phtml',
			'layout/2column-rightbar' => \Dx::getBaseDir('app') . 'design/' . \Dx::getSection() . '/' . \Dx\Config::getAppTheme() . '/layout/2column-rightbar.phtml',
			'layout/3column' => \Dx::getBaseDir('app') . 'design/' . \Dx::getSection() . '/' . \Dx\Config::getAppTheme() . '/layout/3column.phtml',
			'error/404' => \Dx::getBaseDir('app') . 'design/' . \Dx::getSection() . '/' . \Dx\Config::getAppTheme() . '/view/error/404.phtml',
			'error/index' => \Dx::getBaseDir('app') . 'design/' . \Dx::getSection() . '/' . \Dx\Config::getAppTheme() . '/view/error/index.phtml',
			'dxapp/index/index' => \Dx::getBaseDir('app') . 'design/front/' . \Dx\Config::getAppTheme() . '/view/application/index/index.phtml',
		),
		'template_path_stack' => array(
			\Dx::getBaseDir('app') . 'design/' . \Dx::getSection() . '/' . \Dx\Config::getAppTheme() . '/partials',
		),
	),
	'assetic_configuration' => array(
		'modules' => array(
			'application' => array(
				'root_path' => __DIR__ . '/../assets/',
				'collections' => array(
					'base_css' => array(
						'assets' => array(
							'css/bootstrap-responsive.min.css',
							'css/bootstrap.min.css',
							'css/dlu-tw-bootstrap.css',
							'css/prettify.css',
							'css/style.css',
						),
						'filters' => array(
							'CssRewriteFilter' => array(
								'name' => 'Assetic\Filter\CssRewriteFilter'
							)
						),
						'options' => array(),
					),
					'base_js' => array(
						'assets' => array(
							'js/jquery-1.8.0.min.js',
							'js/html5.js',
							'js/bootstrap.min.js',
							'js/prettify.js'
						)
					),
					'base_images' => array(
						'assets' => array(
							'images/*.png',
							'images/*.ico',
						),
						'options' => array(
							'move_raw' => true,
						)
					),
				),
			),
		)
	)
);

return $arr;
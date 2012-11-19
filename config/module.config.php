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
				'instance' => 'dxMemcache',
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
    'controller_plugins' => array(
        'invokables' => array(
            'dxController' => 'Dxapp\Controller\Plugin\DxController',
            'layout' => 'Dxapp\Controller\Plugin\Layout',
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
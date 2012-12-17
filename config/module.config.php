<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
$arr = array(
	'view_manager' => array(
		'display_not_found_reason' => TRUE,
		'display_exceptions' => TRUE,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_path_stack' => array(
			'dxapp' => __DIR__ . '/../view',
		),
	),
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
//            'dxController' => 'Dxapp\Controller\Plugin\DxController',
        ),
    ),
	'assetic_configuration' => array(
		'webPath' => PUBLIC_ROOT . '/' . APP_PREFIX . 'assets',
		'cacheEnabled' => (APP_ENV == 'development' ? FALSE : TRUE),
		'cachePath' => APP_ROOT . '/var/cache/assets',
		'debug' => (APP_ENV == 'development' ? FALSE : FALSE),
		'baseUrl' => '@zfBaseUrl/' . APP_PREFIX . 'assets',
		'default' => array(
			'assets' => array(
			),
			'options' => array(
				'mixin' => true,
			),
		)
	)
);

return $arr;
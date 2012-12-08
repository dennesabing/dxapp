<?php

$arr = array(
	'view_manager' => array(
		'template_map' => array(
			'layout/layout' => __DIR__ . '/1column.phtml',
			'layout/1column' => __DIR__ . '/1column.phtml',
			'layout/2column-leftbar' => __DIR__ . '/2column-leftbar.phtml',
			'layout/2column-rightbar' => __DIR__ . '/2column-rightbar.phtml',
			'layout/3column' => __DIR__ . '/3column.phtml',
			'error/404' => __DIR__ . '/error/404.phtml',
			'error/index' => __DIR__ . '/error/index.phtml'
		),
		'template_path_stack' => array(
			'dxapp' => __DIR__ . '/partials/',
		),
	),
	'assetic_configuration' => array(
		'root_path' => __DIR__ . '/assets/',
		'collections' => array(
			'base_css' => array(
				'assets' => array(
					'css/bootstrap-datepicker-b.css',
					'css/bootstrap-timepicker.css',
					'css/bootstrap-responsive.min.css',
					'css/bootstrap.min.css',
					'css/dlu-tw-bootstrap.css',
					'css/prettify.css',
					'css/metalGray.css',
					'css/style.css',
				),
				'filters' => array(
					'CssRewriteFilter' => array(
						'name' => 'Assetic\Filter\CssRewriteFilter'
					)
				),
				'options' => array(
					'output' => 'css/dx.css'
				)
			),
			'base_js' => array(
				'assets' => array(
					'js/jquery-1.7.1.min.js',
					'js/jquery-ui.1.8.24.custom.min.js',
					'js/jquery.cookie.js',
					'js/html5.js',
					'js/bootstrap.min.js',
					'js/prettify.js',
					'js/bootstrap-datepicker-b.js',
					'js/bootstrap-timepicker.js',
					'js/dx.js'
				),
				'options' => array(
					'output' => 'js/dx.js'
				)
			),
			'base_images' => array(
				'assets' => array(
					'img/*.png',
					'img/*.ico',
				),
				'options' => array(
					'move_raw' => true,
				)
			),
		),
		'default' => array(
			'assets' => array(),
			'options' => array(
				'mixin' => true,
			),
		),
	)
);
return $arr;

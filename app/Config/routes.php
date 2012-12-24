<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

Router::parseExtensions('json');
Router::mapResources(array('access_tokens', 'qr_codes'));

Router::connect('/', array(
	'controller' => 'pages',
	'action' => 'view',
	'slug' => 'welcome'
		), array(
	'pass' => array(
		'slug'
	)
		)
);


Router::connect('/lan/:slug', array(
	'controller' => 'lans',
	'action' => 'view',
	'slug' => null
		), array(
	'pass' => array(
		'slug'
	),
	'slug' => '[a-z][-_a-z0-9]*'
		)
);

Router::connect('/page/:slug', array(
	'controller' => 'pages',
	'action' => 'view',
	'slug' => null
		), array(
	'pass' => array(
		'slug'
	),
	'slug' => '[a-z][-_a-z0-9]*')
);

/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';

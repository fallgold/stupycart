<?php

use Phalcon\Mvc\Router,
	Phalcon\Mvc\Url as UrlResolver,
	Phalcon\Mvc\Dispatcher as PhDispatcher,
	Phalcon\DI\FactoryDefault,
	Phalcon\Session\Adapter\Files as SessionAdapter,
	Phalcon\Loader,
	Phalcon\Mvc\View,
	Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

$di = new FactoryDefault();

$di['router'] = function() use ($config) {
	$router = new Router();

	$router->add('/', array(
		'module' => "frontend",
		'controller' => "common",
		'action' => "home"
	));

	foreach($config['modules'] as $module => $params) {
		if ($module == $config['default_module'])
			continue;
		$router->add('/'. $module. '/:controller/:action/:params[/]?',
			array(
				'namespace' => "Stupycart\\". ucfirst($module).'\Controllers',
				'module' => $module,
				'controller' => 1,
				'action' => 2,
				'params' => 3,
			)
		);
		$router->add('/'. $module. '/:controller/:action[/]?',
			array(
				'namespace' => "Stupycart\\". ucfirst($module).'\Controllers',
				'module' => $module,
				'controller' => 1,
				'action' => 2,
			)
		);
		$router->add('/'. $module. '/:controller[/]?',
			array(
				'namespace' => "Stupycart\\". ucfirst($module).'\Controllers',
				'module' => $module,
				'controller' => 1,
				'action' => 'index',
			)
		);
		$router->add('/'. $module. '[/]?',
			array(
				'namespace' => "Stupycart\\". ucfirst($module).'\Controllers',
				'module' => $module,
				'controller' => 'common',
				'action' => 'home',
			)
		);
	}

	$router->notFound(
		array(
			'module' => 'frontend',
			'namespace' => 'Stupycart\Frontend\Controllers',
			'controller' => 'error',
			'action' => 'show404'
		)
	);

	$router->setDefaultModule($config['default_module']);
	$router->setDefaultNamespace("Stupycart\\". ucfirst($config['default_module']).'\Controllers');
	$router->setDefaultController("index");
	$router->setDefaultAction("index");

	// nasty inject, fixme
	$router->_default_module = $config['default_module'];

	return $router;
};

// TODO filter
class StupyRequest extends \Phalcon\Http\Request {
	function __get($name) {
		if($name == 'get') {
			$_GET['route'] = self::getQueryE('route');
			return $_GET;
		}
	}
	function hasQuery($name) {
		if ($name == 'route') {
			return true;
		} else {
			return parent::hasQuery($name);
		}
	}
	function getQueryE($name = null, $filters = null, $defaultValue = null) {
		if ($name == 'route') {
			$dispatcher = $this->getDI()->get('dispatcher');
			$controller = strtolower($dispatcher->getControllerName());
			$action = $dispatcher->getActionName();
			$subaction = $dispatcher->getSubActionName();
			$route = $controller. '/'. $action. ($subaction == 'index' ? '' : $subaction);
			return $route;
		} else {
			return parent::getQuery($name, $filters, $defaultValue);
		}
	}
	function getPostE($name = null, $filters = null, $defaultValue = null) {
		return parent::getPost($name, $filters, $defaultValue);
	}
	function getE($name = null, $filters = null, $defaultValue = null) {
		return parent::get($name, $filters, $defaultValue);
	}
}

$di->set('request', function() {
	return new StupyRequest();
}, true);

/*
$di['url'] = function() {
	$url = new UrlResolver();
	$url->setBaseUri('/stupycart/');
	return $url;
};
 */

// use for cookies crypt
class StupyCrypt extends \Phalcon\Crypt {
	function decrypt($name, $key = NULL) {
		try {
			return parent::decrypt($name, $key);
		} catch (\Phalcon\Exception $e) {
			return '';
		}
	}
}
$di->set('crypt', function() {
	$crypt = new StupyCrypt();
	$crypt->setKey('1sl@#asls12l$');
	return $crypt;
}, true);

$di->set('session', function() {
	$session = new SessionAdapter();
	$session->start();
	return $session;
}, true);

$di->set('dispatcher', function() use ($di) {
	$evManager = $di->getShared('eventsManager');

	// 404, controller or action not found 
	$evManager->attach( "dispatch:beforeException", function($event, $dispatcher, $exception) {
		switch ($exception->getCode()) {
			case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
			case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
				$dispatcher->forward(array(
					'controller' => 'error',
					'action'	 => 'index',
					'subaction'  => 'show404',
				));
				return false;
		}
	});

	$dispatcher = new \Libs\Stupy\Dispatcher();
	$dispatcher->setEventsManager($evManager);
	return $dispatcher;
}, true);

$di->set('db', function() use ($config) {
	return new DbAdapter(array(
		"host" => $config['database']['host'],
		"username" => $config['database']['username'],
		"password" => $config['database']['password'],
		"dbname" => $config['database']['dbname'],
	));
}, true);

$di->set('ocdb', function() {
	return new \Stupycart\Common\Models\Settings();
}, true);


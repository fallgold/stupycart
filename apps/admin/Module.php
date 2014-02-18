<?php

namespace Stupycart\Admin;

define('DIR_LANGUAGE', __DIR__. '/language/');
define('DIR_APPLICATION', __DIR__. '/');

use Phalcon\Loader,
	Phalcon\Mvc\View,
	Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
	Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

	/**
	 * Registers the module auto-loader
	 */
	public function registerAutoloaders()
	{

		$loader = new Loader();

		$loader->registerNamespaces(array(
			'Stupycart\Admin\Controllers' => __DIR__ . '/controllers/',
			'Stupycart\Common\Models' => realpath(__DIR__ . '/../common/models/'),
			'Libs' => realpath(__DIR__ . '/../../libs/'),
		));
		
		$loader->register();
	}

	/**
	 * Registers the module-only services
	 *
	 * @param Phalcon\DI $di
	 */
	public function registerServices($di)
	{
		$di->set('view', function() {

			$view = new \Libs\Stupy\View();

			$view->setViewsDir(__DIR__ . '/views/');

			// .volt优先于.tpl
			$view->registerEngines(array(
				'.tpl' => function($view, $di) {
					$stupy = new \Libs\Stupy\StupyTplAdapter($view, $di);
					return $stupy;
				}
			));

			return $view;
		}, true);	

		//
		// -----------------
		//	opencart init
		// -----------------
		//

		$config = new \Libs\Opencart\Config();
		$di->set('config', $config, true);

		// Store
		// TODO opencart中根据host自动读取相应store的判断
		$store_id = 0;

		// Settings
		$settingsModel = new \Stupycart\Common\Models\Settings;
		$settingsModel->initStoreConfigs($config, $store_id);
			
		// Url
		$moduleName = $di['router']->getModuleName();
		$moduleName = ($moduleName == $di['router']->_default_module) ? '' : $moduleName;
		$di->set('url', function() use ($config, $moduleName) {
			return new \Libs\Opencart\Url01(
				$config->get('config_url'), 
				($config->get('config_secure') ?  $config->get('config_ssl') : $config->get('config_url')),
				$moduleName);
		}, true);

		// Log 
		// TODO 
		//	opencart中的error handler
		//	stupycart中的exception
		$di->set('log', function() {
			return new \Libs\Opencart\Log($config->get('config_error_filename'));
		}, true);

		// Cache
		// TODO 改用phalcon cache方式
		$di->set('cache', function() { return new \Libs\Opencart\Cache(); }, true);

		// Language		
		$languageModel = new \Stupycart\Common\Models\Language;
		$languages = $languageModel->getLanguages();
		$code = $languageModel->detect($languages, $config);
		$di->set('language', function() use ($code, $languages) { 
			$language = new \Libs\Opencart\Language($languages[$code]['directory']);
			$language->load($languages[$code]['filename']);
			return $language;
		}, true);

		// Document
		$di->set('document', function() { return new \Libs\Opencart\Document(); }, true);

		// Customer
		$di->set('customer', function() use ($di) { return new \Libs\Opencart\Customer($di); }, true);

		// Affiliate
		$di->set('affiliate', function() use ($di) { return new \Libs\Opencart\Affiliate($di); }, true);

		if ($di['request']->getQuery('tracking')) {
			$di['cookies']->set('tracking', $di['request']->getQuery('tracking'), time() + 3600 * 24 * 1000, '/');
		}
				
		// Currency
		$di->set('currency', function() use ($di) { return new \Libs\Opencart\Currency($di); }, true);

		// Tax
		$di->set('tax', function() use ($di) { return new \Libs\Opencart\Tax($di); }, true);

		// Weight
		$di->set('weight', function() use ($di) { return new \Libs\Opencart\Weight($di); }, true);

		// Length
		$di->set('length', function() use ($di) { return new \Libs\Opencart\Length($di); }, true);

		// Cart
		$di->set('cart', function() use ($di) { return new \Libs\Opencart\Cart($di); }, true);

		// Encryption
		$di->set('encryption', function() use ($config) { return new \Libs\Opencart\Encryption($config->get('config_encryption')); }, true);

		// User
		$di->set('user', function() use ($di) { return new \Libs\Opencart\User($di); }, true);

		// TODO
		// SEO URL's
		// Maintenance Mode

	}
}

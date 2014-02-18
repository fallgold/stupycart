<?php

namespace Stupycart\Frontend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	protected $data;

	public function initialize() {
		//
		// 默认render('controller/action');
		// 改为render('action/subaction');
		//
		$this->view->pick($this->dispatcher->getActionName(). '/'. $this->dispatcher->getSubActionName());
	}

	protected function getChild($child, $args = array()) {
		$this->view->storePick();
		$arr = explode('/', trim($child, '/'));
		$class = 'Stupycart\\'. ucfirst($this->dispatcher->getModuleName()) . '\Controllers\\'. ucfirst(\Phalcon\Text::camelize($arr[0])). '\\'. ucfirst(\Phalcon\Text::camelize($arr[1])). 'Controller';
		$subaction = isset($arr[2]) ? lcfirst(\Phalcon\Text::camelize($arr[2])) : 'indexAction';
		$obj = new $class;
		$response = $obj->$subaction($args);
		$this->view->restorePick();
		return $response;
	}

	protected function _commonAction() {
		$this->_columnLeftAction();
		$this->_columnRightAction();
		$this->_contentTopAction();
		$this->_contentBottomAction();
		$this->_headerAction();
		$this->_footerAction();
	}
	
	protected function _contentBottomAction() {
		$this->model_design_layout = new \Stupycart\Common\Models\Design\Layout();
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
		
		if ($this->request->hasQuery('route')) {
			$route = (string)$this->request->getQueryE('route');
		} else {
			$route = 'common/home';
		}
		
		$layout_id = 0;
		
		if ($route == 'product/category' && $this->request->hasQuery('path')) {
			$path = explode('_', (string)$this->request->getQueryE('path'));
				
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));			
		}
		
		if ($route == 'product/product' && $this->request->hasQuery('product_id')) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->getQueryE('product_id'));
		}
		
		if ($route == 'information/information' && $this->request->hasQuery('information_id')) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->getQueryE('information_id'));
		}
		
		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}
						
		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$module_data = array();
		
		$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
		
		$extensions = $this->model_setting_extension->getExtensions('module');		
		
		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');
			
			if ($modules) {
				foreach ($modules as $module) {
					if ($module['layout_id'] == $layout_id && $module['position'] == 'content_bottom' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
						);				
					}
				}
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}
		
		array_multisort($sort_order, SORT_ASC, $module_data);
		
		$this->data['modules'] = array();
		
		foreach ($module_data as $module) {
			$module = $this->getChild('module/' . $module['code'], $module['setting']);
			
			if ($module) {
				$this->data['modules'][] = $module;
			}
		}
	}

	protected function _contentTopAction() {
		$this->model_design_layout = new \Stupycart\Common\Models\Design\Layout();
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
		
		if ($this->request->hasQuery('route')) {
			$route = (string)$this->request->getQueryE('route');
		} else {
			$route = 'common/home';
		}
		
		$layout_id = 0;
		
		if ($route == 'product/category' && $this->request->hasQuery('path')) {
			$path = explode('_', (string)$this->request->getQueryE('path'));
				
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));			
		}
		
		if ($route == 'product/product' && $this->request->hasQuery('product_id')) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->getQueryE('product_id'));
		}
		
		if ($route == 'information/information' && $this->request->hasQuery('information_id')) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->getQueryE('information_id'));
		}
		
		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}
				
		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$module_data = array();
		
		$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
		
		$extensions = $this->model_setting_extension->getExtensions('module');		
		
		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');
			
			if ($modules) {
				foreach ($modules as $module) {
					if ($module['layout_id'] == $layout_id && $module['position'] == 'content_top' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
						);				
					}
				}
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}
		
		array_multisort($sort_order, SORT_ASC, $module_data);
		
		$this->data['modules'] = array();
		
		foreach ($module_data as $module) {
			$module = $this->getChild('module/' . $module['code'], $module['setting']);
			
			if ($module) {
				$this->data['modules'][] = $module;
			}
		}
	}

	protected function _columnRightAction() {
		$this->model_design_layout = new \Stupycart\Common\Models\Design\Layout();
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
		
		if ($this->request->hasQuery('route')) {
			$route = (string)$this->request->getQueryE('route');
		} else {
			$route = 'common/home';
		}
		
		$layout_id = 0;
		
		if ($route == 'product/category' && $this->request->hasQuery('path')) {
			$path = explode('_', (string)$this->request->getQueryE('path'));
				
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));			
		}
		
		if ($route == 'product/product' && $this->request->hasQuery('product_id')) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->getQueryE('product_id'));
		}
		
		if ($route == 'information/information' && $this->request->hasQuery('information_id')) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->getQueryE('information_id'));
		}
		
		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}
						
		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$module_data = array();
		
		$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
		
		$extensions = $this->model_setting_extension->getExtensions('module');		
		
		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');
			
			if ($modules) {
				foreach ($modules as $module) {
					if ($module['layout_id'] == $layout_id && $module['position'] == 'column_right' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
						);				
					}
				}
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}
		
		array_multisort($sort_order, SORT_ASC, $module_data);
		
		$this->data['modules'] = array();
		
		foreach ($module_data as $module) {
			$module = $this->getChild('module/' . $module['code'], $module['setting']);
			
			if ($module) {
				$this->data['modules'][] = $module;
			}
		}
	}

	protected function _columnLeftAction() {
		$this->model_design_layout = new \Stupycart\Common\Models\Design\Layout();
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
		
		if ($this->request->hasQuery('route')) {
			$route = (string)$this->request->getQueryE('route');
		} else {
			$route = 'common/home';
		}
		
		$layout_id = 0;
		
		if ($route == 'product/category' && $this->request->hasQuery('path')) {
			$path = explode('_', (string)$this->request->getQueryE('path'));
				
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));			
		}
		
		if ($route == 'product/product' && $this->request->hasQuery('product_id')) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->getQueryE('product_id'));
		}
		
		if ($route == 'information/information' && $this->request->hasQuery('information_id')) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->getQueryE('information_id'));
		}
		
		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}
						
		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$module_data = array();
		
		$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
		
		$extensions = $this->model_setting_extension->getExtensions('module');		
		
		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');
		
			if ($modules) {
				foreach ($modules as $module) {
					if ($module['layout_id'] == $layout_id && $module['position'] == 'column_left' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
						);				
					}
				}
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}
		
		array_multisort($sort_order, SORT_ASC, $module_data);
		
		$this->data['modules'] = array();
		
		foreach ($module_data as $module) {
			$module = $this->getChild('module/' . $module['code'], $module['setting']);
			
			if ($module) {
				$this->data['modules'][] = $module;
			}
		}
	}

	protected function _footerAction() {
		$this->language->load('common/footer');
		
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_service'] = $this->language->get('text_service');
		$this->data['text_extra'] = $this->language->get('text_extra');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_return'] = $this->language->get('text_return');
    	$this->data['text_sitemap'] = $this->language->get('text_sitemap');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_wishlist'] = $this->language->get('text_wishlist');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		
		$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
		
		$this->data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$this->data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
    	}

		$this->data['contact'] = $this->url->link('information/contact');
		$this->data['return'] = $this->url->link('account/return/insert', '', 'SSL');
    	$this->data['sitemap'] = $this->url->link('information/sitemap');
		$this->data['manufacturer'] = $this->url->link('product/manufacturer');
		$this->data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
		$this->data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
		$this->data['special'] = $this->url->link('product/special');
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['order'] = $this->url->link('account/order', '', 'SSL');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');		

		$this->data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
		
		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->model_tool_online = new \Stupycart\Common\Models\Tool\Online();
	
			if ($this->request->hasServer('REMOTE_ADDR')) {
				$ip = $this->request->getServer('REMOTE_ADDR');	
			} else {
				$ip = ''; 
			}
			
			if ($this->request->hasServer('HTTP_HOST') && $this->request->hasServer('REQUEST_URI')) {
				$url = 'http://' . $this->request->getServer('HTTP_HOST') . $this->request->getServer('REQUEST_URI');	
			} else {
				$url = '';
			}
			
			if ($this->request->hasServer('HTTP_REFERER')) {
				$referer = $this->request->getServer('HTTP_REFERER');	
			} else {
				$referer = '';
			}
						
			$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
		}		
	}

	protected function _headerAction() {
		$this->data['title'] = $this->document->getTitle();
		
		if ($this->request->hasServer('HTTPS') && (($this->request->getServer('HTTPS') == 'on') || ($this->request->getServer('HTTPS') == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$this->data['base'] = $server;
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['name'] = $this->config->get('config_name');
		
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}		
		
		$this->language->load('common/header');
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), ($this->session->has('wishlist') ? count($this->session->get('wishlist')) : 0));
		$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_checkout'] = $this->language->get('text_checkout');
				
		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['shopping_cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		
		// Daniel's robot detector
		$status = true;
		
		if ($this->request->hasServer('HTTP_USER_AGENT')) {
			$robots = explode("\n", trim($this->config->get('config_robots')));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->getServer('HTTP_USER_AGENT'), trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}
		
		// A dirty hack to try to set a cookie for the multi-store feature
		$this->model_setting_store = new \Stupycart\Common\Models\Setting\Store();
		
		$this->data['stores'] = array();
		
		if ($this->config->get('config_shared') && $status) {
			$this->data['stores'][] = $server . 'js/crossdomain.php?session_id=' . $this->session->getId();
			
			$stores = $this->model_setting_store->getStores();
					
			foreach ($stores as $store) {
				$this->data['stores'][] = $store['url'] . 'js/crossdomain.php?session_id=' . $this->session->getId();
			}
		}
				
		// Search		
		if ($this->request->hasQuery('search')) {
			$this->data['search'] = $this->request->getQueryE('search');
		} else {
			$this->data['search'] = '';
		}
		
		// Menu
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();
				
				$children = $this->model_catalog_category->getCategories($category['category_id']);
				
				foreach ($children as $child) {
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);
					
					$product_total = $this->model_catalog_product->getTotalProducts($data);
									
					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);						
				}
				
				// Level 1
				$this->data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}	

		$this->data['language'] = $this->getChild('module/language');
		$this->data['currency'] = $this->getChild('module/currency');
		$this->data['cart'] = $this->getChild('module/cart');
	}
}

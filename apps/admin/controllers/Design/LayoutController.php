<?php 

namespace Stupycart\Admin\Controllers\Design;

class LayoutController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
 
	public function indexAction() {
		$this->language->load('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_design_layout->addLayout($this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
			
			$this->response->redirect($this->url->link('design/layout', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_design_layout->editLayout($this->request->getQueryE('layout_id'), $this->request->getPostE());

			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
					
			$this->response->redirect($this->url->link('design/layout', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}
 
	public function deleteAction() {
		$this->language->load('design/layout');
 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $layout_id) {
				$this->model_design_layout->deleteLayout($layout_id);
			}
			
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}

			$this->response->redirect($this->url->link('design/layout', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'name';
		}
		
		if ($this->request->hasQuery('order')) {
			$order = $this->request->getQueryE('order');
		} else {
			$order = 'ASC';
		}
		
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}
			
		$url = '';
			
		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}

		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
		
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('design/layout', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('design/layout/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('design/layout/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		 
		$this->data['layouts'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$layout_total = $this->model_design_layout->getTotalLayouts();
		
		$results = $this->model_design_layout->getLayouts($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('design/layout/update', 'token=' . $this->session->get('token') . '&layout_id=' . $result['layout_id'] . $url, 'SSL')
			);

			$this->data['layouts'][] = array(
				'layout_id' => $result['layout_id'],
				'name'      => $result['name'],
				'selected'  => $this->request->hasPost('selected') && in_array($result['layout_id'], $this->request->getPostE('selected')),				
				'action'    => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_action'] = $this->language->get('column_action');	

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
		
		$this->data['sort_name'] = $this->url->link('design/layout', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $layout_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('design/layout', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('design/layout_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_default'] = $this->language->get('text_default');
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_route'] = $this->language->get('entry_route');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_route'] = $this->language->get('button_add_route');
		$this->data['button_remove'] = $this->language->get('button_remove');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
				
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}

		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
		
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('design/layout', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!$this->request->hasQuery('layout_id')) { 
			$this->data['action'] = $this->url->link('design/layout/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('design/layout/update', 'token=' . $this->session->get('token') . '&layout_id=' . $this->request->getQueryE('layout_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('design/layout', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		if ($this->request->hasQuery('layout_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$layout_info = $this->model_design_layout->getLayout($this->request->getQueryE('layout_id'));
		}

		if ($this->request->hasPost('name')) {
			$this->data['name'] = $this->request->getPostE('name');
		} elseif (!empty($layout_info)) {
			$this->data['name'] = $layout_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if ($this->request->hasPost('layout_route')) {
			$this->data['layout_routes'] = $this->request->getPostE('layout_route');
		} elseif ($this->request->hasQuery('layout_id')) {
			$this->data['layout_routes'] = $this->model_design_layout->getLayoutRoutes($this->request->getQueryE('layout_id'));
		} else {
			$this->data['layout_routes'] = array();
		}	
				
		$this->view->pick('design/layout_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/layout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->getPostE('name')) < 3) || (utf8_strlen($this->request->getPostE('name')) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/layout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
		$this->model_catalog_information = new \Stupycart\Common\Models\Admin\Catalog\Information();
		
		foreach ($this->request->getPostE('selected') as $layout_id) {
			if ($this->config->get('config_layout_id') == $layout_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}
			
			$store_total = $this->model_setting_store->getTotalStoresByLayoutId($layout_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
			
			$product_total = $this->model_catalog_product->getTotalProductsByLayoutId($layout_id);
	
			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}

			$category_total = $this->model_catalog_category->getTotalCategoriesByLayoutId($layout_id);
	
			if ($category_total) {
				$this->error['warning'] = sprintf($this->language->get('error_category'), $category_total);
			}
							
			$information_total = $this->model_catalog_information->getTotalInformationsByLayoutId($layout_id);
		
			if ($information_total) {
				$this->error['warning'] = sprintf($this->language->get('error_information'), $information_total);
			}
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
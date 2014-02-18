<?php

namespace Stupycart\Admin\Controllers\Catalog;

class InformationController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();

	public function indexAction() {
		$this->language->load('catalog/information');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->model_catalog_information = new \Stupycart\Common\Models\Admin\Catalog\Information();

		$this->getList();
	}

	public function insertAction() {
		$this->language->load('catalog/information');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_information = new \Stupycart\Common\Models\Admin\Catalog\Information();
				
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_information->addInformation($this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('catalog/information', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('catalog/information');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_information = new \Stupycart\Common\Models\Admin\Catalog\Information();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_information->editInformation($this->request->getQueryE('information_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('catalog/information', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}
 
	public function deleteAction() {
		$this->language->load('catalog/information');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_information = new \Stupycart\Common\Models\Admin\Catalog\Information();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $information_id) {
				$this->model_catalog_information->deleteInformation($information_id);
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
			
			$this->response->redirect($this->url->link('catalog/information', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'id.title';
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
			'href'      => $this->url->link('catalog/information', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('catalog/information/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/information/delete', 'token=' . $this->session->get('token') . $url, 'SSL');	

		$this->data['informations'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$information_total = $this->model_catalog_information->getTotalInformations();
	
		$results = $this->model_catalog_information->getInformations($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/information/update', 'token=' . $this->session->get('token') . '&information_id=' . $result['information_id'] . $url, 'SSL')
			);
						
			$this->data['informations'][] = array(
				'information_id' => $result['information_id'],
				'title'          => $result['title'],
				'sort_order'     => $result['sort_order'],
				'selected'       => $this->request->hasPost('selected') && in_array($result['information_id'], $this->request->getPostE('selected')),
				'action'         => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
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
		
		$this->data['sort_title'] = $this->url->link('catalog/information', 'token=' . $this->session->get('token') . '&sort=id.title' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/information', 'token=' . $this->session->get('token') . '&sort=i.sort_order' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/information', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('catalog/information_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_bottom'] = $this->language->get('entry_bottom');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
    	
		$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = array();
		}
		
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
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
			'href'      => $this->url->link('catalog/information', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!$this->request->hasQuery('information_id')) {
			$this->data['action'] = $this->url->link('catalog/information/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/information/update', 'token=' . $this->session->get('token') . '&information_id=' . $this->request->getQueryE('information_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/information', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('information_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$information_info = $this->model_catalog_information->getInformation($this->request->getQueryE('information_id'));
		}
		
		$this->data['token'] = $this->session->get('token');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('information_description')) {
			$this->data['information_description'] = $this->request->getPostE('information_description');
		} elseif ($this->request->hasQuery('information_id')) {
			$this->data['information_description'] = $this->model_catalog_information->getInformationDescriptions($this->request->getQueryE('information_id'));
		} else {
			$this->data['information_description'] = array();
		}

		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if ($this->request->hasPost('information_store')) {
			$this->data['information_store'] = $this->request->getPostE('information_store');
		} elseif ($this->request->hasQuery('information_id')) {
			$this->data['information_store'] = $this->model_catalog_information->getInformationStores($this->request->getQueryE('information_id'));
		} else {
			$this->data['information_store'] = array(0);
		}		
		
		if ($this->request->hasPost('keyword')) {
			$this->data['keyword'] = $this->request->getPostE('keyword');
		} elseif (!empty($information_info)) {
			$this->data['keyword'] = $information_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if ($this->request->hasPost('bottom')) {
			$this->data['bottom'] = $this->request->getPostE('bottom');
		} elseif (!empty($information_info)) {
			$this->data['bottom'] = $information_info['bottom'];
		} else {
			$this->data['bottom'] = 0;
		}
		
		if ($this->request->hasPost('status')) {
			$this->data['status'] = $this->request->getPostE('status');
		} elseif (!empty($information_info)) {
			$this->data['status'] = $information_info['status'];
		} else {
			$this->data['status'] = 1;
		}
				
		if ($this->request->hasPost('sort_order')) {
			$this->data['sort_order'] = $this->request->getPostE('sort_order');
		} elseif (!empty($information_info)) {
			$this->data['sort_order'] = $information_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		if ($this->request->hasPost('information_layout')) {
			$this->data['information_layout'] = $this->request->getPostE('information_layout');
		} elseif ($this->request->hasQuery('information_id')) {
			$this->data['information_layout'] = $this->model_catalog_information->getInformationLayouts($this->request->getQueryE('information_id'));
		} else {
			$this->data['information_layout'] = array();
		}

		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
				
		$this->view->pick('catalog/information_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->getPostE('information_description') as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		
		foreach ($this->request->getPostE('selected') as $information_id) {
			if ($this->config->get('config_account_id') == $information_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
			
			if ($this->config->get('config_checkout_id') == $information_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}
			
			if ($this->config->get('config_affiliate_id') == $information_id) {
				$this->error['warning'] = $this->language->get('error_affiliate');
			}
						
			$store_total = $this->model_setting_store->getTotalStoresByInformationId($information_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
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
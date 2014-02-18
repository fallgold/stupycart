<?php

namespace Stupycart\Admin\Controllers\Localisation;

class TaxClassController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
 
	public function indexAction() {
		$this->language->load('localisation/tax_class');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();
		
		$this->getList(); 
	}

	public function insertAction() {
		$this->language->load('localisation/tax_class');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_tax_class->addTaxClass($this->request->getPostE());

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
			
			$this->response->redirect($this->url->link('localisation/tax_class', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('localisation/tax_class');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_tax_class->editTaxClass($this->request->getQueryE('tax_class_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('localisation/tax_class', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('localisation/tax_class');

		$this->document->setTitle($this->language->get('heading_title'));
 		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $tax_class_id) {
				$this->model_localisation_tax_class->deleteTaxClass($tax_class_id);
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
			
			$this->response->redirect($this->url->link('localisation/tax_class', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'title';
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
			'href'      => $this->url->link('localisation/tax_class', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->data['insert'] = $this->url->link('localisation/tax_class/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/tax_class/delete', 'token=' . $this->session->get('token') . $url, 'SSL');		
		
		$this->data['tax_classes'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$tax_class_total = $this->model_localisation_tax_class->getTotalTaxClasses();

		$results = $this->model_localisation_tax_class->getTaxClasses($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/tax_class/update', 'token=' . $this->session->get('token') . '&tax_class_id=' . $result['tax_class_id'] . $url, 'SSL')
			);
					
			$this->data['tax_classes'][] = array(
				'tax_class_id' => $result['tax_class_id'],
				'title'        => $result['title'],
				'selected'     => $this->request->hasPost('selected') && in_array($result['tax_class_id'], $this->request->getPostE('selected')),
				'action'       => $action				
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
	
		$this->data['column_title'] = $this->language->get('column_title');
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
		 
		$this->data['sort_title'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->get('token') . '&sort=title' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $tax_class_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/tax_class', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/tax_class_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_shipping'] = $this->language->get('text_shipping');	
		$this->data['text_payment'] = $this->language->get('text_payment');	
		$this->data['text_store'] = $this->language->get('text_store');	
						
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_based'] = $this->language->get('entry_based');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_rule'] = $this->language->get('button_add_rule');
		$this->data['button_remove'] = $this->language->get('button_remove');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
 		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
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
			'href'      => $this->url->link('localisation/tax_class', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('tax_class_id')) {
			$this->data['action'] = $this->url->link('localisation/tax_class/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/tax_class/update', 'token=' . $this->session->get('token') . '&tax_class_id=' . $this->request->getQueryE('tax_class_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('tax_class_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$tax_class_info = $this->model_localisation_tax_class->getTaxClass($this->request->getQueryE('tax_class_id'));
		}

		if ($this->request->hasPost('title')) {
			$this->data['title'] = $this->request->getPostE('title');
		} elseif (!empty($tax_class_info)) {
			$this->data['title'] = $tax_class_info['title'];
		} else {
			$this->data['title'] = '';
		}

		if ($this->request->hasPost('description')) {
			$this->data['description'] = $this->request->getPostE('description');
		} elseif (!empty($tax_class_info)) {
			$this->data['description'] = $tax_class_info['description'];
		} else {
			$this->data['description'] = '';
		}

		$this->model_localisation_tax_rate = new \Stupycart\Common\Models\Admin\Localisation\TaxRate();
		
		$this->data['tax_rates'] = $this->model_localisation_tax_rate->getTaxRates();
		
		if ($this->request->hasPost('tax_rule')) {
			$this->data['tax_rules'] = $this->request->getPostE('tax_rule');
		} elseif ($this->request->hasQuery('tax_class_id')) {
			$this->data['tax_rules'] = $this->model_localisation_tax_class->getTaxRules($this->request->getQueryE('tax_class_id'));
		} else {
			$this->data['tax_rules'] = array();
		}

		$this->view->pick('localisation/tax_class_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->getPostE('title')) < 3) || (utf8_strlen($this->request->getPostE('title')) > 32)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if ((utf8_strlen($this->request->getPostE('description')) < 3) || (utf8_strlen($this->request->getPostE('description')) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();

		foreach ($this->request->getPostE('selected') as $tax_class_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByTaxClassId($tax_class_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
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
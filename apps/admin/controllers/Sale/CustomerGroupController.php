<?php

namespace Stupycart\Admin\Controllers\Sale;

class CustomerGroupController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
 
	public function indexAction() {
		$this->language->load('sale/customer_group');
 
		$this->document->setTitle($this->language->get('heading_title'));
 		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('sale/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_group->addCustomerGroup($this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('sale/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_group->editCustomerGroup($this->request->getQueryE('customer_group_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() { 
		$this->language->load('sale/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
      		foreach ($this->request->getPostE('selected') as $customer_group_id) {
				$this->model_sale_customer_group->deleteCustomerGroup($customer_group_id);	
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
			
			$this->response->redirect($this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'cgd.name';
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
			'href'      => $this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('sale/customer_group/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer_group/delete', 'token=' . $this->session->get('token') . $url, 'SSL');	
	
		$this->data['customer_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$customer_group_total = $this->model_sale_customer_group->getTotalCustomerGroups();
		
		$results = $this->model_sale_customer_group->getCustomerGroups($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer_group/update', 'token=' . $this->session->get('token') . '&customer_group_id=' . $result['customer_group_id'] . $url, 'SSL')
			);		
		
			$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : null),
				'sort_order'        => $result['sort_order'],
				'selected'          => $this->request->hasPost('selected') && in_array($result['customer_group_id'], $this->request->getPostE('selected')),
				'action'            => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
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

		$this->data['sort_name'] = $this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . '&sort=cgd.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . '&sort=cg.sort_order' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $customer_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();				

		$this->data['sort'] = $sort; 
		$this->data['order'] = $order;

		$this->view->pick('sale/customer_group_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
 	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_approval'] = $this->language->get('entry_approval');
		$this->data['entry_company_id_display'] = $this->language->get('entry_company_id_display');
		$this->data['entry_company_id_required'] = $this->language->get('entry_company_id_required');
		$this->data['entry_tax_id_display'] = $this->language->get('entry_tax_id_display');
		$this->data['entry_tax_id_required'] = $this->language->get('entry_tax_id_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
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
			'href'      => $this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
			
		if (!$this->request->hasQuery('customer_group_id')) {
			$this->data['action'] = $this->url->link('sale/customer_group/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer_group/update', 'token=' . $this->session->get('token') . '&customer_group_id=' . $this->request->getQueryE('customer_group_id') . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('sale/customer_group', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('customer_group_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($this->request->getQueryE('customer_group_id'));
		}
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('customer_group_description')) {
			$this->data['customer_group_description'] = $this->request->getPostE('customer_group_description');
		} elseif ($this->request->hasQuery('customer_group_id')) {
			$this->data['customer_group_description'] = $this->model_sale_customer_group->getCustomerGroupDescriptions($this->request->getQueryE('customer_group_id'));
		} else {
			$this->data['customer_group_description'] = array();
		}	
		
		if ($this->request->hasPost('approval')) {
			$this->data['approval'] = $this->request->getPostE('approval');
		} elseif (!empty($customer_group_info)) {
			$this->data['approval'] = $customer_group_info['approval'];
		} else {
			$this->data['approval'] = '';
		}	
					
		if ($this->request->hasPost('company_id_display')) {
			$this->data['company_id_display'] = $this->request->getPostE('company_id_display');
		} elseif (!empty($customer_group_info)) {
			$this->data['company_id_display'] = $customer_group_info['company_id_display'];
		} else {
			$this->data['company_id_display'] = '';
		}			
			
		if ($this->request->hasPost('company_id_required')) {
			$this->data['company_id_required'] = $this->request->getPostE('company_id_required');
		} elseif (!empty($customer_group_info)) {
			$this->data['company_id_required'] = $customer_group_info['company_id_required'];
		} else {
			$this->data['company_id_required'] = '';
		}		
		
		if ($this->request->hasPost('tax_id_display')) {
			$this->data['tax_id_display'] = $this->request->getPostE('tax_id_display');
		} elseif (!empty($customer_group_info)) {
			$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
		} else {
			$this->data['tax_id_display'] = '';
		}			
			
		if ($this->request->hasPost('tax_id_required')) {
			$this->data['tax_id_required'] = $this->request->getPostE('tax_id_required');
		} elseif (!empty($customer_group_info)) {
			$this->data['tax_id_required'] = $customer_group_info['tax_id_required'];
		} else {
			$this->data['tax_id_required'] = '';
		}	
		
		if ($this->request->hasPost('sort_order')) {
			$this->data['sort_order'] = $this->request->getPostE('sort_order');
		} elseif (!empty($customer_group_info)) {
			$this->data['sort_order'] = $customer_group_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}	
									
		$this->view->pick('sale/customer_group_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data); 
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->getPostE('customer_group_description') as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
      	
		foreach ($this->request->getPostE('selected') as $customer_group_id) {
    		if ($this->config->get('config_customer_group_id') == $customer_group_id) {
	  			$this->error['warning'] = $this->language->get('error_default');	
			}  
			
			$store_total = $this->model_setting_store->getTotalStoresByCustomerGroupId($customer_group_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
			
			$customer_total = $this->model_sale_customer->getTotalCustomersByCustomerGroupId($customer_group_id);

			if ($customer_total) {
				$this->error['warning'] = sprintf($this->language->get('error_customer'), $customer_total);
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
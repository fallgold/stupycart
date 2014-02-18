<?php

namespace Stupycart\Admin\Controllers\Design;

class CustomFieldController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();  
 
	public function indexAction() {
		$this->language->load('design/custom_field');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_custom_field = new \Stupycart\Common\Models\Admin\Design\CustomField();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('design/custom_field');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_custom_field = new \Stupycart\Common\Models\Admin\Design\CustomField();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_design_custom_field->addCustomField($this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('design/custom_field', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('design/custom_field');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_custom_field = new \Stupycart\Common\Models\Admin\Design\CustomField();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_design_custom_field->editCustomField($this->request->getQueryE('custom_field_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('design/custom_field', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('design/custom_field');

		$this->document->setTitle($this->language->get('heading_title'));
 		
		$this->model_design_custom_field = new \Stupycart\Common\Models\Admin\Design\CustomField();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $custom_field_id) {
				$this->model_design_custom_field->deleteCustomField($custom_field_id);
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
			
			$this->response->redirect($this->url->link('design/custom_field', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'cfd.name';
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
			'href'      => $this->url->link('design/custom_field', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('design/custom_field/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('design/custom_field/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		 
		$this->data['custom_fields'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$custom_field_total = $this->model_design_custom_field->getTotalCustomFields();
		
		$results = $this->model_design_custom_field->getCustomFields($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('design/custom_field/update', 'token=' . $this->session->get('token') . '&custom_field_id=' . $result['custom_field_id'] . $url, 'SSL')
			);
			
			$type = '';
			
			switch ($result['type']) {
				case 'select':
					$type = $this->language->get('text_select');
					break;
				case 'radio':
					$type = $this->language->get('text_radio');
					break;
				case 'checkbox':
					$type = $this->language->get('text_checkbox');
					break;
				case 'input':
					$type = $this->language->get('text_input');
					break;
				case 'text':
					$type = $this->language->get('text_text');
					break;
				case 'textarea':
					$type = $this->language->get('text_textarea');
					break;
				case 'file':
					$type = $this->language->get('text_file');
					break;
				case 'date':
					$type = $this->language->get('text_date');
					break;																														
				case 'datetime':
					$type = $this->language->get('text_datetime');
					break;	
				case 'time':
					$type = $this->language->get('text_time');
					break;																	
			}
			
			$location = '';
			
			switch ($result['location']) {
				case 'customer':
					$location = $this->language->get('text_customer');
					break;
				case 'address':
					$location = $this->language->get('text_address');
					break;
				case 'payment_address':
					$location = $this->language->get('text_payment_address');
					break;
				case 'shipping_address':
					$location = $this->language->get('text_shipping_address');
					break;										
			}			
		
			$this->data['custom_fields'][] = array(
				'custom_field_id' => $result['custom_field_id'],
				'name'            => $result['name'],
				'type'            => $type,
				'location'        => $location,
				'sort_order'      => $result['sort_order'],
				'selected'        => $this->request->hasPost('selected') && in_array($result['custom_field_id'], $this->request->getPostE('selected')),
				'action'          => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_location'] = $this->language->get('column_location');
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
		
		$this->data['sort_name'] = $this->url->link('design/custom_field', 'token=' . $this->session->get('token') . '&sort=cfd.name' . $url, 'SSL');
		$this->data['sort_type'] = $this->url->link('design/custom_field', 'token=' . $this->session->get('token') . '&sort=cf.type' . $url, 'SSL');
		$this->data['sort_location'] = $this->url->link('design/custom_field', 'token=' . $this->session->get('token') . '&sort=cf.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('design/custom_field', 'token=' . $this->session->get('token') . '&sort=cf.sort_order' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $custom_field_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('design/custom_field', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('design/custom_field_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_choose'] = $this->language->get('text_choose');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_radio'] = $this->language->get('text_radio');
		$this->data['text_checkbox'] = $this->language->get('text_checkbox');
		$this->data['text_input'] = $this->language->get('text_input');
		$this->data['text_text'] = $this->language->get('text_text');
		$this->data['text_textarea'] = $this->language->get('text_textarea');
		$this->data['text_file'] = $this->language->get('text_file');
		$this->data['text_date'] = $this->language->get('text_date');
		$this->data['text_datetime'] = $this->language->get('text_datetime');
		$this->data['text_time'] = $this->language->get('text_time');
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_payment_address'] = $this->language->get('text_payment_address');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_value'] = $this->language->get('entry_value');
		$this->data['entry_custom_value'] = $this->language->get('entry_custom_value');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_custom_field_value'] = $this->language->get('button_add_custom_field_value');
		$this->data['button_remove'] = $this->language->get('button_remove');

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
				
 		if (isset($this->error['custom_field_value'])) {
			$this->data['error_custom_field_value'] = $this->error['custom_field_value'];
		} else {
			$this->data['error_custom_field_value'] = array();
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
			'href'      => $this->url->link('design/custom_field', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('custom_field_id')) {
			$this->data['action'] = $this->url->link('design/custom_field/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else { 
			$this->data['action'] = $this->url->link('design/custom_field/update', 'token=' . $this->session->get('token') . '&custom_field_id=' . $this->request->getQueryE('custom_field_id') . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('design/custom_field', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('custom_field_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$custom_field_info = $this->model_design_custom_field->getCustomField($this->request->getQueryE('custom_field_id'));
    	}
		
		$this->data['token'] = $this->session->get('token');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('custom_field_description')) {
			$this->data['custom_field_description'] = $this->request->getPostE('custom_field_description');
		} elseif ($this->request->hasQuery('custom_field_id')) {
			$this->data['custom_field_description'] = $this->model_design_custom_field->getCustomFieldDescriptions($this->request->getQueryE('custom_field_id'));
		} else {
			$this->data['custom_field_description'] = array();
		}	
		
		if ($this->request->hasPost('type')) {
			$this->data['type'] = $this->request->getPostE('type');
		} elseif (!empty($custom_field_info)) {
			$this->data['type'] = $custom_field_info['type'];
		} else {
			$this->data['type'] = '';
		}
		
		if ($this->request->hasPost('value')) {
			$this->data['value'] = $this->request->getPostE('value');
		} elseif (!empty($custom_field_info)) {
			$this->data['value'] = $custom_field_info['value'];
		} else {
			$this->data['value'] = '';
		}
		
		if ($this->request->hasPost('required')) {
			$this->data['required'] = $this->request->getPostE('required');
		} elseif (!empty($custom_field_info)) {
			$this->data['required'] = $custom_field_info['required'];
		} else {
			$this->data['required'] = '';
		}
						
		if ($this->request->hasPost('location')) {
			$this->data['location'] = $this->request->getPostE('location');
		} elseif (!empty($custom_field_info)) {
			$this->data['location'] = $custom_field_info['location'];
		} else {
			$this->data['location'] = '';
		}
		
		if ($this->request->hasPost('position')) {
			$this->data['position'] = $this->request->getPostE('position');
		} elseif (!empty($custom_field_info)) {
			$this->data['position'] = $custom_field_info['position'];
		} else {
			$this->data['position'] = '';
		}	
			
		if ($this->request->hasPost('sort_order')) {
			$this->data['sort_order'] = $this->request->getPostE('sort_order');
		} elseif (!empty($custom_field_info)) {
			$this->data['sort_order'] = $custom_field_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		if ($this->request->hasPost('custom_field_value')) {
			$custom_field_values = $this->request->getPostE('custom_field_value');
		} elseif ($this->request->hasQuery('custom_field_id')) {
			$custom_field_values = $this->model_design_custom_field->getCustomFieldValueDescriptions($this->request->getQueryE('custom_field_id'));
		} else {
			$custom_field_values = array();
		}
		
		$this->data['custom_field_values'] = array();
		 
		foreach ($custom_field_values as $custom_field_value) {
			$this->data['custom_field_values'][] = array(
				'custom_field_value_id'          => $custom_field_value['custom_field_value_id'],
				'custom_field_value_description' => $custom_field_value['custom_field_value_description'],
				'sort_order'                     => $custom_field_value['sort_order']
			);
		}

		$this->view->pick('design/custom_field_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/custom_field')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->getPostE('custom_field_description') as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 128)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if (($this->request->getPostE('type') == 'select' || $this->request->getPostE('type') == 'radio' || $this->request->getPostE('type') == 'checkbox') && !$this->request->hasPost('custom_field_value')) {
			$this->error['warning'] = $this->language->get('error_type');
		}

		if ($this->request->hasPost('custom_field_value')) {
			foreach ($this->request->getPostE('custom_field_value') as $custom_field_value_id => $custom_field_value) {
				foreach ($custom_field_value['custom_field_value_description'] as $language_id => $custom_field_value_description) {
					if ((utf8_strlen($custom_field_value_description['name']) < 1) || (utf8_strlen($custom_field_value_description['name']) > 128)) {
						$this->error['custom_field_value'][$custom_field_value_id][$language_id] = $this->language->get('error_custom_field_value'); 
					}					
				}
			}	
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/custom_field')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->model_sale_product = new \Stupycart\Common\Models\Admin\Sale\Product();
		
		foreach ($this->request->getPostE('selected') as $custom_field_id) {
			$product_total = $this->model_sale_product->getTotalProductsByCustomFieldId($custom_field_id);

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
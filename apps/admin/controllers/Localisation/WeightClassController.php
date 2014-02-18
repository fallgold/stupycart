<?php

namespace Stupycart\Admin\Controllers\Localisation;

class WeightClassController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();  
 
	public function indexAction() {
		$this->language->load('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_weight_class = new \Stupycart\Common\Models\Admin\Localisation\WeightClass();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_weight_class = new \Stupycart\Common\Models\Admin\Localisation\WeightClass();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_weight_class->addWeightClass($this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_weight_class = new \Stupycart\Common\Models\Admin\Localisation\WeightClass();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_weight_class->editWeightClass($this->request->getQueryE('weight_class_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));
 		
		$this->model_localisation_weight_class = new \Stupycart\Common\Models\Admin\Localisation\WeightClass();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $weight_class_id) {
				$this->model_localisation_weight_class->deleteWeightClass($weight_class_id);
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
			
			$this->response->redirect($this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
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
			'href'      => $this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('localisation/weight_class/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/weight_class/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		 
		$this->data['weight_classes'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$weight_class_total = $this->model_localisation_weight_class->getTotalWeightClasses();
		
		$results = $this->model_localisation_weight_class->getWeightClasses($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/weight_class/update', 'token=' . $this->session->get('token') . '&weight_class_id=' . $result['weight_class_id'] . $url, 'SSL')
			);

			$this->data['weight_classes'][] = array(
				'weight_class_id' => $result['weight_class_id'],
				'title'           => $result['title'] . (($result['unit'] == $this->config->get('config_weight_class')) ? $this->language->get('text_default') : null),
				'unit'            => $result['unit'],
				'value'           => $result['value'],
				'selected'        => $this->request->hasPost('selected') && in_array($result['weight_class_id'], $this->request->getPostE('selected')),
				'action'          => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_unit'] = $this->language->get('column_unit');
		$this->data['column_value'] = $this->language->get('column_value');
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
		
		$this->data['sort_title'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . '&sort=title' . $url, 'SSL');
		$this->data['sort_unit'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . '&sort=unit' . $url, 'SSL');
		$this->data['sort_value'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . '&sort=value' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $weight_class_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/weight_class_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_unit'] = $this->language->get('entry_unit');
		$this->data['entry_value'] = $this->language->get('entry_value');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

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
		
 		if (isset($this->error['unit'])) {
			$this->data['error_unit'] = $this->error['unit'];
		} else {
			$this->data['error_unit'] = array();
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
			'href'      => $this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('weight_class_id')) {
			$this->data['action'] = $this->url->link('localisation/weight_class/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else { 
			$this->data['action'] = $this->url->link('localisation/weight_class/update', 'token=' . $this->session->get('token') . '&weight_class_id=' . $this->request->getQueryE('weight_class_id') . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('weight_class_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$weight_class_info = $this->model_localisation_weight_class->getWeightClass($this->request->getQueryE('weight_class_id'));
    	}
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('weight_class_description')) {
			$this->data['weight_class_description'] = $this->request->getPostE('weight_class_description');
		} elseif ($this->request->hasQuery('weight_class_id')) {
			$this->data['weight_class_description'] = $this->model_localisation_weight_class->getWeightClassDescriptions($this->request->getQueryE('weight_class_id'));
		} else {
			$this->data['weight_class_description'] = array();
		}	

		if ($this->request->hasPost('value')) {
			$this->data['value'] = $this->request->getPostE('value');
		} elseif (!empty($weight_class_info)) {
			$this->data['value'] = $weight_class_info['value'];
		} else {
			$this->data['value'] = '';
		}

		$this->view->pick('localisation/weight_class_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/weight_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->getPostE('weight_class_description') as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (!$value['unit'] || (utf8_strlen($value['unit']) > 4)) {
				$this->error['unit'][$language_id] = $this->language->get('error_unit');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/weight_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		
		foreach ($this->request->getPostE('selected') as $weight_class_id) {
			if ($this->config->get('config_weight_class_id') == $weight_class_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}
			
			$product_total = $this->model_catalog_product->getTotalProductsByWeightClassId($weight_class_id);

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
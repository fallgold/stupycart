<?php

namespace Stupycart\Admin\Controllers\Localisation;

class TaxRateController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
 
	public function indexAction() {
		$this->language->load('localisation/tax_rate');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_tax_rate = new \Stupycart\Common\Models\Admin\Localisation\TaxRate();
		
		$this->getList(); 
	}

	public function insertAction() {
		$this->language->load('localisation/tax_rate');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_tax_rate = new \Stupycart\Common\Models\Admin\Localisation\TaxRate();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_tax_rate->addTaxRate($this->request->getPostE());

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
			
			$this->response->redirect($this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('localisation/tax_rate');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_tax_rate = new \Stupycart\Common\Models\Admin\Localisation\TaxRate();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_tax_rate->editTaxRate($this->request->getQueryE('tax_rate_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('localisation/tax_rate');

		$this->document->setTitle($this->language->get('heading_title'));
 		
		$this->model_localisation_tax_rate = new \Stupycart\Common\Models\Admin\Localisation\TaxRate();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $tax_rate_id) {
				$this->model_localisation_tax_rate->deleteTaxRate($tax_rate_id);
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
			
			$this->response->redirect($this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'tr.name';
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
			'href'      => $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->data['insert'] = $this->url->link('localisation/tax_rate/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/tax_rate/delete', 'token=' . $this->session->get('token') . $url, 'SSL');		
		
		$this->data['tax_rates'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRates();

		$results = $this->model_localisation_tax_rate->getTaxRates($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/tax_rate/update', 'token=' . $this->session->get('token') . '&tax_rate_id=' . $result['tax_rate_id'] . $url, 'SSL')
			);
											
			$this->data['tax_rates'][] = array(
				'tax_rate_id'   => $result['tax_rate_id'],
				'name'          => $result['name'],
				'rate'          => $result['rate'],
				'type'          => ($result['type'] == 'F' ? $this->language->get('text_amount') : $this->language->get('text_percent')),				
				'geo_zone'      => $result['geo_zone'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => $this->request->hasPost('selected') && in_array($result['tax_rate_id'], $this->request->getPostE('selected')),
				'action'        => $action				
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
	
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_rate'] = $this->language->get('column_rate');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_geo_zone'] = $this->language->get('column_geo_zone');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
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
		 
		$this->data['sort_name'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . '&sort=tr.name' . $url, 'SSL');
		$this->data['sort_rate'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . '&sort=tr.rate' . $url, 'SSL');
		$this->data['sort_type'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . '&sort=tr.type' . $url, 'SSL');
		$this->data['sort_geo_zone'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . '&sort=gz.name' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . '&sort=tr.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . '&sort=tr.date_modified' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $tax_rate_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/tax_rate_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_percent'] = $this->language->get('text_percent');	
		$this->data['text_amount'] = $this->language->get('text_amount');	
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_type'] = $this->language->get('entry_type');		
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		
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
			$this->data['error_name'] = '';
		}

 		if (isset($this->error['rate'])) {
			$this->data['error_rate'] = $this->error['rate'];
		} else {
			$this->data['error_rate'] = '';
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
			'href'      => $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('tax_rate_id')) {
			$this->data['action'] = $this->url->link('localisation/tax_rate/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/tax_rate/update', 'token=' . $this->session->get('token') . '&tax_rate_id=' . $this->request->getQueryE('tax_rate_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('tax_rate_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($this->request->getQueryE('tax_rate_id'));
		}

		if ($this->request->hasPost('name')) {
			$this->data['name'] = $this->request->getPostE('name');
		} elseif (!empty($tax_rate_info)) {
			$this->data['name'] = $tax_rate_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if ($this->request->hasPost('rate')) {
			$this->data['rate'] = $this->request->getPostE('rate');
		} elseif (!empty($tax_rate_info)) {
			$this->data['rate'] = $tax_rate_info['rate'];
		} else {
			$this->data['rate'] = '';
		}
		
		if ($this->request->hasPost('type')) {
			$this->data['type'] = $this->request->getPostE('type');
		} elseif (!empty($tax_rate_info)) {
			$this->data['type'] = $tax_rate_info['type'];
		} else {
			$this->data['type'] = '';
		}
		
		if ($this->request->hasPost('tax_rate_customer_group')) {
			$this->data['tax_rate_customer_group'] = $this->request->getPostE('tax_rate_customer_group');
		} elseif ($this->request->hasQuery('tax_rate_id')) {
			$this->data['tax_rate_customer_group'] = $this->model_localisation_tax_rate->getTaxRateCustomerGroups($this->request->getQueryE('tax_rate_id'));
		} else {
			$this->data['tax_rate_customer_group'] = array();
		}	
		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
						
		if ($this->request->hasPost('geo_zone_id')) {
			$this->data['geo_zone_id'] = $this->request->getPostE('geo_zone_id');
		} elseif (!empty($tax_rate_info)) {
			$this->data['geo_zone_id'] = $tax_rate_info['geo_zone_id'];
		} else {
			$this->data['geo_zone_id'] = '';
		}
				
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
				
		$this->view->pick('localisation/tax_rate_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_rate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->getPostE('name')) < 3) || (utf8_strlen($this->request->getPostE('name')) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->getPostE('rate')) {
			$this->error['rate'] = $this->language->get('error_rate');
		}
								
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/tax_rate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();

		foreach ($this->request->getPostE('selected') as $tax_rate_id) {
			$tax_rule_total = $this->model_localisation_tax_class->getTotalTaxRulesByTaxRateId($tax_rate_id);

			if ($tax_rule_total) {
				$this->error['warning'] = sprintf($this->language->get('error_tax_rule'), $tax_rule_total);
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
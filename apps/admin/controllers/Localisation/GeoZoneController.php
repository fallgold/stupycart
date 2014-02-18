<?php

namespace Stupycart\Admin\Controllers\Localisation;

class GeoZoneController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
 
	public function indexAction() {
		$this->language->load('localisation/geo_zone');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('localisation/geo_zone');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_geo_zone->addGeoZone($this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('localisation/geo_zone');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_geo_zone->editGeoZone($this->request->getQueryE('geo_zone_id'), $this->request->getPostE());

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
			
			$this->response->redirect($this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('localisation/geo_zone');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $geo_zone_id) {
				$this->model_localisation_geo_zone->deleteGeoZone($geo_zone_id);
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
			
			$this->response->redirect($this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
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
			'href'      => $this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('localisation/geo_zone/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/geo_zone/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		$this->data['geo_zones'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$geo_zone_total = $this->model_localisation_geo_zone->getTotalGeoZones();
		
		$results = $this->model_localisation_geo_zone->getGeoZones($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/geo_zone/update', 'token=' . $this->session->get('token') . '&geo_zone_id=' . $result['geo_zone_id'] . $url, 'SSL')
			);
					
			$this->data['geo_zones'][] = array(
				'geo_zone_id' => $result['geo_zone_id'],
				'name'        => $result['name'],
				'description' => $result['description'],
				'selected'    => $this->request->hasPost('selected') && in_array($result['geo_zone_id'], $this->request->getPostE('selected')),
				'action'      => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
	
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_description'] = $this->language->get('column_description');
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
		 
		$this->data['sort_name'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		$this->data['sort_description'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . '&sort=description' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $geo_zone_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/geo_zone_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_geo_zone'] = $this->language->get('button_add_geo_zone');
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
			'href'      => $this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
				
		if (!$this->request->hasQuery('geo_zone_id')) {
			$this->data['action'] = $this->url->link('localisation/geo_zone/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/geo_zone/update', 'token=' . $this->session->get('token') . '&geo_zone_id=' . $this->request->getQueryE('geo_zone_id') . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('geo_zone_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($this->request->getQueryE('geo_zone_id'));
		}
		
		$this->data['token'] = $this->session->get('token');
		
		if ($this->request->hasPost('name')) {
			$this->data['name'] = $this->request->getPostE('name');
		} elseif (!empty($geo_zone_info)) {
			$this->data['name'] = $geo_zone_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if ($this->request->hasPost('description')) {
			$this->data['description'] = $this->request->getPostE('description');
		} elseif (!empty($geo_zone_info)) {
			$this->data['description'] = $geo_zone_info['description'];
		} else {
			$this->data['description'] = '';
		}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Admin\Localisation\Country();
		 
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		if ($this->request->hasPost('zone_to_geo_zone')) {
			$this->data['zone_to_geo_zones'] = $this->request->getPostE('zone_to_geo_zone');
		} elseif ($this->request->hasQuery('geo_zone_id')) {
			$this->data['zone_to_geo_zones'] = $this->model_localisation_geo_zone->getZoneToGeoZones($this->request->getQueryE('geo_zone_id'));
		} else {
			$this->data['zone_to_geo_zones'] = array();
		}

		$this->view->pick('localisation/geo_zone_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->getPostE('name')) < 3) || (utf8_strlen($this->request->getPostE('name')) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
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
		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->model_localisation_tax_rate = new \Stupycart\Common\Models\Admin\Localisation\TaxRate();

		foreach ($this->request->getPostE('selected') as $geo_zone_id) {
			$tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRatesByGeoZoneId($geo_zone_id);

			if ($tax_rate_total) {
				$this->error['warning'] = sprintf($this->language->get('error_tax_rate'), $tax_rate_total);
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function zoneAction() {
		$output = '<option value="0">' . $this->language->get('text_all_zones') . '</option>';
		
		$this->model_localisation_zone = new \Stupycart\Common\Models\Admin\Localisation\Zone();
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->getQueryE('country_id'));

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if ($this->request->getQueryE('zone_id') == $result['zone_id']) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		$this->response->setContent($output);
		return $this->response;
	} 		
}
?>
<?php 

namespace Stupycart\Admin\Controllers\Localisation;

class ZoneController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {
		$this->language->load('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_zone = new \Stupycart\Common\Models\Admin\Localisation\Zone();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_zone = new \Stupycart\Common\Models\Admin\Localisation\Zone();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_zone->addZone($this->request->getPostE());
	
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
			
			$this->response->redirect($this->url->link('localisation/zone', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_zone = new \Stupycart\Common\Models\Admin\Localisation\Zone();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_zone->editZone($this->request->getQueryE('zone_id'), $this->request->getPostE());			
			
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
			
			$this->response->redirect($this->url->link('localisation/zone', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_zone = new \Stupycart\Common\Models\Admin\Localisation\Zone();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $zone_id) {
				$this->model_localisation_zone->deleteZone($zone_id);
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

			$this->response->redirect($this->url->link('localisation/zone', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'c.name';
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
			'href'      => $this->url->link('localisation/zone', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('localisation/zone/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/zone/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
	
		$this->data['zones'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$zone_total = $this->model_localisation_zone->getTotalZones();
			
		$results = $this->model_localisation_zone->getZones($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/zone/update', 'token=' . $this->session->get('token') . '&zone_id=' . $result['zone_id'] . $url, 'SSL')
			);
					
			$this->data['zones'][] = array(
				'zone_id'  => $result['zone_id'],
				'country'  => $result['country'],
				'name'     => $result['name'] . (($result['zone_id'] == $this->config->get('config_zone_id')) ? $this->language->get('text_default') : null),
				'code'     => $result['code'],
				'selected' => $this->request->hasPost('selected') && in_array($result['zone_id'], $this->request->getPostE('selected')),
				'action'   => $action			
			);
		}
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_country'] = $this->language->get('column_country');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_code'] = $this->language->get('column_code');
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
		 
		$this->data['sort_country'] = $this->url->link('localisation/zone', 'token=' . $this->session->get('token') . '&sort=c.name' . $url, 'SSL');
		$this->data['sort_name'] = $this->url->link('localisation/zone', 'token=' . $this->session->get('token') . '&sort=z.name' . $url, 'SSL');
		$this->data['sort_code'] = $this->url->link('localisation/zone', 'token=' . $this->session->get('token') . '&sort=z.code' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $zone_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/zone', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/zone_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_country'] = $this->language->get('entry_country');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
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
			'href'      => $this->url->link('localisation/zone', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!$this->request->hasQuery('zone_id')) {
			$this->data['action'] = $this->url->link('localisation/zone/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/zone/update', 'token=' . $this->session->get('token') . '&zone_id=' . $this->request->getQueryE('zone_id') . $url, 'SSL');
		}
		 
		$this->data['cancel'] = $this->url->link('localisation/zone', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('zone_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$zone_info = $this->model_localisation_zone->getZone($this->request->getQueryE('zone_id'));
		}

		if ($this->request->hasPost('status')) {
			$this->data['status'] = $this->request->getPostE('status');
		} elseif (!empty($zone_info)) {
			$this->data['status'] = $zone_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if ($this->request->hasPost('name')) {
			$this->data['name'] = $this->request->getPostE('name');
		} elseif (!empty($zone_info)) {
			$this->data['name'] = $zone_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if ($this->request->hasPost('code')) {
			$this->data['code'] = $this->request->getPostE('code');
		} elseif (!empty($zone_info)) {
			$this->data['code'] = $zone_info['code'];
		} else {
			$this->data['code'] = '';
		}

		if ($this->request->hasPost('country_id')) {
			$this->data['country_id'] = $this->request->getPostE('country_id');
		} elseif (!empty($zone_info)) {
			$this->data['country_id'] = $zone_info['country_id'];
		} else {
			$this->data['country_id'] = '';
		}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Admin\Localisation\Country();
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->view->pick('localisation/zone_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/zone')) {
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
		if (!$this->user->hasPermission('modify', 'localisation/zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
		$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		foreach ($this->request->getPostE('selected') as $zone_id) {
			if ($this->config->get('config_zone_id') == $zone_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}
			
			$store_total = $this->model_setting_store->getTotalStoresByZoneId($zone_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
		
			$address_total = $this->model_sale_customer->getTotalAddressesByZoneId($zone_id);

			if ($address_total) {
				$this->error['warning'] = sprintf($this->language->get('error_address'), $address_total);
			}

			$affiliate_total = $this->model_sale_affiliate->getTotalAffiliatesByZoneId($zone_id);

			if ($affiliate_total) {
				$this->error['warning'] = sprintf($this->language->get('error_affiliate'), $affiliate_total);
			}
					
			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByZoneId($zone_id);
		
			if ($zone_to_geo_zone_total) {
				$this->error['warning'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
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
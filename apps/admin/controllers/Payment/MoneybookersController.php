<?php

namespace Stupycart\Admin\Controllers\Payment;

class MoneyBookersController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	
	public function indexAction() {
		$this->language->load('payment/moneybookers');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('moneybookers', $this->request->getPostE());				
			
			$this->session->set('success', $this->language->get('text_success'));
		
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
				
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
		$this->data['entry_pending_status'] = $this->language->get('entry_pending_status');	
		$this->data['entry_canceled_status'] = $this->language->get('entry_canceled_status');	
		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');	
		$this->data['entry_chargeback_status'] = $this->language->get('entry_chargeback_status');	
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_mb_id'] = $this->language->get('entry_mb_id');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_custnote'] = $this->language->get('entry_custnote');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
 		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'),      		
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/moneybookers', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/moneybookers', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');
		
		if ($this->request->hasPost('moneybookers_email')) {
			$this->data['moneybookers_email'] = $this->request->getPostE('moneybookers_email');
		} else {
			$this->data['moneybookers_email'] = $this->config->get('moneybookers_email');
		}
		
		if ($this->request->hasPost('moneybookers_secret')) {
			$this->data['moneybookers_secret'] = $this->request->getPostE('moneybookers_secret');
		} else {
			$this->data['moneybookers_secret'] = $this->config->get('moneybookers_secret');
		}
		
		if ($this->request->hasPost('moneybookers_total')) {
			$this->data['moneybookers_total'] = $this->request->getPostE('moneybookers_total');
		} else {
			$this->data['moneybookers_total'] = $this->config->get('moneybookers_total'); 
		} 
				
		if ($this->request->hasPost('moneybookers_order_status_id')) {
			$this->data['moneybookers_order_status_id'] = $this->request->getPostE('moneybookers_order_status_id');
		} else {
			$this->data['moneybookers_order_status_id'] = $this->config->get('moneybookers_order_status_id'); 
		} 

		if ($this->request->hasPost('moneybookers_pending_status_id')) {
			$this->data['moneybookers_pending_status_id'] = $this->request->getPostE('moneybookers_pending_status_id');
		} else {
			$this->data['moneybookers_pending_status_id'] = $this->config->get('moneybookers_pending_status_id');
		}

		if ($this->request->hasPost('moneybookers_canceled_status_id')) {
			$this->data['moneybookers_canceled_status_id'] = $this->request->getPostE('moneybookers_canceled_status_id');
		} else {
			$this->data['moneybookers_canceled_status_id'] = $this->config->get('moneybookers_canceled_status_id');
		}

		if ($this->request->hasPost('moneybookers_failed_status_id')) {
			$this->data['moneybookers_failed_status_id'] = $this->request->getPostE('moneybookers_failed_status_id');
		} else {
			$this->data['moneybookers_failed_status_id'] = $this->config->get('moneybookers_failed_status_id');
		}

		if ($this->request->hasPost('moneybookers_chargeback_status_id')) {
			$this->data['moneybookers_chargeback_status_id'] = $this->request->getPostE('moneybookers_chargeback_status_id');
		} else {
			$this->data['moneybookers_chargeback_status_id'] = $this->config->get('moneybookers_chargeback_status_id');
		}
		
		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if ($this->request->hasPost('moneybookers_geo_zone_id')) {
			$this->data['moneybookers_geo_zone_id'] = $this->request->getPostE('moneybookers_geo_zone_id');
		} else {
			$this->data['moneybookers_geo_zone_id'] = $this->config->get('moneybookers_geo_zone_id'); 
		} 	
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('moneybookers_status')) {
			$this->data['moneybookers_status'] = $this->request->getPostE('moneybookers_status');
		} else {
			$this->data['moneybookers_status'] = $this->config->get('moneybookers_status');
		}
		
		if ($this->request->hasPost('moneybookers_sort_order')) {
			$this->data['moneybookers_sort_order'] = $this->request->getPostE('moneybookers_sort_order');
		} else {
			$this->data['moneybookers_sort_order'] = $this->config->get('moneybookers_sort_order');
		}
		
		if ($this->request->hasPost('moneybookers_rid')) {
			$this->data['moneybookers_rid'] = $this->request->getPostE('moneybookers_rid');
		} else {
			$this->data['moneybookers_rid'] = $this->config->get('moneybookers_rid');
		}
		
		if ($this->request->hasPost('moneybookers_custnote')) {
			$this->data['moneybookers_custnote'] = $this->request->getPostE('moneybookers_custnote');
		} else {
			$this->data['moneybookers_custnote'] = $this->config->get('moneybookers_custnote');
		}

		$this->view->pick('payment/moneybookers');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/moneybookers')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->getPostE('moneybookers_email')) {
			$this->error['email'] = $this->language->get('error_email');
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
<?php 

namespace Stupycart\Admin\Controllers\Payment;

class AuthorizenetAimController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {
		$this->language->load('payment/authorizenet_aim');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('authorizenet_aim', $this->request->getPostE());				
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_test'] = $this->language->get('text_test');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_capture'] = $this->language->get('text_capture');		
		
		$this->data['entry_login'] = $this->language->get('entry_login');
		$this->data['entry_key'] = $this->language->get('entry_key');
		$this->data['entry_hash'] = $this->language->get('entry_hash');
		$this->data['entry_server'] = $this->language->get('entry_server');
		$this->data['entry_mode'] = $this->language->get('entry_mode');
		$this->data['entry_method'] = $this->language->get('entry_method');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['login'])) {
			$this->data['error_login'] = $this->error['login'];
		} else {
			$this->data['error_login'] = '';
		}

 		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = '';
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
			'href'      => $this->url->link('payment/authorizenet_aim', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/authorizenet_aim', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');
		
		if ($this->request->hasPost('authorizenet_aim_login')) {
			$this->data['authorizenet_aim_login'] = $this->request->getPostE('authorizenet_aim_login');
		} else {
			$this->data['authorizenet_aim_login'] = $this->config->get('authorizenet_aim_login');
		}
	
		if ($this->request->hasPost('authorizenet_aim_key')) {
			$this->data['authorizenet_aim_key'] = $this->request->getPostE('authorizenet_aim_key');
		} else {
			$this->data['authorizenet_aim_key'] = $this->config->get('authorizenet_aim_key');
		}
		
		if ($this->request->hasPost('authorizenet_aim_hash')) {
			$this->data['authorizenet_aim_hash'] = $this->request->getPostE('authorizenet_aim_hash');
		} else {
			$this->data['authorizenet_aim_hash'] = $this->config->get('authorizenet_aim_hash');
		}

		if ($this->request->hasPost('authorizenet_aim_server')) {
			$this->data['authorizenet_aim_server'] = $this->request->getPostE('authorizenet_aim_server');
		} else {
			$this->data['authorizenet_aim_server'] = $this->config->get('authorizenet_aim_server');
		}
		
		if ($this->request->hasPost('authorizenet_aim_mode')) {
			$this->data['authorizenet_aim_mode'] = $this->request->getPostE('authorizenet_aim_mode');
		} else {
			$this->data['authorizenet_aim_mode'] = $this->config->get('authorizenet_aim_mode');
		}
		
		if ($this->request->hasPost('authorizenet_aim_method')) {
			$this->data['authorizenet_aim_method'] = $this->request->getPostE('authorizenet_aim_method');
		} else {
			$this->data['authorizenet_aim_method'] = $this->config->get('authorizenet_aim_method');
		}
		
		if ($this->request->hasPost('authorizenet_aim_total')) {
			$this->data['authorizenet_aim_total'] = $this->request->getPostE('authorizenet_aim_total');
		} else {
			$this->data['authorizenet_aim_total'] = $this->config->get('authorizenet_aim_total'); 
		} 
				
		if ($this->request->hasPost('authorizenet_aim_order_status_id')) {
			$this->data['authorizenet_aim_order_status_id'] = $this->request->getPostE('authorizenet_aim_order_status_id');
		} else {
			$this->data['authorizenet_aim_order_status_id'] = $this->config->get('authorizenet_aim_order_status_id'); 
		} 

		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if ($this->request->hasPost('authorizenet_aim_geo_zone_id')) {
			$this->data['authorizenet_aim_geo_zone_id'] = $this->request->getPostE('authorizenet_aim_geo_zone_id');
		} else {
			$this->data['authorizenet_aim_geo_zone_id'] = $this->config->get('authorizenet_aim_geo_zone_id'); 
		} 
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('authorizenet_aim_status')) {
			$this->data['authorizenet_aim_status'] = $this->request->getPostE('authorizenet_aim_status');
		} else {
			$this->data['authorizenet_aim_status'] = $this->config->get('authorizenet_aim_status');
		}
		
		if ($this->request->hasPost('authorizenet_aim_sort_order')) {
			$this->data['authorizenet_aim_sort_order'] = $this->request->getPostE('authorizenet_aim_sort_order');
		} else {
			$this->data['authorizenet_aim_sort_order'] = $this->config->get('authorizenet_aim_sort_order');
		}

		$this->view->pick('payment/authorizenet_aim');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/authorizenet_aim')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->getPostE('authorizenet_aim_login')) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->getPostE('authorizenet_aim_key')) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
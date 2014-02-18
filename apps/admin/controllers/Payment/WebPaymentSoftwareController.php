<?php 

namespace Stupycart\Admin\Controllers\Payment;

class WebPaymentSoftwareController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {
		$this->language->load('payment/web_payment_software');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('web_payment_software', $this->request->getPostE());				
			
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
			'href'      => $this->url->link('payment/web_payment_software', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = HTTPS_SERVER . 'payment/web_payment_software?token=' . $this->session->get('token');
		
		$this->data['cancel'] = HTTPS_SERVER . 'extension/payment?token=' . $this->session->get('token');
		
		if ($this->request->hasPost('web_payment_software_login')) {
			$this->data['web_payment_software_merchant_name'] = $this->request->getPostE('web_payment_software_merchant_name');
		} else {
			$this->data['web_payment_software_merchant_name'] = $this->config->get('web_payment_software_merchant_name');
		}
	
		if ($this->request->hasPost('web_payment_software_merchant_key')) {
			$this->data['web_payment_software_merchant_key'] = $this->request->getPostE('web_payment_software_merchant_key');
		} else {
			$this->data['web_payment_software_merchant_key'] = $this->config->get('web_payment_software_merchant_key');
		}
		
		if ($this->request->hasPost('web_payment_software_mode')) {
			$this->data['web_payment_software_mode'] = $this->request->getPostE('web_payment_software_mode');
		} else {
			$this->data['web_payment_software_mode'] = $this->config->get('web_payment_software_mode');
		}
		
		if ($this->request->hasPost('web_payment_software_method')) {
			$this->data['web_payment_software_method'] = $this->request->getPostE('web_payment_software_method');
		} else {
			$this->data['web_payment_software_method'] = $this->config->get('web_payment_software_method');
		}
		
		if ($this->request->hasPost('web_payment_software_order_status_id')) {
			$this->data['web_payment_software_order_status_id'] = $this->request->getPostE('web_payment_software_order_status_id');
		} else {
			$this->data['web_payment_software_order_status_id'] = $this->config->get('web_payment_software_order_status_id'); 
		} 

		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if ($this->request->hasPost('web_payment_software_geo_zone_id')) {
			$this->data['web_payment_software_geo_zone_id'] = $this->request->getPostE('web_payment_software_geo_zone_id');
		} else {
			$this->data['web_payment_software_geo_zone_id'] = $this->config->get('web_payment_software_geo_zone_id'); 
		} 
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('web_payment_software_status')) {
			$this->data['web_payment_software_status'] = $this->request->getPostE('web_payment_software_status');
		} else {
			$this->data['web_payment_software_status'] = $this->config->get('web_payment_software_status');
		}
		
		if ($this->request->hasPost('web_payment_software_total')) {
			$this->data['web_payment_software_total'] = $this->request->getPostE('web_payment_software_total');
		} else {
			$this->data['web_payment_software_total'] = $this->config->get('web_payment_software_total');
		}
		
		if ($this->request->hasPost('web_payment_software_sort_order')) {
			$this->data['web_payment_software_sort_order'] = $this->request->getPostE('web_payment_software_sort_order');
		} else {
			$this->data['web_payment_software_sort_order'] = $this->config->get('web_payment_software_sort_order');
		}

		$this->view->pick('payment/web_payment_software');
		$this->_commonAction();
		
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/web_payment_software')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->getPostE('web_payment_software_merchant_name')) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->getPostE('web_payment_software_merchant_key')) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
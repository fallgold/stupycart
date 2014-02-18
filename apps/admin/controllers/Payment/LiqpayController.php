<?php 

namespace Stupycart\Admin\Controllers\Payment;

class LiqPayController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {
		$this->language->load('payment/liqpay');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('liqpay', $this->request->getPostE());				
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_pay'] = $this->language->get('text_pay');
		$this->data['text_card'] = $this->language->get('text_card');
		
		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_signature'] = $this->language->get('entry_signature');
		$this->data['entry_type'] = $this->language->get('entry_type');				
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

		if (isset($this->error['merchant'])) { 
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}
		
		if (isset($this->error['signature'])) { 
			$this->data['error_signature'] = $this->error['signature'];
		} else {
			$this->data['error_signature'] = '';
		}
		
		if (isset($this->error['type'])) { 
			$this->data['error_type'] = $this->error['type'];
		} else {
			$this->data['error_type'] = '';
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
			'href'      => $this->url->link('payment/liqpay', 'token=' . $this->session->get('token'), 'SSL'),      		
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/liqpay', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');
		
		if ($this->request->hasPost('liqpay_merchant')) {
			$this->data['liqpay_merchant'] = $this->request->getPostE('liqpay_merchant');
		} else {
			$this->data['liqpay_merchant'] = $this->config->get('liqpay_merchant');
		}

		if ($this->request->hasPost('liqpay_signature')) {
			$this->data['liqpay_signature'] = $this->request->getPostE('liqpay_signature');
		} else {
			$this->data['liqpay_signature'] = $this->config->get('liqpay_signature');
		}

		if ($this->request->hasPost('liqpay_type')) {
			$this->data['liqpay_type'] = $this->request->getPostE('liqpay_type');
		} else {
			$this->data['liqpay_type'] = $this->config->get('liqpay_type');
		}
		
		if ($this->request->hasPost('liqpay_total')) {
			$this->data['liqpay_total'] = $this->request->getPostE('liqpay_total');
		} else {
			$this->data['liqpay_total'] = $this->config->get('liqpay_total'); 
		} 
				
		if ($this->request->hasPost('liqpay_order_status_id')) {
			$this->data['liqpay_order_status_id'] = $this->request->getPostE('liqpay_order_status_id');
		} else {
			$this->data['liqpay_order_status_id'] = $this->config->get('liqpay_order_status_id'); 
		} 

		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if ($this->request->hasPost('liqpay_geo_zone_id')) {
			$this->data['liqpay_geo_zone_id'] = $this->request->getPostE('liqpay_geo_zone_id');
		} else {
			$this->data['liqpay_geo_zone_id'] = $this->config->get('liqpay_geo_zone_id'); 
		} 		
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('liqpay_status')) {
			$this->data['liqpay_status'] = $this->request->getPostE('liqpay_status');
		} else {
			$this->data['liqpay_status'] = $this->config->get('liqpay_status');
		}
		
		if ($this->request->hasPost('liqpay_sort_order')) {
			$this->data['liqpay_sort_order'] = $this->request->getPostE('liqpay_sort_order');
		} else {
			$this->data['liqpay_sort_order'] = $this->config->get('liqpay_sort_order');
		}

		$this->view->pick('payment/liqpay');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/liqpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->getPostE('liqpay_merchant')) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->getPostE('liqpay_signature')) {
			$this->error['signature'] = $this->language->get('error_signature');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
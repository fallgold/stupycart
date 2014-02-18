<?php 

namespace Stupycart\Admin\Controllers\Payment;

class PPProController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {
		$this->language->load('payment/pp_pro');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_pro', $this->request->getPostE());				
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');
		
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_signature'] = $this->language->get('entry_signature');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
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

 		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}
		
 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['signature'])) {
			$this->data['error_signature'] = $this->error['signature'];
		} else {
			$this->data['error_signature'] = '';
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
			'href'      => $this->url->link('payment/pp_pro', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/pp_pro', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');

		if ($this->request->hasPost('pp_pro_username')) {
			$this->data['pp_pro_username'] = $this->request->getPostE('pp_pro_username');
		} else {
			$this->data['pp_pro_username'] = $this->config->get('pp_pro_username');
		}
		
		if ($this->request->hasPost('pp_pro_password')) {
			$this->data['pp_pro_password'] = $this->request->getPostE('pp_pro_password');
		} else {
			$this->data['pp_pro_password'] = $this->config->get('pp_pro_password');
		}
				
		if ($this->request->hasPost('pp_pro_signature')) {
			$this->data['pp_pro_signature'] = $this->request->getPostE('pp_pro_signature');
		} else {
			$this->data['pp_pro_signature'] = $this->config->get('pp_pro_signature');
		}
		
		if ($this->request->hasPost('pp_pro_test')) {
			$this->data['pp_pro_test'] = $this->request->getPostE('pp_pro_test');
		} else {
			$this->data['pp_pro_test'] = $this->config->get('pp_pro_test');
		}
		
		if ($this->request->hasPost('pp_pro_method')) {
			$this->data['pp_pro_transaction'] = $this->request->getPostE('pp_pro_transaction');
		} else {
			$this->data['pp_pro_transaction'] = $this->config->get('pp_pro_transaction');
		}
		
		if ($this->request->hasPost('pp_pro_total')) {
			$this->data['pp_pro_total'] = $this->request->getPostE('pp_pro_total');
		} else {
			$this->data['pp_pro_total'] = $this->config->get('pp_pro_total'); 
		} 
				
		if ($this->request->hasPost('pp_pro_order_status_id')) {
			$this->data['pp_pro_order_status_id'] = $this->request->getPostE('pp_pro_order_status_id');
		} else {
			$this->data['pp_pro_order_status_id'] = $this->config->get('pp_pro_order_status_id'); 
		} 

		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if ($this->request->hasPost('pp_pro_geo_zone_id')) {
			$this->data['pp_pro_geo_zone_id'] = $this->request->getPostE('pp_pro_geo_zone_id');
		} else {
			$this->data['pp_pro_geo_zone_id'] = $this->config->get('pp_pro_geo_zone_id'); 
		} 
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('pp_pro_status')) {
			$this->data['pp_pro_status'] = $this->request->getPostE('pp_pro_status');
		} else {
			$this->data['pp_pro_status'] = $this->config->get('pp_pro_status');
		}
		
		if ($this->request->hasPost('pp_pro_sort_order')) {
			$this->data['pp_pro_sort_order'] = $this->request->getPostE('pp_pro_sort_order');
		} else {
			$this->data['pp_pro_sort_order'] = $this->config->get('pp_pro_sort_order');
		}

		$this->view->pick('payment/pp_pro');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_pro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->getPostE('pp_pro_username')) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->getPostE('pp_pro_password')) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->getPostE('pp_pro_signature')) {
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
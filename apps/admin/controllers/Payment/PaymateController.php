<?php 

namespace Stupycart\Admin\Controllers\Payment;

class PayMateController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {
		$this->language->load('payment/paymate');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paymate', $this->request->getPostE());				
			
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
						
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_test'] = $this->language->get('entry_test');
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
			'href'      => $this->url->link('payment/paymate', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/paymate', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');
		
		if ($this->request->hasPost('paymate_username')) {
			$this->data['paymate_username'] = $this->request->getPostE('paymate_username');
		} else {
			$this->data['paymate_username'] = $this->config->get('paymate_username');
		}
		
		if ($this->request->hasPost('paymate_password')) {
			$this->data['paymate_username'] = $this->request->getPostE('paymate_username');
		} elseif ($this->config->get('paymate_password')) {
			$this->data['paymate_password'] = $this->config->get('paymate_password');
		} else {
			$this->data['paymate_password'] = md5(mt_rand());
		}
				
		if ($this->request->hasPost('paymate_test')) {
			$this->data['paymate_test'] = $this->request->getPostE('paymate_test');
		} else {
			$this->data['paymate_test'] = $this->config->get('paymate_test');
		}
				
		if ($this->request->hasPost('paymate_total')) {
			$this->data['paymate_total'] = $this->request->getPostE('paymate_total');
		} else {
			$this->data['paymate_total'] = $this->config->get('paymate_total'); 
		} 
				
		if ($this->request->hasPost('paymate_order_status_id')) {
			$this->data['paymate_order_status_id'] = $this->request->getPostE('paymate_order_status_id');
		} else {
			$this->data['paymate_order_status_id'] = $this->config->get('paymate_order_status_id'); 
		} 
		
		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if ($this->request->hasPost('paymate_geo_zone_id')) {
			$this->data['paymate_geo_zone_id'] = $this->request->getPostE('paymate_geo_zone_id');
		} else {
			$this->data['paymate_geo_zone_id'] = $this->config->get('paymate_geo_zone_id'); 
		} 
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('paymate_status')) {
			$this->data['paymate_status'] = $this->request->getPostE('paymate_status');
		} else {
			$this->data['paymate_status'] = $this->config->get('paymate_status');
		}
		
		if ($this->request->hasPost('paymate_sort_order')) {
			$this->data['paymate_sort_order'] = $this->request->getPostE('paymate_sort_order');
		} else {
			$this->data['paymate_sort_order'] = $this->config->get('paymate_sort_order');
		}

		$this->view->pick('payment/paymate');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paymate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->getPostE('paymate_username')) {
			$this->error['username'] = $this->language->get('error_username');
		}
		
		if (!$this->request->getPostE('paymate_password')) {
			$this->error['password'] = $this->language->get('error_password');
		}
						
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>

<?php 

namespace Stupycart\Admin\Controllers\Payment;

class ChequeController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {
		$this->language->load('payment/cheque');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('cheque', $this->request->getPostE());				
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_payable'] = $this->language->get('entry_payable');
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

 		if (isset($this->error['payable'])) {
			$this->data['error_payable'] = $this->error['payable'];
		} else {
			$this->data['error_payable'] = '';
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
			'href'      => $this->url->link('payment/cheque', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/cheque', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');
		
		if ($this->request->hasPost('cheque_payable')) {
			$this->data['cheque_payable'] = $this->request->getPostE('cheque_payable');
		} else {
			$this->data['cheque_payable'] = $this->config->get('cheque_payable');
		}
		
		if ($this->request->hasPost('cheque_total')) {
			$this->data['cheque_total'] = $this->request->getPostE('cheque_total');
		} else {
			$this->data['cheque_total'] = $this->config->get('cheque_total'); 
		} 
				
		if ($this->request->hasPost('cheque_order_status_id')) {
			$this->data['cheque_order_status_id'] = $this->request->getPostE('cheque_order_status_id');
		} else {
			$this->data['cheque_order_status_id'] = $this->config->get('cheque_order_status_id'); 
		} 
		
		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if ($this->request->hasPost('cheque_geo_zone_id')) {
			$this->data['cheque_geo_zone_id'] = $this->request->getPostE('cheque_geo_zone_id');
		} else {
			$this->data['cheque_geo_zone_id'] = $this->config->get('cheque_geo_zone_id'); 
		} 
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('cheque_status')) {
			$this->data['cheque_status'] = $this->request->getPostE('cheque_status');
		} else {
			$this->data['cheque_status'] = $this->config->get('cheque_status');
		}
		
		if ($this->request->hasPost('cheque_sort_order')) {
			$this->data['cheque_sort_order'] = $this->request->getPostE('cheque_sort_order');
		} else {
			$this->data['cheque_sort_order'] = $this->config->get('cheque_sort_order');
		}

		$this->view->pick('payment/cheque');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cheque')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->getPostE('cheque_payable')) {
			$this->error['payable'] = $this->language->get('error_payable');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
<?php 

namespace Stupycart\Admin\Controllers\Payment;

class FreeCheckoutController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	 
	public function indexAction() { 
		$this->language->load('payment/free_checkout');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
				
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('free_checkout', $this->request->getPostE());

			$this->session->set('success', $this->language->get('text_success'));
			
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      =>  $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'),       		
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/free_checkout', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('payment/free_checkout', 'token=' . $this->session->get('token'), 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');	
				
		if ($this->request->hasPost('free_checkout_order_status_id')) {
			$this->data['free_checkout_order_status_id'] = $this->request->getPostE('free_checkout_order_status_id');
		} else {
			$this->data['free_checkout_order_status_id'] = $this->config->get('free_checkout_order_status_id'); 
		} 
		
		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
				
		if ($this->request->hasPost('free_checkout_status')) {
			$this->data['free_checkout_status'] = $this->request->getPostE('free_checkout_status');
		} else {
			$this->data['free_checkout_status'] = $this->config->get('free_checkout_status');
		}
		
		if ($this->request->hasPost('free_checkout_sort_order')) {
			$this->data['free_checkout_sort_order'] = $this->request->getPostE('free_checkout_sort_order');
		} else {
			$this->data['free_checkout_sort_order'] = $this->config->get('free_checkout_sort_order');
		}
						
		$this->view->pick('payment/free_checkout');
		$this->_commonAction();
			
		$this->view->setVars($this->data);
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/free_checkout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
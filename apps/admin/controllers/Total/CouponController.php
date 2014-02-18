<?php

namespace Stupycart\Admin\Controllers\Total;

class CouponController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	 
	public function indexAction() { 
		$this->language->load('total/coupon');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('coupon', $this->request->getPostE());
		
			$this->session->set('success', $this->language->get('text_success'));
			
			$this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/coupon', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/coupon', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->get('token'), 'SSL');

		if ($this->request->hasPost('coupon_status')) {
			$this->data['coupon_status'] = $this->request->getPostE('coupon_status');
		} else {
			$this->data['coupon_status'] = $this->config->get('coupon_status');
		}

		if ($this->request->hasPost('coupon_sort_order')) {
			$this->data['coupon_sort_order'] = $this->request->getPostE('coupon_sort_order');
		} else {
			$this->data['coupon_sort_order'] = $this->config->get('coupon_sort_order');
		}

		$this->view->pick('total/coupon');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/coupon')) {
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
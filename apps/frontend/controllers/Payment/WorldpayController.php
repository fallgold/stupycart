<?php

namespace Stupycart\Frontend\Controllers\Payment;

class WorldPayController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
		
		$order_info = $this->model_checkout_order->getOrder($this->session->get('order_id'));
		
		if (!$this->config->get('worldpay_test')){
			$this->data['action'] = 'https://secure.worldpay.com/wcc/purchase';
		}else{
			$this->data['action'] = 'https://secure-test.worldpay.com/wcc/purchase';
		}
	  
		$this->data['merchant'] = $this->config->get('worldpay_merchant');
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$this->data['currency'] = $order_info['currency_code'];
		$this->data['description'] = $this->config->get('config_name') . ' - #' . $order_info['order_id'];
		$this->data['name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		
		if (!$order_info['payment_address_2']) {
			$this->data['address'] = $order_info['payment_address_1'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'];
		} else {
			$this->data['address'] = $order_info['payment_address_1'] . ', ' . $order_info['payment_address_2'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'];
		}
		
		$this->data['postcode'] = $order_info['payment_postcode'];
		$this->data['country'] = $order_info['payment_iso_code_2'];
		$this->data['telephone'] = $order_info['telephone'];
		$this->data['email'] = $order_info['email'];
		$this->data['test'] = $this->config->get('worldpay_test');
		
		$this->view->pick('payment/worldpay');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
	
	public function callbackAction() {
		$this->language->load('payment/worldpay');
	
		$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		if (!$this->request->hasServer('HTTPS') || ($this->request->getServer('HTTPS') != 'on')) {
			$this->data['base'] = $this->config->get('config_url');
		} else {
			$this->data['base'] = $this->config->get('config_ssl');
		}
	
		$this->data['language'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
	
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		$this->data['text_response'] = $this->language->get('text_response');
		$this->data['text_success'] = $this->language->get('text_success');
		$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
		$this->data['text_failure'] = $this->language->get('text_failure');
		$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/checkout', '', 'SSL'));
	
		if ($this->request->hasPost('transStatus') && $this->request->getPostE('transStatus') == 'Y') { 
			$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();

			// If returned successful but callbackPW doesn't match, set order to pendind and record reason
			if ($this->request->hasPost('callbackPW') && ($this->request->getPostE('callbackPW') == $this->config->get('worldpay_password'))) {
				$this->model_checkout_order->confirm($this->request->getPostE('cartId'), $this->config->get('worldpay_order_status_id'));
			} else {
				$this->model_checkout_order->confirm($this->request->getPostE('cartId'), $this->config->get('config_order_status_id'), $this->language->get('text_pw_mismatch'));
			}
	
			$message = '';

			if ($this->request->hasPost('transId')) {
				$message .= 'transId: ' . $this->request->getPostE('transId') . "\n";
			}
		
			if ($this->request->hasPost('transStatus')) {
				$message .= 'transStatus: ' . $this->request->getPostE('transStatus') . "\n";
			}
		
			if ($this->request->hasPost('countryMatch')) {
				$message .= 'countryMatch: ' . $this->request->getPostE('countryMatch') . "\n";
			}
		
			if ($this->request->hasPost('AVS')) {
				$message .= 'AVS: ' . $this->request->getPostE('AVS') . "\n";
			}	

			if ($this->request->hasPost('rawAuthCode')) {
				$message .= 'rawAuthCode: ' . $this->request->getPostE('rawAuthCode') . "\n";
			}	

			if ($this->request->hasPost('authMode')) {
				$message .= 'authMode: ' . $this->request->getPostE('authMode') . "\n";
			}	

			if ($this->request->hasPost('rawAuthMessage')) {
				$message .= 'rawAuthMessage: ' . $this->request->getPostE('rawAuthMessage') . "\n";
			}	
		
			if ($this->request->hasPost('wafMerchMessage')) {
				$message .= 'wafMerchMessage: ' . $this->request->getPostE('wafMerchMessage') . "\n";
			}				

			$this->model_checkout_order->updateOrder($this->request->getPostE('cartId'), $this->config->get('worldpay_order_status_id'), $message, false);
	
			$this->data['continue'] = $this->url->link('checkout/success');
			
			$this->view->pick('payment/worldpay_success');
	
			$this->view->setVars($this->data);				
		} else {
			$this->data['continue'] = $this->url->link('checkout/cart');
	
			$this->view->pick('payment/worldpay_failure');
			
			$this->view->setVars($this->data);					
		}
	}
}
?>
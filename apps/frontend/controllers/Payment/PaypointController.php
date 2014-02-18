<?php

namespace Stupycart\Frontend\Controllers\Payment;

class PaypointController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();

		$order_info = $this->model_checkout_order->getOrder($this->session->get('order_id'));

		$this->data['merchant'] = $this->config->get('paypoint_merchant');
		$this->data['trans_id'] = $this->session->get('order_id');
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		
		if ($this->config->get('paypoint_password')) {
			$this->data['digest'] = md5($this->session->get('order_id') . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . $this->config->get('paypoint_password'));
		} else {
			$this->data['digest'] = '';
		}		
		
		$this->data['bill_name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$this->data['bill_addr_1'] = $order_info['payment_address_1'];
		$this->data['bill_addr_2'] = $order_info['payment_address_2'];
		$this->data['bill_city'] = $order_info['payment_city'];
		$this->data['bill_state'] = $order_info['payment_zone'];
		$this->data['bill_post_code'] = $order_info['payment_postcode'];
		$this->data['bill_country'] = $order_info['payment_country'];
		$this->data['bill_tel'] = $order_info['telephone'];
		$this->data['bill_email'] = $order_info['email'];

		if ($this->cart->hasShipping()) {
			$this->data['ship_name'] = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'];
			$this->data['ship_addr_1'] = $order_info['shipping_address_1'];
			$this->data['ship_addr_2'] = $order_info['shipping_address_2'];
			$this->data['ship_city'] = $order_info['shipping_city'];
			$this->data['ship_state'] = $order_info['shipping_zone'];
			$this->data['ship_post_code'] = $order_info['shipping_postcode'];
			$this->data['ship_country'] = $order_info['shipping_country'];
		} else {
			$this->data['ship_name'] = '';
			$this->data['ship_addr_1'] = '';
			$this->data['ship_addr_2'] = '';
			$this->data['ship_city'] = '';
			$this->data['ship_state'] = '';
			$this->data['ship_post_code'] = '';
			$this->data['ship_country'] = '';
		}

		$this->data['currency'] = $this->currency->getCode();
		$this->data['callback'] = $this->url->link('payment/paypoint/callback', '', 'SSL');

		switch ($this->config->get('paypoint_test')) {
			case 'live':
				$status = 'live';
				break;
			case 'successful':
			default:
				$status = 'true';
				break;
			case 'fail':
				$status = 'false';
				break;
		}
		
		$this->data['options'] = 'test_status=' . $status . ',dups=false,cb_post=false';
	 
		$this->view->pick('payment/paypoint');

		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}

	public function callbackAction() {
		if ($this->request->hasQuery('trans_id')) {
			$order_id = $this->request->getQueryE('trans_id');
		} else {
			$order_id = 0;
		}	
		
		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
				
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		// Validate the request is from PayPoint
		if ($this->config->get('paypoint_password')) {
			if (!(!$this->request->getQueryE('hash'))) {
				$status = ($this->request->getQueryE('hash') == md5(str_replace('hash=' . $this->request->getQueryE('hash'), '', htmlspecialchars_decode($this->request->getServer('REQUEST_URI'), ENT_COMPAT)) . $this->config->get('paypoint_password')));
			} else {
				$status = false;
			}
		} else {
			$status = true;
		}
									
		if ($order_info) {
			$this->language->load('payment/paypoint');
	
			$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
	
			if (!$this->request->hasServer('HTTPS') || ($this->request->getServer('HTTPS') != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}
	
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
	
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
	
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
			$this->data['text_failure'] = $this->language->get('text_failure');
			$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart'));
	
			if ($this->request->hasQuery('code') && $this->request->getQueryE('code') == 'A' && $status) {
				$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
	
				$this->model_checkout_order->confirm($this->request->getQueryE('trans_id'), $this->config->get('config_order_status_id'));
	
				$message = '';
	
				if ($this->request->hasQuery('code')) {
					$message .= 'code: ' . $this->request->getQueryE('code') . "\n";
				}
	
				if ($this->request->hasQuery('auth_code')) {
					$message .= 'auth_code: ' . $this->request->getQueryE('auth_code') . "\n";
				}
	
				if ($this->request->hasQuery('ip')) {
					$message .= 'ip: ' . $this->request->getQueryE('ip') . "\n";
				}
	
				if ($this->request->hasQuery('cv2avs')) {
					$message .= 'cv2avs: ' . $this->request->getQueryE('cv2avs') . "\n";
				}
	
				if ($this->request->hasQuery('valid')) {
					$message .= 'valid: ' . $this->request->getQueryE('valid') . "\n";
				}
	
				$this->model_checkout_order->updateOrder($order_id, $this->config->get('paypoint_order_status_id'), $message, false);
	
				$this->data['continue'] = $this->url->link('checkout/success');
	
				$this->view->pick('payment/paypoint_success');
	
		$this->_commonAction();
	
				$this->view->setVars($this->data);
			} else {
				$this->data['continue'] = $this->url->link('checkout/cart');
	
				$this->view->pick('payment/paypoint_failure');
	
		$this->_commonAction();
	
				$this->view->setVars($this->data);
			}
		}
	}
}
?>
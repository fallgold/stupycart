<?php 

namespace Stupycart\Frontend\Controllers\Payment;

class PaymateController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		if (!$this->config->get('paymate_test')) {
			$this->data['action'] = 'https://www.paymate.com/PayMate/ExpressPayment';
		} else {
			$this->data['action'] = 'https://www.paymate.com.au/PayMate/TestExpressPayment';
		}
		
		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
		
		$order_info = $this->model_checkout_order->getOrder($this->session->get('order_id'));
				
		$this->data['mid'] = $this->config->get('paymate_username');
		$this->data['amt'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false); 
		
		$this->data['currency'] = $order_info['currency_code'];
		$this->data['ref'] = $order_info['order_id'];
		
		$this->data['pmt_sender_email'] = $order_info['email'];
		$this->data['pmt_contact_firstname'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$this->data['pmt_contact_surname'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['pmt_contact_phone'] = $order_info['telephone'];
		$this->data['pmt_country'] = $order_info['payment_iso_code_2'];
		
		$this->data['regindi_address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_sub'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$this->data['regindi_pcode'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		
		$this->data['return'] = $this->url->link('payment/paymate/callback', 'hash=' . md5($order_info['order_id'] . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . $order_info['currency_code'] . $this->config->get('paymate_password')));
		
		$this->view->pick('payment/paymate');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
	
	public function callbackAction() {
		$this->language->load('payment/paymate');
		
		if ($this->request->hasPost('ref')) {
			$order_id = $this->request->getPostE('ref');
		} else {
			$order_id = 0;
		}			
		
		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
				
		$order_info = $this->model_checkout_order->getOrder($order_id);	 	
		
		if ($order_info) {
			$error = '';
			
			if (!$this->request->hasPost('responseCode') || !$this->request->hasQuery('hash')) {
				$error = $this->language->get('text_unable');
			} elseif ($this->request->getQueryE('hash') != md5($order_info['order_id'] . $this->currency->format($this->request->getPostE('paymentAmount'), $this->request->getPostE('currency'), 1.0000000, false) . $this->request->getPostE('currency') . $this->config->get('paymate_password'))) {
				$error = $this->language->get('text_unable');
			} elseif ($this->request->getPostE('responseCode') != 'PA' && $this->request->getPostE('responseCode') != 'PP') {
				$error = $this->language->get('text_declined');
			}
		} else {
			$error = $this->language->get('text_unable');
		}	

		if ($error) {
			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('common/home'),
				'text'      => $this->language->get('text_home'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('checkout/cart'),
				'text'      => $this->language->get('text_basket'),
				'separator' => $this->language->get('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
				'text'      => $this->language->get('text_checkout'),
				'separator' => $this->language->get('text_separator')
			);

			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('checkout/success'),
				'text'      => $this->language->get('text_failed'),
				'separator' => $this->language->get('text_separator')
			);
						
			$this->data['heading_title'] = $this->language->get('text_failed');

			$this->data['text_message'] = sprintf($this->language->get('text_failed_message'), $error, $this->url->link('information/contact'));
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['continue'] = $this->url->link('common/home');
			
			$this->view->pick('common/success');
			
		$this->_commonAction();
			
			$this->view->setVars($this->data);
		} else {
			$this->model_checkout_order->confirm($order_id, $this->config->get('paymate_order_status_id'));
			
			$this->response->redirect($this->url->link('checkout/success'), true);
		return;			
		}		
	}
}
?>
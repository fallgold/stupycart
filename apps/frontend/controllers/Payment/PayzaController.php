<?php

namespace Stupycart\Frontend\Controllers\Payment;

class PayzaController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
		
		$order_info = $this->model_checkout_order->getOrder($this->session->get('order_id'));
		
		$this->data['action'] = 'https://www.payza.com/PayProcess.aspx';

		$this->data['ap_merchant'] = $this->config->get('payza_merchant');
		$this->data['ap_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$this->data['ap_currency'] = $order_info['currency_code'];
		$this->data['ap_purchasetype'] = 'Item';
		$this->data['ap_itemname'] = $this->config->get('config_name') . ' - #' . $this->session->get('order_id');
		$this->data['ap_itemcode'] = $this->session->get('order_id');
		$this->data['ap_returnurl'] = $this->url->link('checkout/success');
		$this->data['ap_cancelurl'] = $this->url->link('checkout/checkout', '', 'SSL');

		$this->view->pick('payment/payza');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
	
	public function callbackAction() {
		if ($this->request->hasPost('ap_securitycode') && ($this->request->getPostE('ap_securitycode') == $this->config->get('payza_security'))) {
			$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
			
			$this->model_checkout_order->confirm($this->request->getPostE('ap_itemcode'), $this->config->get('payza_order_status_id'));
		}
	}
}
?>
<?php

namespace Stupycart\Frontend\Controllers\Payment;

class FreeCheckoutController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['continue'] = $this->url->link('checkout/success');

           $this->view->pick('payment/free_checkout');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();		 
	}
	
	public function confirmAction() {
		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
		
		$this->model_checkout_order->confirm($this->session->get('order_id'), $this->config->get('free_checkout_order_status_id'));
	}
}
?>
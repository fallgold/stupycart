<?php

namespace Stupycart\Frontend\Controllers\Payment;

class ChequeController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->language->load('payment/cheque');
		
		$this->data['text_instruction'] = $this->language->get('text_instruction');
    	$this->data['text_payable'] = $this->language->get('text_payable');
		$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_payment'] = $this->language->get('text_payment');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['payable'] = $this->config->get('cheque_payable');
		$this->data['address'] = nl2br($this->config->get('config_address'));

		$this->data['continue'] = $this->url->link('checkout/success');
		
		$this->view->pick('payment/cheque');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent(); 
	}
	
	public function confirmAction() {
		$this->language->load('payment/cheque');
		
		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
		
		$comment  = $this->language->get('text_payable') . "\n";
		$comment .= $this->config->get('cheque_payable') . "\n\n";
		$comment .= $this->language->get('text_address') . "\n";
		$comment .= $this->config->get('config_address') . "\n\n";
		$comment .= $this->language->get('text_payment') . "\n";
		
		$this->model_checkout_order->confirm($this->session->get('order_id'), $this->config->get('cheque_order_status_id'), $comment, true);
	}
}
?>
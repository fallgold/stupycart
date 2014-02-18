<?php

namespace Stupycart\Frontend\Controllers\Payment;

class BankTransferController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->language->load('payment/bank_transfer');
		
		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['text_description'] = $this->language->get('text_description');
		$this->data['text_payment'] = $this->language->get('text_payment');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['bank'] = nl2br($this->config->get('bank_transfer_bank_' . $this->config->get('config_language_id')));

		$this->data['continue'] = $this->url->link('checkout/success');
		
		$this->view->pick('payment/bank_transfer');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent(); 
	}
	
	public function confirmAction() {
		$this->language->load('payment/bank_transfer');
		
		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
		
		$comment  = $this->language->get('text_instruction') . "\n\n";
		$comment .= $this->config->get('bank_transfer_bank_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->language->get('text_payment');
		
		$this->model_checkout_order->confirm($this->session->get('order_id'), $this->config->get('bank_transfer_order_status_id'), $comment, true);
	}
}
?>
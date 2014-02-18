<?php

namespace Stupycart\Frontend\Controllers\Payment;

class PPExpressController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		if (!$this->config->get('pp_express_test')) {
    		$this->data['action'] = 'https://www.pp_express.com/cgi-bin/webscr';
  		} else {
			$this->data['action'] = 'https://www.sandbox.pp_express.com/cgi-bin/webscr';
		}		
		
		$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
		
		$order_info = $this->model_checkout_order->getOrder($this->session->get('order_id'));

		if (!$this->config->get('pp_direct_test')) {
			$api_endpoint = 'https://api-3t.pp.com/nvp';
		} else {
			$api_endpoint = 'https://api-3t.sandbox.pp.com/nvp';
		}
		
		$this->view->pick('payment/pp_express');

		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();		
	}
}
?>
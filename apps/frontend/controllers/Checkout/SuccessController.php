<?php

namespace Stupycart\Frontend\Controllers\Checkout;

class SuccessController extends \Stupycart\Frontend\Controllers\ControllerBase { 
	public function indexAction() { 	
		if ($this->session->has('order_id')) {
			$this->cart->clear();

			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
			$this->session->remove('payment_method');
			$this->session->remove('payment_methods');
			$this->session->remove('guest');
			$this->session->remove('comment');
			$this->session->remove('order_id');	
			$this->session->remove('coupon');
			$this->session->remove('reward');
			$this->session->remove('voucher');
			$this->session->remove('vouchers');
		}	
									   
		$this->language->load('checkout/success');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
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
        	'text'      => $this->language->get('text_success'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		if ($this->customer->isLogged()) {
    		$this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
		} else {
    		$this->data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}
		
    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');

		$this->view->pick('common/success');
		
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
}
?>
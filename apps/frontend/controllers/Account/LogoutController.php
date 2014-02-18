<?php 

namespace Stupycart\Frontend\Controllers\Account;

class LogoutController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	if ($this->customer->isLogged()) {
      		$this->customer->logout();
	  		$this->cart->clear();
			
			$this->session->remove('wishlist');
			$this->session->remove('shipping_address_id');
			$this->session->remove('shipping_country_id');
			$this->session->remove('shipping_zone_id');
			$this->session->remove('shipping_postcode');
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
			$this->session->remove('payment_address_id');
			$this->session->remove('payment_country_id');
			$this->session->remove('payment_zone_id');
			$this->session->remove('payment_method');
			$this->session->remove('payment_methods');
			$this->session->remove('comment');
			$this->session->remove('order_id');
			$this->session->remove('coupon');
			$this->session->remove('reward');			
			$this->session->remove('voucher');
			$this->session->remove('vouchers');
			
      		$this->response->redirect($this->url->link('account/logout', '', 'SSL'), true);
		return;
    	}
 
    	$this->language->load('account/logout');
		
		$this->document->setTitle($this->language->get('heading_title'));
      	
		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	);
      	
		$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_logout'),
			'href'      => $this->url->link('account/logout', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');
		
		$this->view->pick('common/success');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);	
  	}
}
?>
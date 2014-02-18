<?php  

namespace Stupycart\Frontend\Controllers\Checkout;

class LoginController extends \Stupycart\Frontend\Controllers\ControllerBase { 
	public function indexAction() {
		$this->language->load('checkout/checkout');
		
		$this->data['text_new_customer'] = $this->language->get('text_new_customer');
		$this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_register'] = $this->language->get('text_register');
		$this->data['text_guest'] = $this->language->get('text_guest');
		$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$this->data['text_register_account'] = $this->language->get('text_register_account');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');
 
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_password'] = $this->language->get('entry_password');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_login'] = $this->language->get('button_login');
		
		$this->data['guest_checkout'] = ($this->config->get('config_guest_checkout') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());
		
		if ($this->session->has('account')) {
			$this->data['account'] = $this->session->get('account');
		} else {
			$this->data['account'] = 'register';
		}
		
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		
		$this->view->pick('checkout/login');
				
		$this->view->setVars($this->data);
	}
	
	public function validateAction() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');			
		}
		
		if ((!$this->cart->hasProducts() && (!$this->session->get('vouchers'))) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}	
		
		if (!$json) {
			if (!$this->customer->login($this->request->getPostE('email'), $this->request->getPostE('password'))) {
				$json['error']['warning'] = $this->language->get('error_login');
			}
		
			$this->model_account_customer = new \Stupycart\Common\Models\Account\Customer();
		
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->getPostE('email'));
			
			if ($customer_info && !$customer_info['approved']) {
				$json['error']['warning'] = $this->language->get('error_approved');
			}		
		}
		
		if (!$json) {
			$this->session->remove('guest');
				
			// Default Addresses
			$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
				
			$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());
									
			if ($address_info) {
				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->set('shipping_country_id', $address_info['country_id']);
					$this->session->set('shipping_zone_id', $address_info['zone_id']);
					$this->session->set('shipping_postcode', $address_info['postcode']);	
				}
				
				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->set('payment_country_id', $address_info['country_id']);
					$this->session->set('payment_zone_id', $address_info['zone_id']);
				}
			} else {
				$this->session->remove('shipping_country_id');	
				$this->session->remove('shipping_zone_id');	
				$this->session->remove('shipping_postcode');
				$this->session->remove('payment_country_id');	
				$this->session->remove('payment_zone_id');	
			}					
				
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}
					
		$this->response->setContent(json_encode($json));
		return $this->response;		
	}
}
?>
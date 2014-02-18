<?php 

namespace Stupycart\Frontend\Controllers\Account;

class LoginController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();
	
	public function indexAction() {
		$this->model_account_customer = new \Stupycart\Common\Models\Account\Customer();
		
		// Login override for admin users
		if (!(!$this->request->getQueryE('token'))) {
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
			
			$customer_info = $this->model_account_customer->getCustomerByToken($this->request->getQueryE('token'));
			
		 	if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
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
									
				$this->response->redirect($this->url->link('account/account', '', 'SSL'), true);
		return; 
			}
		}		
		
		if ($this->customer->isLogged()) {  
      		$this->response->redirect($this->url->link('account/account', '', 'SSL'), true);
		return;
    	}
	
    	$this->language->load('account/login');

    	$this->document->setTitle($this->language->get('heading_title'));
								
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->session->remove('guest');
			
			// Default Shipping Address
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
							
			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if ($this->request->hasPost('redirect') && (strpos($this->request->getPostE('redirect'), $this->config->get('config_url')) !== false || strpos($this->request->getPostE('redirect'), $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->getPostE('redirect')), true);
		return;
			} else {
				$this->response->redirect($this->url->link('account/account', '', 'SSL'), true);
		return; 
			}
    	}  
		
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
        	'text'      => $this->language->get('text_login'),
			'href'      => $this->url->link('account/login', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);
				
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_new_customer'] = $this->language->get('text_new_customer');
    	$this->data['text_register'] = $this->language->get('text_register');
    	$this->data['text_register_account'] = $this->language->get('text_register_account');
		$this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
    	$this->data['text_forgotten'] = $this->language->get('text_forgotten');

    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_login'] = $this->language->get('button_login');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['action'] = $this->url->link('account/login', '', 'SSL');
		$this->data['register'] = $this->url->link('account/register', '', 'SSL');
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

    	// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if ($this->request->hasPost('redirect') && (strpos($this->request->getPostE('redirect'), $this->config->get('config_url')) !== false || strpos($this->request->getPostE('redirect'), $this->config->get('config_ssl')) !== false)) {
			$this->data['redirect'] = $this->request->getPostE('redirect');
		} elseif ($this->session->has('redirect')) {
      		$this->data['redirect'] = $this->session->get('redirect');
	  		
			$this->session->remove('redirect');		  	
    	} else {
			$this->data['redirect'] = '';
		}

		if ($this->session->has('success')) {
    		$this->data['success'] = $this->session->get('success');
    
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}
		
		if ($this->request->hasPost('email')) {
			$this->data['email'] = $this->request->getPostE('email');
		} else {
			$this->data['email'] = '';
		}

		if ($this->request->hasPost('password')) {
			$this->data['password'] = $this->request->getPostE('password');
		} else {
			$this->data['password'] = '';
		}
				
		$this->view->pick('account/login');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);
  	}
  
  	protected function validate() {
    	if (!$this->customer->login($this->request->getPostE('email'), $this->request->getPostE('password'))) {
      		$this->error['warning'] = $this->language->get('error_login');
    	}
	
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->getPostE('email'));
		
    	if ($customer_info && !$customer_info['approved']) {
      		$this->error['warning'] = $this->language->get('error_approved');
    	}		
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}  	
  	}
}
?>
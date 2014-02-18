<?php 

namespace Stupycart\Frontend\Controllers\Checkout;

class RegisterController extends \Stupycart\Frontend\Controllers\ControllerBase {
  	public function indexAction() {
		$this->language->load('checkout/checkout');
		
		$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_address'] = $this->language->get('text_your_address');
		$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
						
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');		
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');

		$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['customer_groups'] = array();
		
		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->model_account_customer_group = new \Stupycart\Common\Models\Account\CustomerGroup();
			
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
			foreach ($customer_groups  as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
		
		if ($this->session->has('shipping_postcode')) {
			$this->data['postcode'] = $this->session->get('shipping_postcode');		
		} else {
			$this->data['postcode'] = '';
		}
		
    	if ($this->session->has('shipping_country_id')) {
			$this->data['country_id'] = $this->session->get('shipping_country_id');		
		} else {	
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}
		
    	if ($this->session->has('shipping_zone_id')) {
			$this->data['zone_id'] = $this->session->get('shipping_zone_id');			
		} else {
      		$this->data['zone_id'] = '';
    	}
				
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		if ($this->config->get('config_account_id')) {
			$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		$this->data['shipping_required'] = $this->cart->hasShipping();
			
		$this->view->pick('checkout/register');
		
		$this->view->setVars($this->data);		
  	}
	
	public function validateAction() {
		$this->language->load('checkout/checkout');
		
		$this->model_account_customer = new \Stupycart\Common\Models\Account\Customer();
		
		$json = array();
		
		// Validate if customer is already logged out.
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');			
		}
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && (!$this->session->get('vouchers'))) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		
		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}				
		}
						
		if (!$json) {					
			if ((utf8_strlen($this->request->getPostE('firstname')) < 1) || (utf8_strlen($this->request->getPostE('firstname')) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}
		
			if ((utf8_strlen($this->request->getPostE('lastname')) < 1) || (utf8_strlen($this->request->getPostE('lastname')) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}
		
			if ((utf8_strlen($this->request->getPostE('email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('email'))) {
				$json['error']['email'] = $this->language->get('error_email');
			}
	
			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->getPostE('email'))) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}
			
			if ((utf8_strlen($this->request->getPostE('telephone')) < 3) || (utf8_strlen($this->request->getPostE('telephone')) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}
	
			// Customer Group
			$this->model_account_customer_group = new \Stupycart\Common\Models\Account\CustomerGroup();
			
			if ($this->request->hasPost('customer_group_id') && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->getPostE('customer_group_id'), $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->getPostE('customer_group_id');
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
				
			if ($customer_group) {	
				// Company ID
				if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && (!$this->request->getPostE('company_id'))) {
					$json['error']['company_id'] = $this->language->get('error_company_id');
				}
				
				// Tax ID
				if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && (!$this->request->getPostE('tax_id'))) {
					$json['error']['tax_id'] = $this->language->get('error_tax_id');
				}						
			}
			
			if ((utf8_strlen($this->request->getPostE('address_1')) < 3) || (utf8_strlen($this->request->getPostE('address_1')) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}
	
			if ((utf8_strlen($this->request->getPostE('city')) < 2) || (utf8_strlen($this->request->getPostE('city')) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}
	
			$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
			
			$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('country_id'));
			
			if ($country_info) {
				if ($country_info['postcode_required'] && (utf8_strlen($this->request->getPostE('postcode')) < 2) || (utf8_strlen($this->request->getPostE('postcode')) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}
				 
				// VAT Validation
				require_once(_ROOT_. '/libs/helper/vat.php');
				
				if ($this->config->get('config_vat') && $this->request->getPostE('tax_id') && (vat_validation($country_info['iso_code_2'], $this->request->getPostE('tax_id')) == 'invalid')) {
					$json['error']['tax_id'] = $this->language->get('error_vat');
				}				
			}
	
			if ($this->request->getPostE('country_id') == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}
			
			if (!$this->request->hasPost('zone_id') || $this->request->getPostE('zone_id') == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}
	
			if ((utf8_strlen($this->request->getPostE('password')) < 4) || (utf8_strlen($this->request->getPostE('password')) > 20)) {
				$json['error']['password'] = $this->language->get('error_password');
			}
	
			if ($this->request->getPostE('confirm') != $this->request->getPostE('password')) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
			
			if ($this->config->get('config_account_id')) {
				$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
				
				if ($information_info && !$this->request->hasPost('agree')) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
		}
		
		if (!$json) {
			$this->model_account_customer->addCustomer($this->request->getPostE());
			
			$this->session->set('account', 'register');
			
			if ($customer_group && !$customer_group['approval']) {
				$this->customer->login($this->request->getPostE('email'), $this->request->getPostE('password'));
				
				$this->session->set('payment_address_id', $this->customer->getAddressId());
				$this->session->set('payment_country_id', $this->request->getPostE('country_id'));
				$this->session->set('payment_zone_id', $this->request->getPostE('zone_id'));
									
				if (!(!$this->request->getPostE('shipping_address'))) {
					$this->session->set('shipping_address_id', $this->customer->getAddressId());
					$this->session->set('shipping_country_id', $this->request->getPostE('country_id'));
					$this->session->set('shipping_zone_id', $this->request->getPostE('zone_id'));
					$this->session->set('shipping_postcode', $this->request->getPostE('postcode'));					
				}
			} else {
				$json['redirect'] = $this->url->link('account/success');
			}
			
			$this->session->remove('guest');
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
			$this->session->remove('payment_method');	
			$this->session->remove('payment_methods');
		}	
		
		$this->response->setContent(json_encode($json));
		return $this->response;	
	} 
}
?>
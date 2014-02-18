<?php 

namespace Stupycart\Frontend\Controllers\Checkout;

class PaymentAddressController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->language->load('checkout/checkout');
		
		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');			
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
	
		$this->data['button_continue'] = $this->language->get('button_continue');

		if ($this->session->has('payment_address_id')) {
			$this->data['address_id'] = $this->session->get('payment_address_id');
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}
		
		$this->data['addresses'] = array();
		
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
		
		$this->data['addresses'] = $this->model_account_address->getAddresses();
		
		$this->model_account_customer_group = new \Stupycart\Common\Models\Account\CustomerGroup();
		
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());
		
		if ($customer_group_info) {
			$this->data['company_id_display'] = $customer_group_info['company_id_display'];
		} else {
			$this->data['company_id_display'] = '';
		}
		
		if ($customer_group_info) {
			$this->data['company_id_required'] = $customer_group_info['company_id_required'];
		} else {
			$this->data['company_id_required'] = '';
		}
				
		if ($customer_group_info) {
			$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
		} else {
			$this->data['tax_id_display'] = '';
		}
		
		if ($customer_group_info) {
			$this->data['tax_id_required'] = $customer_group_info['tax_id_required'];
		} else {
			$this->data['tax_id_required'] = '';
		}
										
		if ($this->session->has('payment_country_id')) {
			$this->data['country_id'] = $this->session->get('payment_country_id');		
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}
				
		if ($this->session->has('payment_zone_id')) {
			$this->data['zone_id'] = $this->session->get('payment_zone_id');		
		} else {
			$this->data['zone_id'] = '';
		}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
	
		$this->view->pick('checkout/payment_address');
	
		$this->view->setVars($this->data);			
  	}
	
	public function validateAction() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
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
			if ($this->request->hasPost('payment_address') && $this->request->getPostE('payment_address') == 'existing') {
				$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
				
				if ((!$this->request->getPostE('address_id'))) {
					$json['error']['warning'] = $this->language->get('error_address');
				} elseif (!in_array($this->request->getPostE('address_id'), array_keys($this->model_account_address->getAddresses()))) {
					$json['error']['warning'] = $this->language->get('error_address');
				} else {
					// Default Payment Address
					$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
	
					$address_info = $this->model_account_address->getAddress($this->request->getPostE('address_id'));
										
					if ($address_info) {				
						$this->model_account_customer_group = new \Stupycart\Common\Models\Account\CustomerGroup();
				
						$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());
					
						// Company ID
						if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && !$address_info['company_id']) {
							$json['error']['warning'] = $this->language->get('error_company_id');
						}					
						
						// Tax ID
						if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && !$address_info['tax_id']) {
							$json['error']['warning'] = $this->language->get('error_tax_id');
						}						
					}					
				}
					
				if (!$json) {			
					$this->session->set('payment_address_id', $this->request->getPostE('address_id'));
					
					if ($address_info) {
						$this->session->set('payment_country_id', $address_info['country_id']);
						$this->session->set('payment_zone_id', $address_info['zone_id']);
					} else {
						$this->session->remove('payment_country_id');	
						$this->session->remove('payment_zone_id');	
					}
										
					$this->session->remove('payment_method');	
					$this->session->remove('payment_methods');
				}
			} else {
				if ((utf8_strlen($this->request->getPostE('firstname')) < 1) || (utf8_strlen($this->request->getPostE('firstname')) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}
		
				if ((utf8_strlen($this->request->getPostE('lastname')) < 1) || (utf8_strlen($this->request->getPostE('lastname')) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				}
		
				// Customer Group
				$this->model_account_customer_group = new \Stupycart\Common\Models\Account\CustomerGroup();
				
				$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());
					
				if ($customer_group_info) {	
					// Company ID
					if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && (!$this->request->getPostE('company_id'))) {
						$json['error']['company_id'] = $this->language->get('error_company_id');
					}
					
					// Tax ID
					if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && (!$this->request->getPostE('tax_id'))) {
						$json['error']['tax_id'] = $this->language->get('error_tax_id');
					}						
				}
					
				if ((utf8_strlen($this->request->getPostE('address_1')) < 3) || (utf8_strlen($this->request->getPostE('address_1')) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}
		
				if ((utf8_strlen($this->request->getPostE('city')) < 2) || (utf8_strlen($this->request->getPostE('city')) > 32)) {
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
					
					if ($this->config->get('config_vat') && !(!$this->request->getPostE('tax_id')) && (vat_validation($country_info['iso_code_2'], $this->request->getPostE('tax_id')) == 'invalid')) {
						$json['error']['tax_id'] = $this->language->get('error_vat');
					}						
				}
				
				if ($this->request->getPostE('country_id') == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}
				
				if (!$this->request->hasPost('zone_id') || $this->request->getPostE('zone_id') == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
				}
				
				if (!$json) {
					// Default Payment Address
					$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
					
					$this->session->set('payment_address_id', $this->model_account_address->addAddress($this->request->getPostE()));
					$this->session->set('payment_country_id', $this->request->getPostE('country_id'));
					$this->session->set('payment_zone_id', $this->request->getPostE('zone_id'));
															
					$this->session->remove('payment_method');	
					$this->session->remove('payment_methods');
				}		
			}		
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;
	}
}
?>
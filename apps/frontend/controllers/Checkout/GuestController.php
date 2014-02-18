<?php 

namespace Stupycart\Frontend\Controllers\Checkout;

class GuestController extends \Stupycart\Frontend\Controllers\ControllerBase {
  	public function indexAction() {
    	$this->language->load('checkout/checkout');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_account'] = $this->language->get('text_your_account');
		$this->data['text_your_address'] = $this->language->get('text_your_address');
		
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
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['firstname']))) {
			$this->data['firstname'] = (($_tmp = $this->session->get('guest')) ? $_tmp['firstname'] : null);
		} else {
			$this->data['firstname'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['lastname']))) {
			$this->data['lastname'] = (($_tmp = $this->session->get('guest')) ? $_tmp['lastname'] : null);
		} else {
			$this->data['lastname'] = '';
		}
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['email']))) {
			$this->data['email'] = (($_tmp = $this->session->get('guest')) ? $_tmp['email'] : null);
		} else {
			$this->data['email'] = '';
		}
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['telephone']))) {
			$this->data['telephone'] = (($_tmp = $this->session->get('guest')) ? $_tmp['telephone'] : null);		
		} else {
			$this->data['telephone'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['fax']))) {
			$this->data['fax'] = (($_tmp = $this->session->get('guest')) ? $_tmp['fax'] : null);				
		} else {
			$this->data['fax'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['company']))) {
			$this->data['company'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['company'] : null);			
		} else {
			$this->data['company'] = '';
		}

		$this->model_account_customer_group = new \Stupycart\Common\Models\Account\CustomerGroup();

		$this->data['customer_groups'] = array();
		
		if (is_array($this->config->get('config_customer_group_display'))) {
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['customer_group_id']))) {
    		$this->data['customer_group_id'] = (($_tmp = $this->session->get('guest')) ? $_tmp['customer_group_id'] : null);
		} else {
			$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
		
		// Company ID
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['company_id']))) {
			$this->data['company_id'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['company_id'] : null);			
		} else {
			$this->data['company_id'] = '';
		}
		
		// Tax ID
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['tax_id']))) {
			$this->data['tax_id'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['tax_id'] : null);			
		} else {
			$this->data['tax_id'] = '';
		}
								
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['address_1']))) {
			$this->data['address_1'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['address_1'] : null);			
		} else {
			$this->data['address_1'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['address_2']))) {
			$this->data['address_2'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['address_2'] : null);			
		} else {
			$this->data['address_2'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['postcode']))) {
			$this->data['postcode'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['postcode'] : null);							
		} elseif ($this->session->has('shipping_postcode')) {
			$this->data['postcode'] = $this->session->get('shipping_postcode');			
		} else {
			$this->data['postcode'] = '';
		}
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['city']))) {
			$this->data['city'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['city'] : null);			
		} else {
			$this->data['city'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['country_id']))) {
			$this->data['country_id'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['country_id'] : null);			  	
		} elseif ($this->session->has('shipping_country_id')) {
			$this->data['country_id'] = $this->session->get('shipping_country_id');		
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']['zone_id']))) {
			$this->data['zone_id'] = (($_tmp = $this->session->get('guest')) ? $_tmp['payment']['zone_id'] : null);	
		} elseif ($this->session->has('shipping_zone_id')) {
			$this->data['zone_id'] = $this->session->get('shipping_zone_id');						
		} else {
			$this->data['zone_id'] = '';
		}
					
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['shipping_required'] = $this->cart->hasShipping();
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping_address']))) {
			$this->data['shipping_address'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping_address'] : null);			
		} else {
			$this->data['shipping_address'] = true;
		}			
		
		$this->view->pick('checkout/guest');
		
		$this->view->setVars($this->data);		
  	}
	
	public function validateAction() {
    	$this->language->load('checkout/checkout');

		$json = array();
		
		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		} 			
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && (!$this->session->get('vouchers'))) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');		
		}
		
		// Check if guest checkout is avaliable.			
		if (!$this->config->get('config_guest_checkout') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
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
		}
			
		if (!$json) {
			{ $_tmp = $this->session->get('guest'); $_tmp['customer_group_id'] = $customer_group_id; $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['firstname'] = $this->request->getPostE('firstname'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['lastname'] = $this->request->getPostE('lastname'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['email'] = $this->request->getPostE('email'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['telephone'] = $this->request->getPostE('telephone'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['fax'] = $this->request->getPostE('fax'); $this->session->set('guest', $_tmp); }
			
			{ $_tmp = $this->session->get('guest'); $_tmp['firstname'] = $this->request->getPostE('firstname'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['lastname'] = $this->request->getPostE('lastname'); $this->session->set('guest', $_tmp); }				
			{ $_tmp = $this->session->get('guest'); $_tmp['company'] = $this->request->getPostE('company'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['company_id'] = $this->request->getPostE('company_id'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['tax_id'] = $this->request->getPostE('tax_id'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['address_1'] = $this->request->getPostE('address_1'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['address_2'] = $this->request->getPostE('address_2'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['postcode'] = $this->request->getPostE('postcode'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['city'] = $this->request->getPostE('city'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['country_id'] = $this->request->getPostE('country_id'); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['zone_id'] = $this->request->getPostE('zone_id'); $this->session->set('guest', $_tmp); }
							
			$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
			
			$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('country_id'));
			
			if ($country_info) {
				{ $_tmp = $this->session->get('guest'); $_tmp['country'] = $country_info['name']; $this->session->set('guest', $_tmp); }	
				{ $_tmp = $this->session->get('guest'); $_tmp['iso_code_2'] = $country_info['iso_code_2']; $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['iso_code_3'] = $country_info['iso_code_3']; $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['address_format'] = $country_info['address_format']; $this->session->set('guest', $_tmp); }
			} else {
				{ $_tmp = $this->session->get('guest'); $_tmp['country'] = ''; $this->session->set('guest', $_tmp); }	
				{ $_tmp = $this->session->get('guest'); $_tmp['iso_code_2'] = ''; $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['iso_code_3'] = ''; $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['address_format'] = ''; $this->session->set('guest', $_tmp); }
			}
						
			$this->model_localisation_zone = new \Stupycart\Common\Models\Localisation\Zone();

			$zone_info = $this->model_localisation_zone->getZone($this->request->getPostE('zone_id'));
			
			if ($zone_info) {
				{ $_tmp = $this->session->get('guest'); $_tmp['zone'] = $zone_info['name']; $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['zone_code'] = $zone_info['code']; $this->session->set('guest', $_tmp); }
			} else {
				{ $_tmp = $this->session->get('guest'); $_tmp['zone'] = ''; $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['zone_code'] = ''; $this->session->set('guest', $_tmp); }
			}
			
			if (!(!$this->request->getPostE('shipping_address'))) {
				{ $_tmp = $this->session->get('guest'); $_tmp['shipping_address'] = true; $this->session->set('guest', $_tmp); }
			} else {
				{ $_tmp = $this->session->get('guest'); $_tmp['shipping_address'] = false; $this->session->set('guest', $_tmp); }
			}
			
			// Default Payment Address
			$this->session->set('payment_country_id', $this->request->getPostE('country_id'));
			$this->session->set('payment_zone_id', $this->request->getPostE('zone_id'));
			
			if ((($_tmp = $this->session->get('guest')) ? $_tmp['shipping_address'] : null)) {
				{ $_tmp = $this->session->get('guest'); $_tmp['firstname'] = $this->request->getPostE('firstname'); $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['lastname'] = $this->request->getPostE('lastname'); $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['company'] = $this->request->getPostE('company'); $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['address_1'] = $this->request->getPostE('address_1'); $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['address_2'] = $this->request->getPostE('address_2'); $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['postcode'] = $this->request->getPostE('postcode'); $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['city'] = $this->request->getPostE('city'); $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['country_id'] = $this->request->getPostE('country_id'); $this->session->set('guest', $_tmp); }
				{ $_tmp = $this->session->get('guest'); $_tmp['zone_id'] = $this->request->getPostE('zone_id'); $this->session->set('guest', $_tmp); }
				
				if ($country_info) {
					{ $_tmp = $this->session->get('guest'); $_tmp['country'] = $country_info['name']; $this->session->set('guest', $_tmp); }	
					{ $_tmp = $this->session->get('guest'); $_tmp['iso_code_2'] = $country_info['iso_code_2']; $this->session->set('guest', $_tmp); }
					{ $_tmp = $this->session->get('guest'); $_tmp['iso_code_3'] = $country_info['iso_code_3']; $this->session->set('guest', $_tmp); }
					{ $_tmp = $this->session->get('guest'); $_tmp['address_format'] = $country_info['address_format']; $this->session->set('guest', $_tmp); }
				} else {
					{ $_tmp = $this->session->get('guest'); $_tmp['country'] = ''; $this->session->set('guest', $_tmp); }	
					{ $_tmp = $this->session->get('guest'); $_tmp['iso_code_2'] = ''; $this->session->set('guest', $_tmp); }
					{ $_tmp = $this->session->get('guest'); $_tmp['iso_code_3'] = ''; $this->session->set('guest', $_tmp); }
					{ $_tmp = $this->session->get('guest'); $_tmp['address_format'] = ''; $this->session->set('guest', $_tmp); }
				}
	
				if ($zone_info) {
					{ $_tmp = $this->session->get('guest'); $_tmp['zone'] = $zone_info['name']; $this->session->set('guest', $_tmp); }
					{ $_tmp = $this->session->get('guest'); $_tmp['zone_code'] = $zone_info['code']; $this->session->set('guest', $_tmp); }
				} else {
					{ $_tmp = $this->session->get('guest'); $_tmp['zone'] = ''; $this->session->set('guest', $_tmp); }
					{ $_tmp = $this->session->get('guest'); $_tmp['zone_code'] = ''; $this->session->set('guest', $_tmp); }
				}
				
				// Default Shipping Address
				$this->session->set('shipping_country_id', $this->request->getPostE('country_id'));
				$this->session->set('shipping_zone_id', $this->request->getPostE('zone_id'));
				$this->session->set('shipping_postcode', $this->request->getPostE('postcode'));
			}
			
			$this->session->set('account', 'guest');
			
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
			$this->session->remove('payment_method');
			$this->session->remove('payment_methods');
		}
					
		$this->response->setContent(json_encode($json));
		return $this->response;	
	}
	
  	public function zoneAction() {
		$output = '<option value="">' . $this->language->get('text_select') . '</option>';
		
		$this->model_localisation_zone = new \Stupycart\Common\Models\Localisation\Zone();

    	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->getQueryE('country_id'));
        
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if ($this->request->hasQuery('zone_id') && ($this->request->getQueryE('zone_id') == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
		  	$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}
	
		$this->response->setContent($output);
		return $this->response;
  	}
}
?>
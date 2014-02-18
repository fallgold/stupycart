<?php 

namespace Stupycart\Frontend\Controllers\Checkout;

class GuestShippingController extends \Stupycart\Frontend\Controllers\ControllerBase {
  	public function indexAction() {	
		$this->language->load('checkout/checkout');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
	
		$this->data['button_continue'] = $this->language->get('button_continue');
					
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['firstname']))) {
			$this->data['firstname'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['firstname'] : null);
		} else {
			$this->data['firstname'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['lastname']))) {
			$this->data['lastname'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['lastname'] : null);
		} else {
			$this->data['lastname'] = '';
		}
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['company']))) {
			$this->data['company'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['company'] : null);			
		} else {
			$this->data['company'] = '';
		}
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['address_1']))) {
			$this->data['address_1'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['address_1'] : null);			
		} else {
			$this->data['address_1'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['address_2']))) {
			$this->data['address_2'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['address_2'] : null);			
		} else {
			$this->data['address_2'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['postcode']))) {
			$this->data['postcode'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['postcode'] : null);	
		} elseif ($this->session->has('shipping_postcode')) {
			$this->data['postcode'] = $this->session->get('shipping_postcode');								
		} else {
			$this->data['postcode'] = '';
		}
		
		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['city']))) {
			$this->data['city'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['city'] : null);			
		} else {
			$this->data['city'] = '';
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['country_id']))) {
			$this->data['country_id'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['country_id'] : null);			  	
		} elseif ($this->session->has('shipping_country_id')) {
			$this->data['country_id'] = $this->session->get('shipping_country_id');		
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if ((($_tmp = $this->session->get('guest')) && isset($_tmp['shipping']['zone_id']))) {
			$this->data['zone_id'] = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping']['zone_id'] : null);	
		} elseif ($this->session->has('shipping_zone_id')) {
			$this->data['zone_id'] = $this->session->get('shipping_zone_id');						
		} else {
			$this->data['zone_id'] = '';
		}
					
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->view->pick('checkout/guest_shipping');
		
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
			
			if ((utf8_strlen($this->request->getPostE('address_1')) < 3) || (utf8_strlen($this->request->getPostE('address_1')) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}
	
			if ((utf8_strlen($this->request->getPostE('city')) < 2) || (utf8_strlen($this->request->getPostE('city')) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}
			
			$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
			
			$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('country_id'));
			
			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->getPostE('postcode')) < 2) || (utf8_strlen($this->request->getPostE('postcode')) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}
	
			if ($this->request->getPostE('country_id') == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}
			
			if (!$this->request->hasPost('zone_id') || $this->request->getPostE('zone_id') == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}	
		}
		
		if (!$json) {
			{ $_tmp = $this->session->get('guest'); $_tmp['firstname'] = trim($this->request->getPostE('firstname')); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['lastname'] = trim($this->request->getPostE('lastname')); $this->session->set('guest', $_tmp); }
			{ $_tmp = $this->session->get('guest'); $_tmp['company'] = trim($this->request->getPostE('company')); $this->session->set('guest', $_tmp); }
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
			
			$this->session->set('shipping_country_id', $this->request->getPostE('country_id'));
			$this->session->set('shipping_zone_id', $this->request->getPostE('zone_id'));
			$this->session->set('shipping_postcode', $this->request->getPostE('postcode'));	
			
			$this->session->remove('shipping_method');	
			$this->session->remove('shipping_methods');
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;		
	}
}
?>
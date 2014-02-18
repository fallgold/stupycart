<?php 

namespace Stupycart\Frontend\Controllers\Checkout;

class ShippingMethodController extends \Stupycart\Frontend\Controllers\ControllerBase {
  	public function indexAction() {
		$this->language->load('checkout/checkout');
		
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
		
		if ($this->customer->isLogged() && $this->session->has('shipping_address_id')) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->get('shipping_address_id'));		
		} elseif ($this->session->has('guest')) {
			$shipping_address = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping'] : null);
		}
		
		if (!empty($shipping_address)) {
			// Shipping Methods
			$quote_data = array();
			
			$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
			
			$results = $this->model_setting_extension->getExtensions('shipping');
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$_model_class_name = "\\Stupycart\\Common\\Models\\Shipping\\". ucfirst(\Phalcon\Text::camelize($result['code']));
		$this->{'model_shipping_'. $result['code']} = new $_model_class_name;
					
					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address); 
		
					if ($quote) {
						$quote_data[$result['code']] = array( 
							'title'      => $quote['title'],
							'quote'      => $quote['quote'], 
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}
	
			$sort_order = array();
		  
			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $quote_data);
			
			$this->session->set('shipping_methods', $quote_data);
		}
					
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_comments'] = $this->language->get('text_comments');
	
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		if ((!$this->session->get('shipping_methods'))) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
		}	
					
		if ($this->session->has('shipping_methods')) {
			$this->data['shipping_methods'] = $this->session->get('shipping_methods'); 
		} else {
			$this->data['shipping_methods'] = array();
		}
		
		if ((($_tmp = $this->session->get('shipping_method')) && isset($_tmp['code']))) {
			$this->data['code'] = (($_tmp = $this->session->get('shipping_method')) ? $_tmp['code'] : null);
		} else {
			$this->data['code'] = '';
		}
		
		if ($this->session->has('comment')) {
			$this->data['comment'] = $this->session->get('comment');
		} else {
			$this->data['comment'] = '';
		}
			
		$this->view->pick('checkout/shipping_method');
		
		$this->view->setVars($this->data);
  	}
	
	public function validateAction() {
		$this->language->load('checkout/checkout');
		
		$json = array();		
		
		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}
		
		// Validate if shipping address has been set.		
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();

		if ($this->customer->isLogged() && $this->session->has('shipping_address_id')) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->get('shipping_address_id'));		
		} elseif ($this->session->has('guest')) {
			$shipping_address = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping'] : null);
		}
		
		if (empty($shipping_address)) {								
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
			if (!$this->request->hasPost('shipping_method')) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			} else {
				$shipping = explode('.', $this->request->getPostE('shipping_method'));
					
				if (!isset($shipping[0]) || !isset($shipping[1]) || !(($_tmp = $this->session->get('shipping_methods')) && isset($_tmp[$shipping[0]]['quote'][$shipping[1]]))) {			
					$json['error']['warning'] = $this->language->get('error_shipping');
				}
			}
			
			if (!$json) {
				$shipping = explode('.', $this->request->getPostE('shipping_method'));
					
				$this->session->set('shipping_method', (($_tmp = $this->session->get('shipping_methods')) ? $_tmp[$shipping[0]]['quote'][$shipping[1]] : null));
				
				$this->session->set('comment', strip_tags($this->request->getPostE('comment')));
			}							
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;	
	}
}
?>
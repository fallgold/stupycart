<?php  

namespace Stupycart\Frontend\Controllers\Checkout;

class PaymentMethodController extends \Stupycart\Frontend\Controllers\ControllerBase {
  	public function indexAction() {
		$this->language->load('checkout/checkout');
		
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
		
		if ($this->customer->isLogged() && $this->session->has('payment_address_id')) {
			$payment_address = $this->model_account_address->getAddress($this->session->get('payment_address_id'));		
		} elseif ($this->session->has('guest')) {
			$payment_address = (($_tmp = $this->session->get('guest')) ? $_tmp['payment'] : null);
		}	
		
		if (!empty($payment_address)) {
			// Totals
			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
			$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$_model_class_name = "\\Stupycart\\Common\\Models\\Total\\". ucfirst(\Phalcon\Text::camelize($result['code']));
		$this->{'model_total_'. $result['code']} = new $_model_class_name;
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}
			
			// Payment Methods
			$method_data = array();
			
			$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
			
			$results = $this->model_setting_extension->getExtensions('payment');
	
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$_model_class_name = "\\Stupycart\\Common\\Models\\Payment\\". ucfirst(\Phalcon\Text::camelize($result['code']));
		$this->{'model_payment_'. $result['code']} = new $_model_class_name;
					
					$method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total); 
					
					if ($method) {
						$method_data[$result['code']] = $method;
					}
				}
			}

			$sort_order = array(); 
		  
			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $method_data);			
			
			$this->session->set('payment_methods', $method_data);	
			
		}			
		
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_comments'] = $this->language->get('text_comments');

		$this->data['button_continue'] = $this->language->get('button_continue');
   
		if ((!$this->session->get('payment_methods'))) {
			$this->data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
		} else {
			$this->data['error_warning'] = '';
		}	

		if ($this->session->has('payment_methods')) {
			$this->data['payment_methods'] = $this->session->get('payment_methods'); 
		} else {
			$this->data['payment_methods'] = array();
		}
	  
		if ((($_tmp = $this->session->get('payment_method')) && isset($_tmp['code']))) {
			$this->data['code'] = (($_tmp = $this->session->get('payment_method')) ? $_tmp['code'] : null);
		} else {
			$this->data['code'] = '';
		}
		
		if ($this->session->has('comment')) {
			$this->data['comment'] = $this->session->get('comment');
		} else {
			$this->data['comment'] = '';
		}
		
		if ($this->config->get('config_checkout_id')) {
			$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		if ($this->session->has('agree')) { 
			$this->data['agree'] = $this->session->get('agree');
		} else {
			$this->data['agree'] = '';
		}
			
		$this->view->pick('checkout/payment_method');
		
		$this->view->setVars($this->data);
  	}
	
	public function validateAction() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		// Validate if payment address has been set.
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
		
		if ($this->customer->isLogged() && $this->session->has('payment_address_id')) {
			$payment_address = $this->model_account_address->getAddress($this->session->get('payment_address_id'));		
		} elseif ($this->session->has('guest')) {
			$payment_address = (($_tmp = $this->session->get('guest')) ? $_tmp['payment'] : null);
		}	
				
		if (empty($payment_address)) {
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
			if (!$this->request->hasPost('payment_method')) {
				$json['error']['warning'] = $this->language->get('error_payment');
			} elseif (!(($_tmp = $this->session->get('payment_methods')) && isset($_tmp[$this->request->getPostE('payment_method'))])) {
				$json['error']['warning'] = $this->language->get('error_payment');
			}	
							
			if ($this->config->get('config_checkout_id')) {
				$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
				
				if ($information_info && !$this->request->hasPost('agree')) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
			
			if (!$json) {
				$this->session->set('payment_method', (($_tmp = $this->session->get('payment_methods')) ? $_tmp[$this->request->getPostE('payment_method')] : null));
			  
				$this->session->set('comment', strip_tags($this->request->getPostE('comment')));
			}							
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;
	}
}
?>
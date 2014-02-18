<?php 

namespace Stupycart\Frontend\Controllers\Checkout;

class ConfirmController extends \Stupycart\Frontend\Controllers\ControllerBase { 
	public function indexAction() {
		$redirect = '';
		
		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.		
			$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
	
			if ($this->customer->isLogged() && $this->session->has('shipping_address_id')) {					
				$shipping_address = $this->model_account_address->getAddress($this->session->get('shipping_address_id'));		
			} elseif ($this->session->has('guest')) {
				$shipping_address = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping'] : null);
			}
			
			if (empty($shipping_address)) {								
				$redirect = $this->url->link('checkout/checkout', '', 'SSL');
			}
			
			// Validate if shipping method has been set.	
			if (!$this->session->has('shipping_method')) {
				$redirect = $this->url->link('checkout/checkout', '', 'SSL');
			}
		} else {
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
		}
		
		// Validate if payment address has been set.
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
		
		if ($this->customer->isLogged() && $this->session->has('payment_address_id')) {
			$payment_address = $this->model_account_address->getAddress($this->session->get('payment_address_id'));		
		} elseif ($this->session->has('guest')) {
			$payment_address = (($_tmp = $this->session->get('guest')) ? $_tmp['payment'] : null);
		}	
				
		if (empty($payment_address)) {
			$redirect = $this->url->link('checkout/checkout', '', 'SSL');
		}			
		
		// Validate if payment method has been set.	
		if (!$this->session->has('payment_method')) {
			$redirect = $this->url->link('checkout/checkout', '', 'SSL');
		}
					
		// Validate cart has products and has stock.	
		if ((!$this->cart->hasProducts() && (!$this->session->get('vouchers'))) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkout/cart');				
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
				$redirect = $this->url->link('checkout/cart');
				
				break;
			}				
		}
						
		if (!$redirect) {
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
			
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $total_data);
	
			$this->language->load('checkout/checkout');
			
			$data = array();
			
			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');
			
			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');		
			} else {
				$data['store_url'] = HTTP_SERVER;	
			}
			
			if ($this->customer->isLogged()) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->customer->getCustomerGroupId();
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
				$data['fax'] = $this->customer->getFax();
			
				$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
				
				$payment_address = $this->model_account_address->getAddress($this->session->get('payment_address_id'));
			} elseif ($this->session->has('guest')) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = (($_tmp = $this->session->get('guest')) ? $_tmp['customer_group_id'] : null);
				$data['firstname'] = (($_tmp = $this->session->get('guest')) ? $_tmp['firstname'] : null);
				$data['lastname'] = (($_tmp = $this->session->get('guest')) ? $_tmp['lastname'] : null);
				$data['email'] = (($_tmp = $this->session->get('guest')) ? $_tmp['email'] : null);
				$data['telephone'] = (($_tmp = $this->session->get('guest')) ? $_tmp['telephone'] : null);
				$data['fax'] = (($_tmp = $this->session->get('guest')) ? $_tmp['fax'] : null);
				
				$payment_address = (($_tmp = $this->session->get('guest')) ? $_tmp['payment'] : null);
			}
			
			$data['payment_firstname'] = $payment_address['firstname'];
			$data['payment_lastname'] = $payment_address['lastname'];	
			$data['payment_company'] = $payment_address['company'];	
			$data['payment_company_id'] = $payment_address['company_id'];	
			$data['payment_tax_id'] = $payment_address['tax_id'];	
			$data['payment_address_1'] = $payment_address['address_1'];
			$data['payment_address_2'] = $payment_address['address_2'];
			$data['payment_city'] = $payment_address['city'];
			$data['payment_postcode'] = $payment_address['postcode'];
			$data['payment_zone'] = $payment_address['zone'];
			$data['payment_zone_id'] = $payment_address['zone_id'];
			$data['payment_country'] = $payment_address['country'];
			$data['payment_country_id'] = $payment_address['country_id'];
			$data['payment_address_format'] = $payment_address['address_format'];
		
			if ((($_tmp = $this->session->get('payment_method')) && isset($_tmp['title']))) {
				$data['payment_method'] = (($_tmp = $this->session->get('payment_method')) ? $_tmp['title'] : null);
			} else {
				$data['payment_method'] = '';
			}
			
			if ((($_tmp = $this->session->get('payment_method')) && isset($_tmp['code']))) {
				$data['payment_code'] = (($_tmp = $this->session->get('payment_method')) ? $_tmp['code'] : null);
			} else {
				$data['payment_code'] = '';
			}
						
			if ($this->cart->hasShipping()) {
				if ($this->customer->isLogged()) {
					$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
					
					$shipping_address = $this->model_account_address->getAddress($this->session->get('shipping_address_id'));	
				} elseif ($this->session->has('guest')) {
					$shipping_address = (($_tmp = $this->session->get('guest')) ? $_tmp['shipping'] : null);
				}			
				
				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];	
				$data['shipping_company'] = $shipping_address['company'];	
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];
			
				if ((($_tmp = $this->session->get('shipping_method')) && isset($_tmp['title']))) {
					$data['shipping_method'] = (($_tmp = $this->session->get('shipping_method')) ? $_tmp['title'] : null);
				} else {
					$data['shipping_method'] = '';
				}
				
				if ((($_tmp = $this->session->get('shipping_method')) && isset($_tmp['code']))) {
					$data['shipping_code'] = (($_tmp = $this->session->get('shipping_method')) ? $_tmp['code'] : null);
				} else {
					$data['shipping_code'] = '';
				}				
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';	
				$data['shipping_company'] = '';	
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}
			
			$product_data = array();
		
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
	
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$value = $this->encryption->decrypt($option['option_value']);
					}	
					
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],								   
						'name'                    => $option['name'],
						'value'                   => $value,
						'type'                    => $option['type']
					);					
				}
	 
				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				); 
			}
			
			// Gift Voucher
			$voucher_data = array();
			
			if (!(!$this->session->get('vouchers'))) {
				foreach ($this->session->get('vouchers') as $voucher) {
					$voucher_data[] = array(
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],						
						'amount'           => $voucher['amount']
					);
				}
			}  
						
			$data['products'] = $product_data;
			$data['vouchers'] = $voucher_data;
			$data['totals'] = $total_data;
			$data['comment'] = $this->session->get('comment');
			$data['total'] = $total;
			
			if ($this->cookies->has('tracking')) {
				$this->model_affiliate_affiliate = new \Stupycart\Common\Models\Affiliate\Affiliate();
				
				$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->cookies->get('tracking')->getValue());
				$subtotal = $this->cart->getSubTotal();
				
				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id']; 
					$data['commission'] = ($subtotal / 100) * $affiliate_info['commission']; 
				} else {
					$data['affiliate_id'] = 0;
					$data['commission'] = 0;
				}
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
			}
			
			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId();
			$data['currency_code'] = $this->currency->getCode();
			$data['currency_value'] = $this->currency->getValue($this->currency->getCode());
			$data['ip'] = $this->request->getServer('REMOTE_ADDR');
			
			if (!(!$this->request->getServer('HTTP_X_FORWARDED_FOR'))) {
				$data['forwarded_ip'] = $this->request->getServer('HTTP_X_FORWARDED_FOR');	
			} elseif(!(!$this->request->getServer('HTTP_CLIENT_IP'))) {
				$data['forwarded_ip'] = $this->request->getServer('HTTP_CLIENT_IP');	
			} else {
				$data['forwarded_ip'] = '';
			}
			
			if ($this->request->hasServer('HTTP_USER_AGENT')) {
				$data['user_agent'] = $this->request->getServer('HTTP_USER_AGENT');	
			} else {
				$data['user_agent'] = '';
			}
			
			if ($this->request->hasServer('HTTP_ACCEPT_LANGUAGE')) {
				$data['accept_language'] = $this->request->getServer('HTTP_ACCEPT_LANGUAGE');	
			} else {
				$data['accept_language'] = '';
			}
						
			$this->model_checkout_order = new \Stupycart\Common\Models\Checkout\Order();
			
			$this->session->set('order_id', $this->model_checkout_order->addOrder($data));
			
			$this->data['column_name'] = $this->language->get('column_name');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
	
			$this->data['products'] = array();
	
			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();
	
				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];	
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);
						
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
										
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}  
	 
				$this->data['products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
					'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				); 
			} 
			
			// Gift Voucher
			$this->data['vouchers'] = array();
			
			if (!(!$this->session->get('vouchers'))) {
				foreach ($this->session->get('vouchers') as $voucher) {
					$this->data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'])
					);
				}
			}  
						
			$this->data['totals'] = $total_data;
	
			$this->data['payment'] = $this->getChild('payment/' . (($_tmp = $this->session->get('payment_method')) ? $_tmp['code'] : null));
		} else {
			$this->data['redirect'] = $redirect;
		}			
		
		$this->view->pick('checkout/confirm');
		
		$this->view->setVars($this->data);	
  	}
}
?>
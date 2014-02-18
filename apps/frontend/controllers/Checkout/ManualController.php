<?php 

namespace Stupycart\Frontend\Controllers\Checkout;

class ManualController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->language->load('checkout/manual');
		
		$json = array();
			
		
		$this->user = new \Libs\Opencart\User($this->registry);
				
		if ($this->user->isLogged() && $this->user->hasPermission('modify', 'sale/order')) {	
			// Reset everything
			$this->cart->clear();
			$this->customer->logout();
			
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');			
			$this->session->remove('payment_method');
			$this->session->remove('payment_methods');
			$this->session->remove('coupon');
			$this->session->remove('reward');
			$this->session->remove('voucher');
			$this->session->remove('vouchers');

			// Settings
			$this->model_setting_setting = new \Stupycart\Common\Models\Setting\Setting();
			
			$settings = $this->model_setting_setting->getSetting('config', $this->request->getPostE('store_id'));
			
			foreach ($settings as $key => $value) {
				$this->config->set($key, $value);
			}
			
    		// Customer
			if ($this->request->getPostE('customer_id')) {
				$this->model_account_customer = new \Stupycart\Common\Models\Account\Customer();

				$customer_info = $this->model_account_customer->getCustomer($this->request->getPostE('customer_id'));

				if ($customer_info) {
					$this->customer->login($customer_info['email'], '', true);
					$this->cart->clear();
				} else {
					$json['error']['customer'] = $this->language->get('error_customer');
				}
			} else {
				// Customer Group
				$this->config->set('config_customer_group_id', $this->request->getPostE('customer_group_id'));
			}
	
			// Product
			$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
			
			if ($this->request->hasPost('order_product')) {
				foreach ($this->request->getPostE('order_product') as $order_product) {
					$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);
				
					if ($product_info) {	
						$option_data = array();
						
						if (isset($order_product['order_option'])) {
							foreach ($order_product['order_option'] as $option) {
								if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image') { 
									$option_data[$option['product_option_id']] = $option['product_option_value_id'];
								} elseif ($option['type'] == 'checkbox') {
									$option_data[$option['product_option_id']][] = $option['product_option_value_id'];
								} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
									$option_data[$option['product_option_id']] = $option['value'];						
								}
							}
						}
															
						$this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
					}
				}
			}
			
			if ($this->request->hasPost('product_id')) {
				$product_info = $this->model_catalog_product->getProduct($this->request->getPostE('product_id'));
				
				if ($product_info) {
					if ($this->request->hasPost('quantity')) {
						$quantity = $this->request->getPostE('quantity');
					} else {
						$quantity = 1;
					}
																
					if ($this->request->hasPost('option')) {
						$option = array_filter($this->request->getPostE('option'));
					} else {
						$option = array();	
					}
					
					$product_options = $this->model_catalog_product->getProductOptions($this->request->getPostE('product_id'));
					
					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['product']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
						}
					}
					
					if (!isset($json['error']['product']['option'])) {
						$this->cart->add($this->request->getPostE('product_id'), $quantity, $option);
					}
				}
			}
			
			// Stock
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$json['error']['product']['stock'] = $this->language->get('error_stock');
			}		
			
			// Tax
			if ($this->cart->hasShipping()) {
				$this->tax->setShippingAddress($this->request->getPostE('shipping_country_id'), $this->request->getPostE('shipping_zone_id'));
			} else {
				$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
			}
			
			$this->tax->setPaymentAddress($this->request->getPostE('payment_country_id'), $this->request->getPostE('payment_zone_id'));				
			$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));	
						
			// Products
			$json['order_product'] = array();
			
			$products = $this->cart->getProducts();
			
			foreach ($products as $product) {
				$product_total = 0;
					
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}	
								
				if ($product['minimum'] > $product_total) {
					$json['error']['product']['minimum'][] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}	
								
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['option_value'],
						'type'                    => $option['type']
					);
				}
		
				$download_data = array();
				
				foreach ($product['download'] as $download) {
					$download_data[] = array(
						'name'      => $download['name'],
						'filename'  => $download['filename'],
						'mask'      => $download['mask'],
						'remaining' => $download['remaining']
					);
				}
								
				$json['order_product'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'], 
					'option'     => $option_data,
					'download'   => $download_data,
					'quantity'   => $product['quantity'],
					'stock'      => $product['stock'],
					'price'      => $product['price'],	
					'total'      => $product['total'],	
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']				
				);
			}
			
			// Voucher
			$this->session->set('vouchers', array());
			
			if ($this->request->hasPost('order_voucher')) {
				foreach ($this->request->getPostE('order_voucher') as $voucher) {
					(($_tmp = $this->session->get('vouchers')) ? $_tmp[] : null) = array(
						'voucher_id'       => $voucher['voucher_id'],
						'description'      => $voucher['description'],
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'], 
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']    
					);
				}
			}

			// Add a new voucher if set
			if ($this->request->hasPost('from_name') && $this->request->hasPost('from_email') && $this->request->hasPost('to_name') && $this->request->hasPost('to_email') && $this->request->hasPost('amount')) {
				if ((utf8_strlen($this->request->getPostE('from_name')) < 1) || (utf8_strlen($this->request->getPostE('from_name')) > 64)) {
					$json['error']['vouchers']['from_name'] = $this->language->get('error_from_name');
				}  
			
				if ((utf8_strlen($this->request->getPostE('from_email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('from_email'))) {
					$json['error']['vouchers']['from_email'] = $this->language->get('error_email');
				}
			
				if ((utf8_strlen($this->request->getPostE('to_name')) < 1) || (utf8_strlen($this->request->getPostE('to_name')) > 64)) {
					$json['error']['vouchers']['to_name'] = $this->language->get('error_to_name');
				}       
			
				if ((utf8_strlen($this->request->getPostE('to_email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('to_email'))) {
					$json['error']['vouchers']['to_email'] = $this->language->get('error_email');
				}
			
				if (($this->request->getPostE('amount') < 1) || ($this->request->getPostE('amount') > 1000)) {
					$json['error']['vouchers']['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format(1, false, 1), $this->currency->format(1000, false, 1) . ' ' . $this->config->get('config_currency'));
				}
			
				if (!isset($json['error']['vouchers'])) { 
					$voucher_data = array(
						'order_id'         => 0,
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $this->request->getPostE('from_name'),
						'from_email'       => $this->request->getPostE('from_email'),
						'to_name'          => $this->request->getPostE('to_name'),
						'to_email'         => $this->request->getPostE('to_email'),
						'voucher_theme_id' => $this->request->getPostE('voucher_theme_id'), 
						'message'          => $this->request->getPostE('message'),
						'amount'           => $this->request->getPostE('amount'),
						'status'           => true             
					); 
					
					$this->model_checkout_voucher = new \Stupycart\Common\Models\Checkout\Voucher();
					
					$voucher_id = $this->model_checkout_voucher->addVoucher(0, $voucher_data);  
									
					(($_tmp = $this->session->get('vouchers')) ? $_tmp[] : null) = array(
						'voucher_id'       => $voucher_id,
						'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->request->getPostE('amount'), $this->config->get('config_currency')), $this->request->getPostE('to_name')),
						'code'             => substr(md5(mt_rand()), 0, 10),
						'from_name'        => $this->request->getPostE('from_name'),
						'from_email'       => $this->request->getPostE('from_email'),
						'to_name'          => $this->request->getPostE('to_name'),
						'to_email'         => $this->request->getPostE('to_email'),
						'voucher_theme_id' => $this->request->getPostE('voucher_theme_id'), 
						'message'          => $this->request->getPostE('message'),
						'amount'           => $this->request->getPostE('amount')            
					); 
				}
			}
			
			$json['order_voucher'] = array();
					
			foreach ($this->session->get('vouchers') as $voucher) {
				$json['order_voucher'][] = array(
					'voucher_id'       => $voucher['voucher_id'],
					'description'      => $voucher['description'],
					'code'             => $voucher['code'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'], 
					'message'          => $voucher['message'],
					'amount'           => $voucher['amount']    
				);
			}
						
			$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
			
			$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
			$this->model_localisation_zone = new \Stupycart\Common\Models\Localisation\Zone();
			
			// Shipping
			$json['shipping_method'] = array();
			
			if ($this->cart->hasShipping()) {		
				$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
				
				$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('shipping_country_id'));
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->getPostE('shipping_postcode')) < 2) || (utf8_strlen($this->request->getPostE('shipping_postcode')) > 10)) {
					$json['error']['shipping']['postcode'] = $this->language->get('error_postcode');
				}
		
				if ($this->request->getPostE('shipping_country_id') == '') {
					$json['error']['shipping']['country'] = $this->language->get('error_country');
				}
				
				if (!$this->request->hasPost('shipping_zone_id') || $this->request->getPostE('shipping_zone_id') == '') {
					$json['error']['shipping']['zone'] = $this->language->get('error_zone');
				}
							
				$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
				
				$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('shipping_country_id'));
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->getPostE('shipping_postcode')) < 2) || (utf8_strlen($this->request->getPostE('shipping_postcode')) > 10)) {
					$json['error']['shipping']['postcode'] = $this->language->get('error_postcode');
				}

				if (!isset($json['error']['shipping'])) {
					if ($country_info) {
						$country = $country_info['name'];
						$iso_code_2 = $country_info['iso_code_2'];
						$iso_code_3 = $country_info['iso_code_3'];
						$address_format = $country_info['address_format'];
					} else {
						$country = '';
						$iso_code_2 = '';
						$iso_code_3 = '';	
						$address_format = '';
					}
				
					$zone_info = $this->model_localisation_zone->getZone($this->request->getPostE('shipping_zone_id'));
					
					if ($zone_info) {
						$zone = $zone_info['name'];
						$zone_code = $zone_info['code'];
					} else {
						$zone = '';
						$zone_code = '';
					}					
	
					$address_data = array(
						'firstname'      => $this->request->getPostE('shipping_firstname'),
						'lastname'       => $this->request->getPostE('shipping_lastname'),
						'company'        => $this->request->getPostE('shipping_company'),
						'address_1'      => $this->request->getPostE('shipping_address_1'),
						'address_2'      => $this->request->getPostE('shipping_address_2'),
						'postcode'       => $this->request->getPostE('shipping_postcode'),
						'city'           => $this->request->getPostE('shipping_city'),
						'zone_id'        => $this->request->getPostE('shipping_zone_id'),
						'zone'           => $zone,
						'zone_code'      => $zone_code,
						'country_id'     => $this->request->getPostE('shipping_country_id'),
						'country'        => $country,	
						'iso_code_2'     => $iso_code_2,
						'iso_code_3'     => $iso_code_3,
						'address_format' => $address_format
					);
					
					$results = $this->model_setting_extension->getExtensions('shipping');
					
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$_model_class_name = "\\Stupycart\\Common\\Models\\Shipping\\". ucfirst(\Phalcon\Text::camelize($result['code']));
		$this->{'model_shipping_'. $result['code']} = new $_model_class_name;
							
							$quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_data); 
				
							if ($quote) {
								$json['shipping_method'][$result['code']] = array( 
									'title'      => $quote['title'],
									'quote'      => $quote['quote'], 
									'sort_order' => $quote['sort_order'],
									'error'      => $quote['error']
								);
							}
						}
					}
			
					$sort_order = array();
				  
					foreach ($json['shipping_method'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
			
					array_multisort($sort_order, SORT_ASC, $json['shipping_method']);

					if (!$json['shipping_method']) {
						$json['error']['shipping_method'] = $this->language->get('error_no_shipping');
					} elseif ($this->request->getPostE('shipping_code')) {
						$shipping = explode('.', $this->request->getPostE('shipping_code'));
						
						if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($json['shipping_method'][$shipping[0]]['quote'][$shipping[1]])) {		
							$json['error']['shipping_method'] = $this->language->get('error_shipping');
						} else {
							$this->session->set('shipping_method', $json['shipping_method'][$shipping[0]]['quote'][$shipping[1]]);
						}				
					}					
				}
			}
			
			// Coupon
			if (!(!$this->request->getPostE('coupon'))) {
				$this->model_checkout_coupon = new \Stupycart\Common\Models\Checkout\Coupon();
			
				$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->getPostE('coupon'));			
			
				if ($coupon_info) {					
					$this->session->set('coupon', $this->request->getPostE('coupon'));
				} else {
					$json['error']['coupon'] = $this->language->get('error_coupon');
				}
			}
			
			// Voucher
			if (!(!$this->request->getPostE('voucher'))) {
				$this->model_checkout_voucher = new \Stupycart\Common\Models\Checkout\Voucher();
			
				$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->getPostE('voucher'));			
			
				if ($voucher_info) {					
					$this->session->set('voucher', $this->request->getPostE('voucher'));
				} else {
					$json['error']['voucher'] = $this->language->get('error_voucher');
				}
			}
						
			// Reward Points
			if (!(!$this->request->getPostE('reward'))) {
				$points = $this->customer->getRewardPoints();
				
				if ($this->request->getPostE('reward') > $points) {
					$json['error']['reward'] = sprintf($this->language->get('error_points'), $this->request->getPostE('reward'));
				}
				
				if (!isset($json['error']['reward'])) {
					$points_total = 0;
					
					foreach ($this->cart->getProducts() as $product) {
						if ($product['points']) {
							$points_total += $product['points'];
						}
					}				
					
					if ($this->request->getPostE('reward') > $points_total) {
						$json['error']['reward'] = sprintf($this->language->get('error_maximum'), $points_total);
					}
					
					if (!isset($json['error']['reward'])) {		
						$this->session->set('reward', $this->request->getPostE('reward'));
					}
				}
			}

			// Totals
			$json['order_total'] = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
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
		
					$this->{'model_total_' . $result['code']}->getTotal($json['order_total'], $total, $taxes);
				}
				
				$sort_order = array(); 
			  
				foreach ($json['order_total'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
	
				array_multisort($sort_order, SORT_ASC, $json['order_total']);				
			}
		
			// Payment
			if ($this->request->getPostE('payment_country_id') == '') {
				$json['error']['payment']['country'] = $this->language->get('error_country');
			}
			
			if (!$this->request->hasPost('payment_zone_id') || $this->request->getPostE('payment_zone_id') == '') {
				$json['error']['payment']['zone'] = $this->language->get('error_zone');
			}		
			
			if (!isset($json['error']['payment'])) {
				$json['payment_methods'] = array();
				
				$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('payment_country_id'));
				
				if ($country_info) {
					$country = $country_info['name'];
					$iso_code_2 = $country_info['iso_code_2'];
					$iso_code_3 = $country_info['iso_code_3'];
					$address_format = $country_info['address_format'];
				} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';	
					$address_format = '';
				}
				
				$zone_info = $this->model_localisation_zone->getZone($this->request->getPostE('payment_zone_id'));
				
				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
				} else {
					$zone = '';
					$zone_code = '';
				}					
				
				$address_data = array(
					'firstname'      => $this->request->getPostE('payment_firstname'),
					'lastname'       => $this->request->getPostE('payment_lastname'),
					'company'        => $this->request->getPostE('payment_company'),
					'address_1'      => $this->request->getPostE('payment_address_1'),
					'address_2'      => $this->request->getPostE('payment_address_2'),
					'postcode'       => $this->request->getPostE('payment_postcode'),
					'city'           => $this->request->getPostE('payment_city'),
					'zone_id'        => $this->request->getPostE('payment_zone_id'),
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => $this->request->getPostE('payment_country_id'),
					'country'        => $country,	
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format
				);
				
				$json['payment_method'] = array();
								
				$results = $this->model_setting_extension->getExtensions('payment');
		
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$_model_class_name = "\\Stupycart\\Common\\Models\\Payment\\". ucfirst(\Phalcon\Text::camelize($result['code']));
		$this->{'model_payment_'. $result['code']} = new $_model_class_name;
						
						$method = $this->{'model_payment_' . $result['code']}->getMethod($address_data, $total); 
						
						if ($method) {
							$json['payment_method'][$result['code']] = $method;
						}
					}
				}
							 
				$sort_order = array(); 
			  
				foreach ($json['payment_method'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
		
				array_multisort($sort_order, SORT_ASC, $json['payment_method']);	
				
				if (!$json['payment_method']) {
					$json['error']['payment_method'] = $this->language->get('error_no_payment');
				} elseif ($this->request->getPostE('payment_code')) {			
					if (!isset($json['payment_method'][$this->request->getPostE('payment_code')])) {
						$json['error']['payment_method'] = $this->language->get('error_payment');
					}
				}
			}
			
			if (!isset($json['error'])) { 
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error']['warning'] = $this->language->get('error_warning');
			}
			
			// Reset everything
			$this->cart->clear();
			$this->customer->logout();
			
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
			$this->session->remove('payment_method');
			$this->session->remove('payment_methods');
			$this->session->remove('coupon');
			$this->session->remove('reward');
			$this->session->remove('voucher');
			$this->session->remove('vouchers');
		} else {
      		$json['error']['warning'] = $this->language->get('error_permission');
		}
	
		$this->response->setContent(json_encode($json));
		return $this->response;	
	}
}
?>
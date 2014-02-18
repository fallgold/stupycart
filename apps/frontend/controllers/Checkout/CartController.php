<?php 

namespace Stupycart\Frontend\Controllers\Checkout;

class CartController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();
	
	public function indexAction() {
		$this->language->load('checkout/cart');

		if (!$this->session->has('vouchers')) {
			$this->session->set('vouchers', array());
		}
		
		// Update
		if (!(!$this->request->getPostE('quantity'))) {
			foreach ($this->request->getPostE('quantity') as $key => $value) {
				$this->cart->update($key, $value);
			}
			
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
			$this->session->remove('payment_method');
			$this->session->remove('payment_methods'); 
			$this->session->remove('reward');
			
			$this->response->redirect($this->url->link('checkout/cart'), true);
		return;  			
		}
       	
		// Remove
		if ($this->request->hasQuery('remove')) {
			$this->cart->remove($this->request->getQueryE('remove'));
			
			{ $_tmp = $this->session->get('vouchers'); unset($_tmp[$this->request->getQueryE('remove')]); $this->session->set('vouchers', $_tmp); }
			
			$this->session->set('success', $this->language->get('text_remove'));
		
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
			$this->session->remove('payment_method');
			$this->session->remove('payment_methods'); 
			$this->session->remove('reward');  
								
			$this->response->redirect($this->url->link('checkout/cart'), true);
		return;
		}
			
		// Coupon    
		if ($this->request->hasPost('coupon') && $this->validateCoupon()) { 
			$this->session->set('coupon', $this->request->getPostE('coupon'));
				
			$this->session->set('success', $this->language->get('text_coupon'));
			
			$this->response->redirect($this->url->link('checkout/cart'), true);
		return;
		}
		
		// Voucher
		if ($this->request->hasPost('voucher') && $this->validateVoucher()) { 
			$this->session->set('voucher', $this->request->getPostE('voucher'));
				
			$this->session->set('success', $this->language->get('text_voucher'));
				
			$this->response->redirect($this->url->link('checkout/cart'), true);
		return;
		}

		// Reward
		if ($this->request->hasPost('reward') && $this->validateReward()) { 
			$this->session->set('reward', abs($this->request->getPostE('reward')));
				
			$this->session->set('success', $this->language->get('text_reward'));
				
			$this->response->redirect($this->url->link('checkout/cart'), true);
		return;
		}
		
		// Shipping
		if ($this->request->hasPost('shipping_method') && $this->validateShipping()) {
			$shipping = explode('.', $this->request->getPostE('shipping_method'));
			
			$this->session->set('shipping_method', (($_tmp = $this->session->get('shipping_methods')) ? $_tmp[$shipping[0]]['quote'][$shipping[1]] : null));
			
			$this->session->set('success', $this->language->get('text_shipping'));
			
			$this->response->redirect($this->url->link('checkout/cart'), true);
		return;
		}
		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('js/jquery/colorbox/jquery.colorbox-min.js');
		$this->document->addStyle('js/jquery/colorbox/colorbox.css');
			
      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('checkout/cart'),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	if ($this->cart->hasProducts() || !(!$this->session->get('vouchers'))) {
			$points = $this->customer->getRewardPoints();
			
			$points_total = 0;
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['points']) {
					$points_total += $product['points'];
				}
			}		
				
      		$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_next'] = $this->language->get('text_next');
			$this->data['text_next_choice'] = $this->language->get('text_next_choice');
     		$this->data['text_use_coupon'] = $this->language->get('text_use_coupon');
			$this->data['text_use_voucher'] = $this->language->get('text_use_voucher');
			$this->data['text_use_reward'] = sprintf($this->language->get('text_use_reward'), $points);
			$this->data['text_shipping_estimate'] = $this->language->get('text_shipping_estimate');
			$this->data['text_shipping_detail'] = $this->language->get('text_shipping_detail');
			$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_none'] = $this->language->get('text_none');
						
			$this->data['column_image'] = $this->language->get('column_image');
      		$this->data['column_name'] = $this->language->get('column_name');
      		$this->data['column_model'] = $this->language->get('column_model');
      		$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
      		$this->data['column_total'] = $this->language->get('column_total');
			
			$this->data['entry_coupon'] = $this->language->get('entry_coupon');
			$this->data['entry_voucher'] = $this->language->get('entry_voucher');
			$this->data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_postcode'] = $this->language->get('entry_postcode');
						
			$this->data['button_update'] = $this->language->get('button_update');
			$this->data['button_remove'] = $this->language->get('button_remove');
			$this->data['button_coupon'] = $this->language->get('button_coupon');
			$this->data['button_voucher'] = $this->language->get('button_voucher');
			$this->data['button_reward'] = $this->language->get('button_reward');
			$this->data['button_quote'] = $this->language->get('button_quote');
			$this->data['button_shipping'] = $this->language->get('button_shipping');			
      		$this->data['button_shopping'] = $this->language->get('button_shopping');
      		$this->data['button_checkout'] = $this->language->get('button_checkout');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} elseif (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
      			$this->data['error_warning'] = $this->language->get('error_stock');		
			} else {
				$this->data['error_warning'] = '';
			}
			
			if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
				$this->data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$this->data['attention'] = '';
			}
						
			if ($this->session->has('success')) {
				$this->data['success'] = $this->session->get('success');
			
				$this->session->remove('success');
			} else {
				$this->data['success'] = '';
			}
			
			$this->data['action'] = $this->url->link('checkout/cart');   
						
			if ($this->config->get('config_cart_weight')) {
				$this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$this->data['weight'] = '';
			}
						 
			$this->model_tool_image = new \Stupycart\Common\Models\Tool\Image();
			
      		$this->data['products'] = array();
			
			$products = $this->cart->getProducts();

      		foreach ($products as $product) {
				$product_total = 0;
					
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}			
				
				if ($product['minimum'] > $product_total) {
					$this->data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}				
					
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$image = '';
				}

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
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
				} else {
					$total = false;
				}
				
        		$this->data['products'][] = array(
          			'key'      => $product['key'],
          			'thumb'    => $image,
					'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'quantity' => $product['quantity'],
          			'stock'    => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'   => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'    => $price,
					'total'    => $total,
					'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'remove'   => $this->url->link('checkout/cart', 'remove=' . $product['key'])
				);
      		}
			
			// Gift Voucher
			$this->data['vouchers'] = array();
			
			if (!(!$this->session->get('vouchers'))) {
				foreach ($this->session->get('vouchers') as $key => $voucher) {
					$this->data['vouchers'][] = array(
						'key'         => $key,
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount']),
						'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)   
					);
				}
			}

			if ($this->request->hasPost('next')) {
				$this->data['next'] = $this->request->getPostE('next');
			} else {
				$this->data['next'] = '';
			}
						 
			$this->data['coupon_status'] = $this->config->get('coupon_status');
			
			if ($this->request->hasPost('coupon')) {
				$this->data['coupon'] = $this->request->getPostE('coupon');			
			} elseif ($this->session->has('coupon')) {
				$this->data['coupon'] = $this->session->get('coupon');
			} else {
				$this->data['coupon'] = '';
			}
			
			$this->data['voucher_status'] = $this->config->get('voucher_status');
			
			if ($this->request->hasPost('voucher')) {
				$this->data['voucher'] = $this->request->getPostE('voucher');				
			} elseif ($this->session->has('voucher')) {
				$this->data['voucher'] = $this->session->get('voucher');
			} else {
				$this->data['voucher'] = '';
			}
			
			$this->data['reward_status'] = ($points && $points_total && $this->config->get('reward_status'));
			
			if ($this->request->hasPost('reward')) {
				$this->data['reward'] = $this->request->getPostE('reward');				
			} elseif ($this->session->has('reward')) {
				$this->data['reward'] = $this->session->get('reward');
			} else {
				$this->data['reward'] = '';
			}

			$this->data['shipping_status'] = $this->config->get('shipping_status') && $this->config->get('shipping_estimator') && $this->cart->hasShipping();	
								
			if ($this->request->hasPost('country_id')) {
				$this->data['country_id'] = $this->request->getPostE('country_id');				
			} elseif ($this->session->has('shipping_country_id')) {
				$this->data['country_id'] = $this->session->get('shipping_country_id');			  	
			} else {
				$this->data['country_id'] = $this->config->get('config_country_id');
			}
				
			$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
			
			$this->data['countries'] = $this->model_localisation_country->getCountries();
						
			if ($this->request->hasPost('zone_id')) {
				$this->data['zone_id'] = $this->request->getPostE('zone_id');				
			} elseif ($this->session->has('shipping_zone_id')) {
				$this->data['zone_id'] = $this->session->get('shipping_zone_id');			
			} else {
				$this->data['zone_id'] = '';
			}
			
			if ($this->request->hasPost('postcode')) {
				$this->data['postcode'] = $this->request->getPostE('postcode');				
			} elseif ($this->session->has('shipping_postcode')) {
				$this->data['postcode'] = $this->session->get('shipping_postcode');					
			} else {
				$this->data['postcode'] = '';
			}
			
			if ($this->request->hasPost('shipping_method')) {
				$this->data['shipping_method'] = $this->request->getPostE('shipping_method');				
			} elseif ($this->session->has('shipping_method')) {
				$this->data['shipping_method'] = (($_tmp = $this->session->get('shipping_method')) ? $_tmp['code'] : null); 
			} else {
				$this->data['shipping_method'] = '';
			}
						
			// Totals
			$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
			
			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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
					
					$sort_order = array(); 
				  
					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
		
					array_multisort($sort_order, SORT_ASC, $total_data);			
				}
			}
			
			$this->data['totals'] = $total_data;
						
			$this->data['continue'] = $this->url->link('common/home');
						
			$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

			$this->view->pick('checkout/cart');
			
		$this->_commonAction();
						
			$this->view->setVars($this->data);					
    	} else {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_error'] = $this->language->get('text_empty');

      		$this->data['button_continue'] = $this->language->get('button_continue');
			
      		$this->data['continue'] = $this->url->link('common/home');

			$this->session->remove('success');

			$this->view->pick('error/not_found');
			
		$this->_commonAction();
					
			$this->view->setVars($this->data);			
    	}
  	}
	
	protected function validateCoupon() {
		$this->model_checkout_coupon = new \Stupycart\Common\Models\Checkout\Coupon();
				
		$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->getPostE('coupon'));			
		
		if (!$coupon_info) {			
			$this->error['warning'] = $this->language->get('error_coupon');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}		
	}
	
	protected function validateVoucher() {
		$this->model_checkout_voucher = new \Stupycart\Common\Models\Checkout\Voucher();
				
		$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->getPostE('voucher'));			
		
		if (!$voucher_info) {			
			$this->error['warning'] = $this->language->get('error_voucher');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}		
	}
	
	protected function validateReward() {
		$points = $this->customer->getRewardPoints();
		
		$points_total = 0;
		
		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}	
				
		if ((!$this->request->getPostE('reward'))) {
			$this->error['warning'] = $this->language->get('error_reward');
		}
	
		if ($this->request->getPostE('reward') > $points) {
			$this->error['warning'] = sprintf($this->language->get('error_points'), $this->request->getPostE('reward'));
		}
		
		if ($this->request->getPostE('reward') > $points_total) {
			$this->error['warning'] = sprintf($this->language->get('error_maximum'), $points_total);
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}		
	}
	
	protected function validateShipping() {
		if (!(!$this->request->getPostE('shipping_method'))) {
			$shipping = explode('.', $this->request->getPostE('shipping_method'));
					
			if (!isset($shipping[0]) || !isset($shipping[1]) || !(($_tmp = $this->session->get('shipping_methods')) && isset($_tmp[$shipping[0]]['quote'][$shipping[1]]))) {			
				$this->error['warning'] = $this->language->get('error_shipping');
			}
		} else {
			$this->error['warning'] = $this->language->get('error_shipping');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}		
	}
								
	public function addAction() {
		$this->language->load('checkout/cart');
		
		$json = array();
		
		if ($this->request->hasPost('product_id')) {
			$product_id = $this->request->getPostE('product_id');
		} else {
			$product_id = 0;
		}
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
						
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
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
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}
			
			if (!$json) {
				$this->cart->add($this->request->getPostE('product_id'), $quantity, $option);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->getPostE('product_id')), $product_info['name'], $this->url->link('checkout/cart'));
				
				$this->session->remove('shipping_method');
				$this->session->remove('shipping_methods');
				$this->session->remove('payment_method');
				$this->session->remove('payment_methods');
				
				// Totals
				$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
				
				$total_data = array();					
				$total = 0;
				$taxes = $this->cart->getTaxes();
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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
						
						$sort_order = array(); 
					  
						foreach ($total_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}
			
						array_multisort($sort_order, SORT_ASC, $total_data);			
					}
				}
				
				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + ($this->session->has('vouchers') ? count($this->session->get('vouchers')) : 0), $this->currency->format($total));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->getPostE('product_id')));
			}
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;		
	}
	
	public function quoteAction() {
		$this->language->load('checkout/cart');
		
		$json = array();	
		
		if (!$this->cart->hasProducts()) {
			$json['error']['warning'] = $this->language->get('error_product');				
		}				

		if (!$this->cart->hasShipping()) {
			$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));				
		}				
		
		if ($this->request->getPostE('country_id') == '') {
			$json['error']['country'] = $this->language->get('error_country');
		}
		
		if (!$this->request->hasPost('zone_id') || $this->request->getPostE('zone_id') == '') {
			$json['error']['zone'] = $this->language->get('error_zone');
		}
			
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
		$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('country_id'));
		
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->getPostE('postcode')) < 2) || (utf8_strlen($this->request->getPostE('postcode')) > 10)) {
			$json['error']['postcode'] = $this->language->get('error_postcode');
		}
						
		if (!$json) {		
			$this->tax->setShippingAddress($this->request->getPostE('country_id'), $this->request->getPostE('zone_id'));
		
			// Default Shipping Address
			$this->session->set('shipping_country_id', $this->request->getPostE('country_id'));
			$this->session->set('shipping_zone_id', $this->request->getPostE('zone_id'));
			$this->session->set('shipping_postcode', $this->request->getPostE('postcode'));
		
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
			
			$this->model_localisation_zone = new \Stupycart\Common\Models\Localisation\Zone();
		
			$zone_info = $this->model_localisation_zone->getZone($this->request->getPostE('zone_id'));
			
			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}	
		 
			$address_data = array(
				'firstname'      => '',
				'lastname'       => '',
				'company'        => '',
				'address_1'      => '',
				'address_2'      => '',
				'postcode'       => $this->request->getPostE('postcode'),
				'city'           => '',
				'zone_id'        => $this->request->getPostE('zone_id'),
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $this->request->getPostE('country_id'),
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);
		
			$quote_data = array();
			
			$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
			
			$results = $this->model_setting_extension->getExtensions('shipping');
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$_model_class_name = "\\Stupycart\\Common\\Models\\Shipping\\". ucfirst(\Phalcon\Text::camelize($result['code']));
		$this->{'model_shipping_'. $result['code']} = new $_model_class_name;
					
					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_data); 
		
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
			
			if ($this->session->get('shipping_methods')) {
				$json['shipping_method'] = $this->session->get('shipping_methods'); 
			} else {
				$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
			}				
		}	
		
		$this->response->setContent(json_encode($json));
		return $this->response;						
	}
	
	public function countryAction() {
		$json = array();
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();

    	$country_info = $this->model_localisation_country->getCountry($this->request->getQueryE('country_id'));
		
		if ($country_info) {
			$this->model_localisation_zone = new \Stupycart\Common\Models\Localisation\Zone();

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->getQueryE('country_id')),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;
	}
}
?>

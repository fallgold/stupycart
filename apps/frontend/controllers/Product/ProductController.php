<?php  

namespace Stupycart\Frontend\Controllers\Product;

class ProductController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array(); 
	
	public function indexAction() { 
		$this->language->load('product/product');
	
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),			
			'separator' => false
		);
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();	
		
		if ($this->request->hasQuery('path')) {
			$path = '';
			
			$parts = explode('_', (string)$this->request->getQueryE('path'));
			
			$category_id = (int)array_pop($parts);
				
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}
				
				$category_info = $this->model_catalog_category->getCategory($path_id);
				
				if ($category_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
			
			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);
				
			if ($category_info) {			
				$url = '';
				
				if ($this->request->hasQuery('sort')) {
					$url .= '&sort=' . $this->request->getQueryE('sort');
				}	
	
				if ($this->request->hasQuery('order')) {
					$url .= '&order=' . $this->request->getQueryE('order');
				}	
				
				if ($this->request->hasQuery('page')) {
					$url .= '&page=' . $this->request->getQueryE('page');
				}
				
				if ($this->request->hasQuery('limit')) {
					$url .= '&limit=' . $this->request->getQueryE('limit');
				}
										
				$this->data['breadcrumbs'][] = array(
					'text'      => $category_info['name'],
					'href'      => $this->url->link('product/category', 'path=' . $this->request->getQueryE('path')),
					'separator' => $this->language->get('text_separator')
				);
			}
		}
		
		$this->model_catalog_manufacturer = new \Stupycart\Common\Models\Catalog\Manufacturer();	
		
		if ($this->request->hasQuery('manufacturer_id')) {
			$this->data['breadcrumbs'][] = array( 
				'text'      => $this->language->get('text_brand'),
				'href'      => $this->url->link('product/manufacturer'),
				'separator' => $this->language->get('text_separator')
			);	
	
			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}	

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}	
			
			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
						
			if ($this->request->hasQuery('limit')) {
				$url .= '&limit=' . $this->request->getQueryE('limit');
			}
							
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->getQueryE('manufacturer_id'));

			if ($manufacturer_info) {	
				$this->data['breadcrumbs'][] = array(
					'text'	    => $manufacturer_info['name'],
					'href'	    => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->getQueryE('manufacturer_id') . $url),					
					'separator' => $this->language->get('text_separator')
				);
			}
		}
		
		if ($this->request->hasQuery('search') || $this->request->hasQuery('tag')) {
			$url = '';
			
			if ($this->request->hasQuery('search')) {
				$url .= '&search=' . $this->request->getQueryE('search');
			}
						
			if ($this->request->hasQuery('tag')) {
				$url .= '&tag=' . $this->request->getQueryE('tag');
			}
						
			if ($this->request->hasQuery('description')) {
				$url .= '&description=' . $this->request->getQueryE('description');
			}
			
			if ($this->request->hasQuery('category_id')) {
				$url .= '&category_id=' . $this->request->getQueryE('category_id');
			}	

			if ($this->request->hasQuery('sub_category')) {
				$url .= '&sub_category=' . $this->request->getQueryE('sub_category');
			}

			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}	

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}
			
			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
						
			if ($this->request->hasQuery('limit')) {
				$url .= '&limit=' . $this->request->getQueryE('limit');
			}
												
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_search'),
				'href'      => $this->url->link('product/search', $url),
				'separator' => $this->language->get('text_separator')
			); 	
		}
		
		if ($this->request->hasQuery('product_id')) {
			$product_id = (int)$this->request->getQueryE('product_id');
		} else {
			$product_id = 0;
		}
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {
			$url = '';
			
			if ($this->request->hasQuery('path')) {
				$url .= '&path=' . $this->request->getQueryE('path');
			}
						
			if ($this->request->hasQuery('filter')) {
				$url .= '&filter=' . $this->request->getQueryE('filter');
			}
						
			if ($this->request->hasQuery('manufacturer_id')) {
				$url .= '&manufacturer_id=' . $this->request->getQueryE('manufacturer_id');
			}			

			if ($this->request->hasQuery('search')) {
				$url .= '&search=' . $this->request->getQueryE('search');
			}
						
			if ($this->request->hasQuery('tag')) {
				$url .= '&tag=' . $this->request->getQueryE('tag');
			}
			
			if ($this->request->hasQuery('description')) {
				$url .= '&description=' . $this->request->getQueryE('description');
			}	
						
			if ($this->request->hasQuery('category_id')) {
				$url .= '&category_id=' . $this->request->getQueryE('category_id');
			}
			
			if ($this->request->hasQuery('sub_category')) {
				$url .= '&sub_category=' . $this->request->getQueryE('sub_category');
			}	
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}	

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}	
			
			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
						
			if ($this->request->hasQuery('limit')) {
				$url .= '&limit=' . $this->request->getQueryE('limit');
			}
																		
			$this->data['breadcrumbs'][] = array(
				'text'      => $product_info['name'],
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $this->request->getQueryE('product_id')),
				'separator' => $this->language->get('text_separator')
			);			
			
			$this->document->setTitle($product_info['name']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->getQueryE('product_id')), 'canonical');
			$this->document->addScript('js/jquery/tabs.js');
			$this->document->addScript('js/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('js/jquery/colorbox/colorbox.css');
			
			$this->data['heading_title'] = $product_info['name'];
			
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_reward'] = $this->language->get('text_reward');
			$this->data['text_points'] = $this->language->get('text_points');	
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_stock'] = $this->language->get('text_stock');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_option'] = $this->language->get('text_option');
			$this->data['text_qty'] = $this->language->get('text_qty');
			$this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$this->data['text_or'] = $this->language->get('text_or');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['text_share'] = $this->language->get('text_share');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_tags'] = $this->language->get('text_tags');
			
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');			
			$this->data['button_upload'] = $this->language->get('button_upload');
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->model_catalog_review = new \Stupycart\Common\Models\Catalog\Review();

			$this->data['tab_description'] = $this->language->get('tab_description');
			$this->data['tab_attribute'] = $this->language->get('tab_attribute');
			$this->data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			$this->data['tab_related'] = $this->language->get('tab_related');
			
			$this->data['product_id'] = $this->request->getQueryE('product_id');
			$this->data['manufacturer'] = $product_info['manufacturer'];
			$this->data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$this->data['model'] = $product_info['model'];
			$this->data['reward'] = $product_info['reward'];
			$this->data['points'] = $product_info['points'];
			
			if ($product_info['quantity'] <= 0) {
				$this->data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$this->data['stock'] = $product_info['quantity'];
			} else {
				$this->data['stock'] = $this->language->get('text_instock');
			}
			
			$this->model_tool_image = new \Stupycart\Common\Models\Tool\Image();

			if ($product_info['image']) {
				$this->data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$this->data['popup'] = '';
			}
			
			if ($product_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
			} else {
				$this->data['thumb'] = '';
			}
			
			$this->data['images'] = array();
			
			$results = $this->model_catalog_product->getProductImages($this->request->getQueryE('product_id'));
			
			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
				);
			}	
						
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}
						
			if ((float)$product_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}
			
			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$this->data['tax'] = false;
			}
			
			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->getQueryE('product_id'));
			
			$this->data['discounts'] = array(); 
			
			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}
			
			$this->data['options'] = array();
			
			foreach ($this->model_catalog_product->getProductOptions($this->request->getQueryE('product_id')) as $option) { 
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
					$option_value_data = array();
					
					foreach ($option['option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
							} else {
								$price = false;
							}
							
							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}
					
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required']
					);					
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$this->data['options'][] = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option['option_value'],
						'required'          => $option['required']
					);						
				}
			}
							
			if ($product_info['minimum']) {
				$this->data['minimum'] = $product_info['minimum'];
			} else {
				$this->data['minimum'] = 1;
			}
			
			$this->data['review_status'] = $this->config->get('config_review_status');
			$this->data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$this->data['rating'] = (int)$product_info['rating'];
			$this->data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->getQueryE('product_id'));
			
			$this->data['products'] = array();
			
			$results = $this->model_catalog_product->getProductRelated($this->request->getQueryE('product_id'));
			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
						
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
							
				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}	
			
			$this->data['tags'] = array();
			
			if ($product_info['tag']) {		
				$tags = explode(',', $product_info['tag']);
				
				foreach ($tags as $tag) {
					$this->data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}
			
			$this->model_catalog_product->updateViewed($this->request->getQueryE('product_id'));
			
			$this->view->pick('product/product');
			
		$this->_commonAction();
						
			$this->view->setVars($this->data);
		} else {
			$url = '';
			
			if ($this->request->hasQuery('path')) {
				$url .= '&path=' . $this->request->getQueryE('path');
			}
						
			if ($this->request->hasQuery('filter')) {
				$url .= '&filter=' . $this->request->getQueryE('filter');
			}	
						
			if ($this->request->hasQuery('manufacturer_id')) {
				$url .= '&manufacturer_id=' . $this->request->getQueryE('manufacturer_id');
			}			

			if ($this->request->hasQuery('search')) {
				$url .= '&search=' . $this->request->getQueryE('search');
			}	
					
			if ($this->request->hasQuery('tag')) {
				$url .= '&tag=' . $this->request->getQueryE('tag');
			}
							
			if ($this->request->hasQuery('description')) {
				$url .= '&description=' . $this->request->getQueryE('description');
			}
					
			if ($this->request->hasQuery('category_id')) {
				$url .= '&category_id=' . $this->request->getQueryE('category_id');
			}
			
			if ($this->request->hasQuery('sub_category')) {
				$url .= '&sub_category=' . $this->request->getQueryE('sub_category');
			}	
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}	

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}	
			
			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
						
			if ($this->request->hasQuery('limit')) {
				$url .= '&limit=' . $this->request->getQueryE('limit');
			}
														
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/product', $url . '&product_id=' . $product_id),
        		'separator' => $this->language->get('text_separator')
      		);			
		
      		$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			$this->view->pick('error/not_found');
			
		$this->_commonAction();
						
			$this->view->setVars($this->data);
    	}
  	}
	
	public function reviewAction() {
    	$this->language->load('product/product');
		
		$this->model_catalog_review = new \Stupycart\Common\Models\Catalog\Review();

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}  
		
		$this->data['reviews'] = array();
		
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->getQueryE('product_id'));
			
		$results = $this->model_catalog_review->getReviewsByProductId($this->request->getQueryE('product_id'), ($page - 1) * 5, 5);
      		
		foreach ($results as $result) {
        	$this->data['reviews'][] = array(
        		'author'     => $result['author'],
				'text'       => $result['text'],
				'rating'     => (int)$result['rating'],
        		'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
			
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->getQueryE('product_id') . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->view->pick('product/review');
		
		$this->view->setVars($this->data);
	}
	
	public function writeAction() {
		$this->language->load('product/product');
		
		$this->model_catalog_review = new \Stupycart\Common\Models\Catalog\Review();
		
		$json = array();
		
		if ($this->request->getServer('REQUEST_METHOD') == 'POST') {
			if ((utf8_strlen($this->request->getPostE('name')) < 3) || (utf8_strlen($this->request->getPostE('name')) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}
			
			if ((utf8_strlen($this->request->getPostE('text')) < 25) || (utf8_strlen($this->request->getPostE('text')) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}
	
			if ((!$this->request->getPostE('rating'))) {
				$json['error'] = $this->language->get('error_rating');
			}
	
			if ((!$this->session->get('captcha')) || ($this->session->get('captcha') != $this->request->getPostE('captcha'))) {
				$json['error'] = $this->language->get('error_captcha');
			}
				
			if (!isset($json['error'])) {
				$this->model_catalog_review->addReview($this->request->getQueryE('product_id'), $this->request->getPostE());
				
				$json['success'] = $this->language->get('text_success');
			}
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;
	}
	
	public function captchaAction() {
		
		$captcha = new \Libs\Opencart\Captcha();
		
		$this->session->set('captcha', $captcha->getCode());
		
		$captcha->showImage();
	}
	
	public function uploadAction() {
		$this->language->load('product/product');
		
		$json = array();
		
		if (!empty($_FILES['file']['name'])) {
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($_FILES['file']['name'], ENT_QUOTES, 'UTF-8')));
			
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
        		$json['error'] = $this->language->get('error_filename');
	  		}	  	

			// Allowed file extension types
			$allowed = array();
			
			$filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));
			
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
       		}	
			
			// Allowed file mime types		
		    $allowed = array();
			
			$filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));
			
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
							
			if (!in_array($_FILES['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}
						
			if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $_FILES['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		
		if (!$json && is_uploaded_file($_FILES['file']['tmp_name']) && file_exists($_FILES['file']['tmp_name'])) {
			$file = basename($filename) . '.' . md5(mt_rand());
			
			// Hide the uploaded file name so people can not link to it directly.
			$json['file'] = $this->encryption->encrypt($file);
			
			move_uploaded_file($_FILES['file']['tmp_name'], DIR_DOWNLOAD . $file);
						
			$json['success'] = $this->language->get('text_upload');
		}	
		
		$this->response->setContent(json_encode($json));
		return $this->response;		
	}
}
?>
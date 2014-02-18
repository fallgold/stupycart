<?php  

namespace Stupycart\Frontend\Controllers\Product;

class CompareController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() { 
		$this->language->load('product/compare');
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();

		$this->model_tool_image = new \Stupycart\Common\Models\Tool\Image();
		
		if (!$this->session->has('compare')) {
			$this->session->set('compare', array());
		}	
				
		if ($this->request->hasQuery('remove')) {
			$key = array_search($this->request->getQueryE('remove'), (($_tmp = $this->session->get('compare')) ? $_tmp : null));
				
			if ($key !== false) {
				{ $_tmp = $this->session->get('compare'); unset($_tmp[$key]); $this->session->set('compare', $_tmp); }
			}
		
			$this->session->set('success', $this->language->get('text_remove'));
		
			$this->response->redirect($this->url->link('product/compare'), true);
		return;
		}
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),			
			'separator' => false
		);
				
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/compare'),			
			'separator' => $this->language->get('text_separator')
		);	
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_name'] = $this->language->get('text_name');
		$this->data['text_image'] = $this->language->get('text_image');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_availability'] = $this->language->get('text_availability');
		$this->data['text_rating'] = $this->language->get('text_rating');
		$this->data['text_summary'] = $this->language->get('text_summary');
		$this->data['text_weight'] = $this->language->get('text_weight');
		$this->data['text_dimension'] = $this->language->get('text_dimension');
		$this->data['text_empty'] = $this->language->get('text_empty');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
			
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['review_status'] = $this->config->get('config_review_status');
		
		$this->data['products'] = array();
		
		$this->data['attribute_groups'] = array();

		foreach ($this->session->get('compare') as $key => $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_compare_width'), $this->config->get('config_image_compare_height'));
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
			
				if ($product_info['quantity'] <= 0) {
					$availability = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$availability = $product_info['quantity'];
				} else {
					$availability = $this->language->get('text_instock');
				}				
				
				$attribute_data = array();
				
				$attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);
				
				foreach ($attribute_groups as $attribute_group) {
					foreach ($attribute_group['attribute'] as $attribute) {
						$attribute_data[$attribute['attribute_id']] = $attribute['text'];
					}
				}
															
				$this->data['products'][$product_id] = array(
					'product_id'   => $product_info['product_id'],
					'name'         => $product_info['name'],
					'thumb'        => $image,
					'price'        => $price,
					'special'      => $special,
					'description'  => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..',
					'model'        => $product_info['model'],
					'manufacturer' => $product_info['manufacturer'],
					'availability' => $availability,
					'rating'       => (int)$product_info['rating'],
					'reviews'      => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'weight'       => $this->weight->format($product_info['weight'], $product_info['weight_class_id']),
					'length'       => $this->length->format($product_info['length'], $product_info['length_class_id']),
					'width'        => $this->length->format($product_info['width'], $product_info['length_class_id']),
					'height'       => $this->length->format($product_info['height'], $product_info['length_class_id']),
					'attribute'    => $attribute_data,
					'href'         => $this->url->link('product/product', 'product_id=' . $product_id),
					'remove'       => $this->url->link('product/compare', 'remove=' . $product_id)
				);
				
				foreach ($attribute_groups as $attribute_group) {
					$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];
					
					foreach ($attribute_group['attribute'] as $attribute) {
						$this->data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
					}
				}
			} else {
				{ $_tmp = $this->session->get('compare'); unset($_tmp[$key]); $this->session->set('compare', $_tmp); }
			}
		}
		
		$this->data['continue'] = $this->url->link('common/home');
		
		$this->view->pick('product/compare');
		
		$this->_commonAction();
		
		$this->view->setVars($this->data);
  	}
	
	public function addAction() {
		$this->language->load('product/compare');
		
		$json = array();

		if (!$this->session->has('compare')) {
			$this->session->set('compare', array());
		}
				
		if ($this->request->hasPost('product_id')) {
			$product_id = $this->request->getPostE('product_id');
		} else {
			$product_id = 0;
		}
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {
			if (!in_array($this->request->getPostE('product_id'), $this->session->get('compare'))) {	
				if (count($this->session->get('compare')) >= 4) {
					array_shift($this->session->get('compare'));
				}
				
				{ $_tmp = $this->session->get('compare'); $_tmp[] = $this->request->getPostE('product_id'); $this->session->set('compare', $_tmp); }
			}
			 
			$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->getPostE('product_id')), $product_info['name'], $this->url->link('product/compare'));				
		
			$json['total'] = sprintf($this->language->get('text_compare'), ($this->session->has('compare') ? count($this->session->get('compare')) : 0));
		}	

		$this->response->setContent(json_encode($json));
		return $this->response;
	}
}
?>
<?php 

namespace Stupycart\Frontend\Controllers\Account;

class WishListController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->link('account/wishlist', '', 'SSL'));

	  		$this->response->redirect($this->url->link('account/login', '', 'SSL'), true);
		return; 
    	}    	
		
		$this->language->load('account/wishlist');
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		
		$this->model_tool_image = new \Stupycart\Common\Models\Tool\Image();
		
		if (!$this->session->has('wishlist')) {
			$this->session->set('wishlist', array());
		}
		
		if ($this->request->hasQuery('remove')) {
			$key = array_search($this->request->getQueryE('remove'), (($_tmp = $this->session->get('wishlist')) ? $_tmp : null));
			
			if ($key !== false) {
				{ $_tmp = $this->session->get('wishlist'); unset($_tmp[$key]); $this->session->set('wishlist', $_tmp); }
			}
		
			$this->session->set('success', $this->language->get('text_remove'));
		
			$this->response->redirect($this->url->link('account/wishlist'), true);
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
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/wishlist'),
        	'separator' => $this->language->get('text_separator')
      	);
								
		$this->data['heading_title'] = $this->language->get('heading_title');	
		
		$this->data['text_empty'] = $this->language->get('text_empty');
     	
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_stock'] = $this->language->get('column_stock');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
			
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}
							
		$this->data['products'] = array();
	
		foreach ($this->session->get('wishlist') as $key => $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
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
																			
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'remove'     => $this->url->link('account/wishlist', 'remove=' . $product_info['product_id'])
				);
			} else {
				{ $_tmp = $this->session->get('wishlist'); unset($_tmp[$key]); $this->session->set('wishlist', $_tmp); }
			}
		}	

		$this->data['continue'] = $this->url->link('account/account', '', 'SSL');
		
		$this->view->pick('account/wishlist');
		
		$this->_commonAction();
							
		$this->view->setVars($this->data);		
	}
	
	public function addAction() {
		$this->language->load('account/wishlist');
		
		$json = array();

		if (!$this->session->has('wishlist')) {
			$this->session->set('wishlist', array());
		}
				
		if ($this->request->hasPost('product_id')) {
			$product_id = $this->request->getPostE('product_id');
		} else {
			$product_id = 0;
		}
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {
			if (!in_array($this->request->getPostE('product_id'), $this->session->get('wishlist'))) {	
				{ $_tmp = $this->session->get('wishlist'); $_tmp[] = $this->request->getPostE('product_id'); $this->session->set('wishlist', $_tmp); }
			}
			 
			if ($this->customer->isLogged()) {			
				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->getPostE('product_id')), $product_info['name'], $this->url->link('account/wishlist'));				
			} else {
				$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'), $this->url->link('product/product', 'product_id=' . $this->request->getPostE('product_id')), $product_info['name'], $this->url->link('account/wishlist'));				
			}
			
			$json['total'] = sprintf($this->language->get('text_wishlist'), ($this->session->has('wishlist') ? count($this->session->get('wishlist')) : 0));
		}	
		
		$this->response->setContent(json_encode($json));
		return $this->response;
	}	
}
?>
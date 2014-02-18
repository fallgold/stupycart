<?php 

namespace Stupycart\Admin\Controllers\Catalog;

class ProductController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
     
  	public function indexAction() {
		$this->language->load('catalog/product');
    	
		$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		
		$this->getList();
  	}
  
  	public function insertAction() {
    	$this->language->load('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->getPostE());
	  		
			$this->session->set('success', $this->language->get('text_success'));
	  
			$url = '';
			
			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
		
			if ($this->request->hasQuery('filter_model')) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->getQueryE('filter_model'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_price')) {
				$url .= '&filter_price=' . $this->request->getQueryE('filter_price');
			}
			
			if ($this->request->hasQuery('filter_quantity')) {
				$url .= '&filter_quantity=' . $this->request->getQueryE('filter_quantity');
			}
			
			if ($this->request->hasQuery('filter_status')) {
				$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
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
			
			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getForm();
  	}

  	public function updateAction() {
    	$this->language->load('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
	
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->editProduct($this->request->getQueryE('product_id'), $this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));
			
			$url = '';
			
			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
		
			if ($this->request->hasQuery('filter_model')) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->getQueryE('filter_model'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_price')) {
				$url .= '&filter_price=' . $this->request->getQueryE('filter_price');
			}
			
			if ($this->request->hasQuery('filter_quantity')) {
				$url .= '&filter_quantity=' . $this->request->getQueryE('filter_quantity');
			}	
		
			if ($this->request->hasQuery('filter_status')) {
				$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
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
			
			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

    	$this->getForm();
  	}

  	public function deleteAction() {
    	$this->language->load('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
	  		}

			$this->session->set('success', $this->language->get('text_success'));
			
			$url = '';
			
			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
		
			if ($this->request->hasQuery('filter_model')) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->getQueryE('filter_model'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_price')) {
				$url .= '&filter_price=' . $this->request->getQueryE('filter_price');
			}
			
			if ($this->request->hasQuery('filter_quantity')) {
				$url .= '&filter_quantity=' . $this->request->getQueryE('filter_quantity');
			}	
		
			if ($this->request->hasQuery('filter_status')) {
				$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
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
			
			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

    	$this->getList();
  	}

  	public function copyAction() {
    	$this->language->load('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		
		if ($this->request->hasPost('selected') && $this->validateCopy()) {
			foreach ($this->request->getPostE('selected') as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
	  		}

			$this->session->set('success', $this->language->get('text_success'));
			
			$url = '';
			
			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
		  
			if ($this->request->hasQuery('filter_model')) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->getQueryE('filter_model'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_price')) {
				$url .= '&filter_price=' . $this->request->getQueryE('filter_price');
			}
			
			if ($this->request->hasQuery('filter_quantity')) {
				$url .= '&filter_quantity=' . $this->request->getQueryE('filter_quantity');
			}	
		
			if ($this->request->hasQuery('filter_status')) {
				$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
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
			
			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

    	$this->getList();
  	}
	
  	protected function getList() {				
		if ($this->request->hasQuery('filter_name')) {
			$filter_name = $this->request->getQueryE('filter_name');
		} else {
			$filter_name = null;
		}

		if ($this->request->hasQuery('filter_model')) {
			$filter_model = $this->request->getQueryE('filter_model');
		} else {
			$filter_model = null;
		}
		
		if ($this->request->hasQuery('filter_price')) {
			$filter_price = $this->request->getQueryE('filter_price');
		} else {
			$filter_price = null;
		}

		if ($this->request->hasQuery('filter_quantity')) {
			$filter_quantity = $this->request->getQueryE('filter_quantity');
		} else {
			$filter_quantity = null;
		}

		if ($this->request->hasQuery('filter_status')) {
			$filter_status = $this->request->getQueryE('filter_status');
		} else {
			$filter_status = null;
		}

		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'pd.name';
		}
		
		if ($this->request->hasQuery('order')) {
			$order = $this->request->getQueryE('order');
		} else {
			$order = 'ASC';
		}
		
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}
						
		$url = '';
						
		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_model')) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->getQueryE('filter_model'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_price')) {
			$url .= '&filter_price=' . $this->request->getQueryE('filter_price');
		}
		
		if ($this->request->hasQuery('filter_quantity')) {
			$url .= '&filter_quantity=' . $this->request->getQueryE('filter_quantity');
		}		

		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product', 'token=' . $this->session->get('token') . $url, 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->get('token') . $url, 'SSL');	
		$this->data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
    	
		$this->data['products'] = array();

		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		$this->model_tool_image = new \Stupycart\Common\Models\Admin\Tool\Image();
		
		$product_total = $this->model_catalog_product->getTotalProducts($data);
			
		$results = $this->model_catalog_product->getProducts($data);
				    	
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/product/update', 'token=' . $this->session->get('token') . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}
	
			$special = false;
			
			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);
			
			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
					$special = $product_special['price'];
			
					break;
				}					
			}
	
      		$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
				'special'    => $special,
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => $this->request->hasPost('selected') && in_array($result['product_id'], $this->request->getPostE('selected')),
				'action'     => $action
			);
    	}
		
		$this->data['heading_title'] = $this->language->get('heading_title');		
				
		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');		
			
		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_model'] = $this->language->get('column_model');		
		$this->data['column_price'] = $this->language->get('column_price');		
		$this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');		
				
		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_delete'] = $this->language->get('button_delete');		
		$this->data['button_filter'] = $this->language->get('button_filter');
		 
 		$this->data['token'] = $this->session->get('token');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_model')) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->getQueryE('filter_model'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_price')) {
			$url .= '&filter_price=' . $this->request->getQueryE('filter_price');
		}
		
		if ($this->request->hasQuery('filter_quantity')) {
			$url .= '&filter_quantity=' . $this->request->getQueryE('filter_quantity');
		}
		
		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
		}
								
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
					
		$this->data['sort_name'] = $this->url->link('catalog/product', 'token=' . $this->session->get('token') . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('catalog/product', 'token=' . $this->session->get('token') . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('catalog/product', 'token=' . $this->session->get('token') . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('catalog/product', 'token=' . $this->session->get('token') . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/product', 'token=' . $this->session->get('token') . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('catalog/product', 'token=' . $this->session->get('token') . '&sort=p.sort_order' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_model')) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->getQueryE('filter_model'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_price')) {
			$url .= '&filter_price=' . $this->request->getQueryE('filter_price');
		}
		
		if ($this->request->hasQuery('filter_quantity')) {
			$url .= '&filter_quantity=' . $this->request->getQueryE('filter_quantity');
		}

		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
		}

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('catalog/product_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}

  	protected function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
    	$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_sku'] = $this->language->get('entry_sku');
		$this->data['entry_upc'] = $this->language->get('entry_upc');
		$this->data['entry_ean'] = $this->language->get('entry_ean');
		$this->data['entry_jan'] = $this->language->get('entry_jan');
		$this->data['entry_isbn'] = $this->language->get('entry_isbn');
		$this->data['entry_mpn'] = $this->language->get('entry_mpn');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
    	$this->data['entry_shipping'] = $this->language->get('entry_shipping');
    	$this->data['entry_date_available'] = $this->language->get('entry_date_available');
    	$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
    	$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_points'] = $this->language->get('entry_points');
		$this->data['entry_option_points'] = $this->language->get('entry_option_points');
		$this->data['entry_subtract'] = $this->language->get('entry_subtract');
    	$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
    	$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension');
		$this->data['entry_length'] = $this->language->get('entry_length');
    	$this->data['entry_image'] = $this->language->get('entry_image');
    	$this->data['entry_download'] = $this->language->get('entry_download');
    	$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_attribute'] = $this->language->get('entry_attribute');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_reward'] = $this->language->get('entry_reward');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
				
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_attribute'] = $this->language->get('button_add_attribute');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		$this->data['tab_option'] = $this->language->get('tab_option');		
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_special'] = $this->language->get('tab_special');
    	$this->data['tab_image'] = $this->language->get('tab_image');		
		$this->data['tab_links'] = $this->language->get('tab_links');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_design'] = $this->language->get('tab_design');
		 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

 		if (isset($this->error['meta_description'])) {
			$this->data['error_meta_description'] = $this->error['meta_description'];
		} else {
			$this->data['error_meta_description'] = array();
		}		
   
   		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}	
		
   		if (isset($this->error['model'])) {
			$this->data['error_model'] = $this->error['model'];
		} else {
			$this->data['error_model'] = '';
		}		
     	
		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}	

		$url = '';

		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_model')) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->getQueryE('filter_model'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_price')) {
			$url .= '&filter_price=' . $this->request->getQueryE('filter_price');
		}
		
		if ($this->request->hasQuery('filter_quantity')) {
			$url .= '&filter_quantity=' . $this->request->getQueryE('filter_quantity');
		}	
		
		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
									
		if (!$this->request->hasQuery('product_id')) {
			$this->data['action'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/product/update', 'token=' . $this->session->get('token') . '&product_id=' . $this->request->getQueryE('product_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('product_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$product_info = $this->model_catalog_product->getProduct($this->request->getQueryE('product_id'));
    	}

		$this->data['token'] = $this->session->get('token');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('product_description')) {
			$this->data['product_description'] = $this->request->getPostE('product_description');
		} elseif ($this->request->hasQuery('product_id')) {
			$this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->getQueryE('product_id'));
		} else {
			$this->data['product_description'] = array();
		}
		
		if ($this->request->hasPost('model')) {
      		$this->data['model'] = $this->request->getPostE('model');
    	} elseif (!empty($product_info)) {
			$this->data['model'] = $product_info['model'];
		} else {
      		$this->data['model'] = '';
    	}

		if ($this->request->hasPost('sku')) {
      		$this->data['sku'] = $this->request->getPostE('sku');
    	} elseif (!empty($product_info)) {
			$this->data['sku'] = $product_info['sku'];
		} else {
      		$this->data['sku'] = '';
    	}
		
		if ($this->request->hasPost('upc')) {
      		$this->data['upc'] = $this->request->getPostE('upc');
    	} elseif (!empty($product_info)) {
			$this->data['upc'] = $product_info['upc'];
		} else {
      		$this->data['upc'] = '';
    	}
		
		if ($this->request->hasPost('ean')) {
      		$this->data['ean'] = $this->request->getPostE('ean');
    	} elseif (!empty($product_info)) {
			$this->data['ean'] = $product_info['ean'];
		} else {
      		$this->data['ean'] = '';
    	}
		
		if ($this->request->hasPost('jan')) {
      		$this->data['jan'] = $this->request->getPostE('jan');
    	} elseif (!empty($product_info)) {
			$this->data['jan'] = $product_info['jan'];
		} else {
      		$this->data['jan'] = '';
    	}
		
		if ($this->request->hasPost('isbn')) {
      		$this->data['isbn'] = $this->request->getPostE('isbn');
    	} elseif (!empty($product_info)) {
			$this->data['isbn'] = $product_info['isbn'];
		} else {
      		$this->data['isbn'] = '';
    	}
		
		if ($this->request->hasPost('mpn')) {
      		$this->data['mpn'] = $this->request->getPostE('mpn');
    	} elseif (!empty($product_info)) {
			$this->data['mpn'] = $product_info['mpn'];
		} else {
      		$this->data['mpn'] = '';
    	}								
				
		if ($this->request->hasPost('location')) {
      		$this->data['location'] = $this->request->getPostE('location');
    	} elseif (!empty($product_info)) {
			$this->data['location'] = $product_info['location'];
		} else {
      		$this->data['location'] = '';
    	}

		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if ($this->request->hasPost('product_store')) {
			$this->data['product_store'] = $this->request->getPostE('product_store');
		} elseif ($this->request->hasQuery('product_id')) {
			$this->data['product_store'] = $this->model_catalog_product->getProductStores($this->request->getQueryE('product_id'));
		} else {
			$this->data['product_store'] = array(0);
		}	
		
		if ($this->request->hasPost('keyword')) {
			$this->data['keyword'] = $this->request->getPostE('keyword');
		} elseif (!empty($product_info)) {
			$this->data['keyword'] = $product_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if ($this->request->hasPost('image')) {
			$this->data['image'] = $this->request->getPostE('image');
		} elseif (!empty($product_info)) {
			$this->data['image'] = $product_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->model_tool_image = new \Stupycart\Common\Models\Admin\Tool\Image();
		
		if ($this->request->hasPost('image') && file_exists(DIR_IMAGE . $this->request->getPostE('image'))) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->getPostE('image'), 100, 100);
		} elseif (!empty($product_info) && $product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
    	if ($this->request->hasPost('shipping')) {
      		$this->data['shipping'] = $this->request->getPostE('shipping');
    	} elseif (!empty($product_info)) {
      		$this->data['shipping'] = $product_info['shipping'];
    	} else {
			$this->data['shipping'] = 1;
		}
		
    	if ($this->request->hasPost('price')) {
      		$this->data['price'] = $this->request->getPostE('price');
    	} elseif (!empty($product_info)) {
			$this->data['price'] = $product_info['price'];
		} else {
      		$this->data['price'] = '';
    	}
		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
    	
		if ($this->request->hasPost('tax_class_id')) {
      		$this->data['tax_class_id'] = $this->request->getPostE('tax_class_id');
    	} elseif (!empty($product_info)) {
			$this->data['tax_class_id'] = $product_info['tax_class_id'];
		} else {
      		$this->data['tax_class_id'] = 0;
    	}
		      	
		if ($this->request->hasPost('date_available')) {
       		$this->data['date_available'] = $this->request->getPostE('date_available');
		} elseif (!empty($product_info)) {
			$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}
											
    	if ($this->request->hasPost('quantity')) {
      		$this->data['quantity'] = $this->request->getPostE('quantity');
    	} elseif (!empty($product_info)) {
      		$this->data['quantity'] = $product_info['quantity'];
    	} else {
			$this->data['quantity'] = 1;
		}
		
		if ($this->request->hasPost('minimum')) {
      		$this->data['minimum'] = $this->request->getPostE('minimum');
    	} elseif (!empty($product_info)) {
      		$this->data['minimum'] = $product_info['minimum'];
    	} else {
			$this->data['minimum'] = 1;
		}
		
		if ($this->request->hasPost('subtract')) {
      		$this->data['subtract'] = $this->request->getPostE('subtract');
    	} elseif (!empty($product_info)) {
      		$this->data['subtract'] = $product_info['subtract'];
    	} else {
			$this->data['subtract'] = 1;
		}
		
		if ($this->request->hasPost('sort_order')) {
      		$this->data['sort_order'] = $this->request->getPostE('sort_order');
    	} elseif (!empty($product_info)) {
      		$this->data['sort_order'] = $product_info['sort_order'];
    	} else {
			$this->data['sort_order'] = 1;
		}

		$this->model_localisation_stock_status = new \Stupycart\Common\Models\Admin\Localisation\StockStatus();
		
		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
    	
		if ($this->request->hasPost('stock_status_id')) {
      		$this->data['stock_status_id'] = $this->request->getPostE('stock_status_id');
    	} elseif (!empty($product_info)) {
      		$this->data['stock_status_id'] = $product_info['stock_status_id'];
    	} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}
				
    	if ($this->request->hasPost('status')) {
      		$this->data['status'] = $this->request->getPostE('status');
    	} elseif (!empty($product_info)) {
			$this->data['status'] = $product_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}

    	if ($this->request->hasPost('weight')) {
      		$this->data['weight'] = $this->request->getPostE('weight');
		} elseif (!empty($product_info)) {
			$this->data['weight'] = $product_info['weight'];
    	} else {
      		$this->data['weight'] = '';
    	} 
		
		$this->model_localisation_weight_class = new \Stupycart\Common\Models\Admin\Localisation\WeightClass();
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
    	
		if ($this->request->hasPost('weight_class_id')) {
      		$this->data['weight_class_id'] = $this->request->getPostE('weight_class_id');
    	} elseif (!empty($product_info)) {
      		$this->data['weight_class_id'] = $product_info['weight_class_id'];
		} else {
      		$this->data['weight_class_id'] = $this->config->get('config_weight_class_id');
    	}
		
		if ($this->request->hasPost('length')) {
      		$this->data['length'] = $this->request->getPostE('length');
    	} elseif (!empty($product_info)) {
			$this->data['length'] = $product_info['length'];
		} else {
      		$this->data['length'] = '';
    	}
		
		if ($this->request->hasPost('width')) {
      		$this->data['width'] = $this->request->getPostE('width');
		} elseif (!empty($product_info)) {	
			$this->data['width'] = $product_info['width'];
    	} else {
      		$this->data['width'] = '';
    	}
		
		if ($this->request->hasPost('height')) {
      		$this->data['height'] = $this->request->getPostE('height');
		} elseif (!empty($product_info)) {
			$this->data['height'] = $product_info['height'];
    	} else {
      		$this->data['height'] = '';
    	}

		$this->model_localisation_length_class = new \Stupycart\Common\Models\Admin\Localisation\LengthClass();
		
		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
    	
		if ($this->request->hasPost('length_class_id')) {
      		$this->data['length_class_id'] = $this->request->getPostE('length_class_id');
    	} elseif (!empty($product_info)) {
      		$this->data['length_class_id'] = $product_info['length_class_id'];
    	} else {
      		$this->data['length_class_id'] = $this->config->get('config_length_class_id');
		}

		$this->model_catalog_manufacturer = new \Stupycart\Common\Models\Admin\Catalog\Manufacturer();
		
    	if ($this->request->hasPost('manufacturer_id')) {
      		$this->data['manufacturer_id'] = $this->request->getPostE('manufacturer_id');
		} elseif (!empty($product_info)) {
			$this->data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
      		$this->data['manufacturer_id'] = 0;
    	} 		
		
    	if ($this->request->hasPost('manufacturer')) {
      		$this->data['manufacturer'] = $this->request->getPostE('manufacturer');
		} elseif (!empty($product_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
			
			if ($manufacturer_info) {		
				$this->data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$this->data['manufacturer'] = '';
			}	
		} else {
      		$this->data['manufacturer'] = '';
    	} 
		
		// Categories
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
		
		if ($this->request->hasPost('product_category')) {
			$categories = $this->request->getPostE('product_category');
		} elseif ($this->request->hasQuery('product_id')) {		
			$categories = $this->model_catalog_product->getProductCategories($this->request->getQueryE('product_id'));
		} else {
			$categories = array();
		}
	
		$this->data['product_categories'] = array();
		
		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			
			if ($category_info) {
				$this->data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}
		
		// Filters
		$this->model_catalog_filter = new \Stupycart\Common\Models\Admin\Catalog\Filter();
		
		if ($this->request->hasPost('product_filter')) {
			$filters = $this->request->getPostE('product_filter');
		} elseif ($this->request->hasQuery('product_id')) {
			$filters = $this->model_catalog_product->getProductFilters($this->request->getQueryE('product_id'));
		} else {
			$filters = array();
		}
		
		$this->data['product_filters'] = array();
		
		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);
			
			if ($filter_info) {
				$this->data['product_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}		
		
		// Attributes
		$this->model_catalog_attribute = new \Stupycart\Common\Models\Admin\Catalog\Attribute();
		
		if ($this->request->hasPost('product_attribute')) {
			$product_attributes = $this->request->getPostE('product_attribute');
		} elseif ($this->request->hasQuery('product_id')) {
			$product_attributes = $this->model_catalog_product->getProductAttributes($this->request->getQueryE('product_id'));
		} else {
			$product_attributes = array();
		}
		
		$this->data['product_attributes'] = array();
		
		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);
			
			if ($attribute_info) {
				$this->data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
				);
			}
		}		
		
		// Options
		$this->model_catalog_option = new \Stupycart\Common\Models\Admin\Catalog\Option();
		
		if ($this->request->hasPost('product_option')) {
			$product_options = $this->request->getPostE('product_option');
		} elseif ($this->request->hasQuery('product_id')) {
			$product_options = $this->model_catalog_product->getProductOptions($this->request->getQueryE('product_id'));			
		} else {
			$product_options = array();
		}			
		
		$this->data['product_options'] = array();
			
		foreach ($product_options as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();
				
				foreach ($product_option['product_option_value'] as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],						
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']	
					);
				}
				
				$this->data['product_options'][] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'required'             => $product_option['required']
				);				
			} else {
				$this->data['product_options'][] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
		}
		
		$this->data['option_values'] = array();
		
		foreach ($this->data['product_options'] as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (!isset($this->data['option_values'][$product_option['option_id']])) {
					$this->data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
				}
			}
		}
		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		if ($this->request->hasPost('product_discount')) {
			$this->data['product_discounts'] = $this->request->getPostE('product_discount');
		} elseif ($this->request->hasQuery('product_id')) {
			$this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($this->request->getQueryE('product_id'));
		} else {
			$this->data['product_discounts'] = array();
		}

		if ($this->request->hasPost('product_special')) {
			$this->data['product_specials'] = $this->request->getPostE('product_special');
		} elseif ($this->request->hasQuery('product_id')) {
			$this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($this->request->getQueryE('product_id'));
		} else {
			$this->data['product_specials'] = array();
		}
		
		// Images
		if ($this->request->hasPost('product_image')) {
			$product_images = $this->request->getPostE('product_image');
		} elseif ($this->request->hasQuery('product_id')) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->getQueryE('product_id'));
		} else {
			$product_images = array();
		}
		
		$this->data['product_images'] = array();
		
		foreach ($product_images as $product_image) {
			if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$this->data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		// Downloads
		$this->model_catalog_download = new \Stupycart\Common\Models\Admin\Catalog\Download();
		
		if ($this->request->hasPost('product_download')) {
			$product_downloads = $this->request->getPostE('product_download');
		} elseif ($this->request->hasQuery('product_id')) {
			$product_downloads = $this->model_catalog_product->getProductDownloads($this->request->getQueryE('product_id'));
		} else {
			$product_downloads = array();
		}
			
		$this->data['product_downloads'] = array();
		
		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);
			
			if ($download_info) {
				$this->data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}
		
		if ($this->request->hasPost('product_related')) {
			$products = $this->request->getPostE('product_related');
		} elseif ($this->request->hasQuery('product_id')) {		
			$products = $this->model_catalog_product->getProductRelated($this->request->getQueryE('product_id'));
		} else {
			$products = array();
		}
	
		$this->data['product_related'] = array();
		
		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($related_info) {
				$this->data['product_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

    	if ($this->request->hasPost('points')) {
      		$this->data['points'] = $this->request->getPostE('points');
    	} elseif (!empty($product_info)) {
			$this->data['points'] = $product_info['points'];
		} else {
      		$this->data['points'] = '';
    	}
						
		if ($this->request->hasPost('product_reward')) {
			$this->data['product_reward'] = $this->request->getPostE('product_reward');
		} elseif ($this->request->hasQuery('product_id')) {
			$this->data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->getQueryE('product_id'));
		} else {
			$this->data['product_reward'] = array();
		}
		
		if ($this->request->hasPost('product_layout')) {
			$this->data['product_layout'] = $this->request->getPostE('product_layout');
		} elseif ($this->request->hasQuery('product_id')) {
			$this->data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->getQueryE('product_id'));
		} else {
			$this->data['product_layout'] = array();
		}

		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
										
		$this->view->pick('catalog/product_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	} 
	
  	protected function validateForm() { 
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	foreach ($this->request->getPostE('product_description') as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}
		
    	if ((utf8_strlen($this->request->getPostE('model')) < 1) || (utf8_strlen($this->request->getPostE('model')) > 64)) {
      		$this->error['model'] = $this->language->get('error_model');
    	}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
					
    	if (!$this->error) {
			return true;
    	} else {
      		return false;
    	}
  	}
	
  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
  	
  	protected function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
		
	public function autocompleteAction() {
		$json = array();
		
		if ($this->request->hasQuery('filter_name') || $this->request->hasQuery('filter_model') || $this->request->hasQuery('filter_category_id')) {
			$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
			$this->model_catalog_option = new \Stupycart\Common\Models\Admin\Catalog\Option();
			
			if ($this->request->hasQuery('filter_name')) {
				$filter_name = $this->request->getQueryE('filter_name');
			} else {
				$filter_name = '';
			}
			
			if ($this->request->hasQuery('filter_model')) {
				$filter_model = $this->request->getQueryE('filter_model');
			} else {
				$filter_model = '';
			}
			
			if ($this->request->hasQuery('limit')) {
				$limit = $this->request->getQueryE('limit');	
			} else {
				$limit = 20;	
			}			
						
			$data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);
			
			$results = $this->model_catalog_product->getProducts($data);
			
			foreach ($results as $result) {
				$option_data = array();
				
				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);	
				
				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);
					
					if ($option_info) {				
						if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
							$option_value_data = array();
							
							foreach ($product_option['product_option_value'] as $product_option_value) {
								$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);
						
								if ($option_value_info) {
									$option_value_data[] = array(
										'product_option_value_id' => $product_option_value['product_option_value_id'],
										'option_value_id'         => $product_option_value['option_value_id'],
										'name'                    => $option_value_info['name'],
										'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
										'price_prefix'            => $product_option_value['price_prefix']
									);
								}
							}
						
							$option_data[] = array(
								'product_option_id' => $product_option['product_option_id'],
								'option_id'         => $product_option['option_id'],
								'name'              => $option_info['name'],
								'type'              => $option_info['type'],
								'option_value'      => $option_value_data,
								'required'          => $product_option['required']
							);	
						} else {
							$option_data[] = array(
								'product_option_id' => $product_option['product_option_id'],
								'option_id'         => $product_option['option_id'],
								'name'              => $option_info['name'],
								'type'              => $option_info['type'],
								'option_value'      => $product_option['option_value'],
								'required'          => $product_option['required']
							);				
						}
					}
				}
					
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),	
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);	
			}
		}

		$this->response->setContent(json_encode($json));
		return $this->response;
	}
}
?>

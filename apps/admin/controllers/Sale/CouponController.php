<?php  

namespace Stupycart\Admin\Controllers\Sale;

class CouponController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
     
  	public function indexAction() {
		$this->language->load('sale/coupon');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_coupon = new \Stupycart\Common\Models\Admin\Sale\Coupon();
		
		$this->getList();
  	}
  
  	public function insertAction() {
    	$this->language->load('sale/coupon');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_coupon = new \Stupycart\Common\Models\Admin\Sale\Coupon();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_coupon->addCoupon($this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));

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
						
			$this->response->redirect($this->url->link('sale/coupon', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
    
    	$this->getForm();
  	}

  	public function updateAction() {
    	$this->language->load('sale/coupon');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_coupon = new \Stupycart\Common\Models\Admin\Sale\Coupon();
				
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_coupon->editCoupon($this->request->getQueryE('coupon_id'), $this->request->getPostE());
      		
			$this->session->set('success', $this->language->get('text_success'));
	  
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
						
			$this->response->redirect($this->url->link('sale/coupon', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
    
    	$this->getForm();
  	}

  	public function deleteAction() {
    	$this->language->load('sale/coupon');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_coupon = new \Stupycart\Common\Models\Admin\Sale\Coupon();
		
    	if ($this->request->hasPost('selected') && $this->validateDelete()) { 
			foreach ($this->request->getPostE('selected') as $coupon_id) {
				$this->model_sale_coupon->deleteCoupon($coupon_id);
			}
      		
			$this->session->set('success', $this->language->get('text_success'));
	  
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
						
			$this->response->redirect($this->url->link('sale/coupon', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getList();
  	}

  	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'name';
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
			'href'      => $this->url->link('sale/coupon', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('sale/coupon/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/coupon/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		$this->data['coupons'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$coupon_total = $this->model_sale_coupon->getTotalCoupons();
	
		$results = $this->model_sale_coupon->getCoupons($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/coupon/update', 'token=' . $this->session->get('token') . '&coupon_id=' . $result['coupon_id'] . $url, 'SSL')
			);
						
			$this->data['coupons'][] = array(
				'coupon_id'  => $result['coupon_id'],
				'name'       => $result['name'],
				'code'       => $result['code'],
				'discount'   => $result['discount'],
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => $this->request->hasPost('selected') && in_array($result['coupon_id'], $this->request->getPostE('selected')),
				'action'     => $action
			);
		}
									
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_discount'] = $this->language->get('column_discount');
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
		
		$this->data['sort_name'] = HTTPS_SERVER . 'sale/coupon&token=' . $this->session->get('token') . '?sort=name' . $url;
		$this->data['sort_code'] = HTTPS_SERVER . 'sale/coupon&token=' . $this->session->get('token') . '?sort=code' . $url;
		$this->data['sort_discount'] = HTTPS_SERVER . 'sale/coupon&token=' . $this->session->get('token') . '?sort=discount' . $url;
		$this->data['sort_date_start'] = HTTPS_SERVER . 'sale/coupon&token=' . $this->session->get('token') . '?sort=date_start' . $url;
		$this->data['sort_date_end'] = HTTPS_SERVER . 'sale/coupon&token=' . $this->session->get('token') . '?sort=date_end' . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'sale/coupon&token=' . $this->session->get('token') . '?sort=status' . $url;
				
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'sale/coupon&token=' . $this->session->get('token') . $url . '?page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->view->pick('sale/coupon_list');
		$this->_commonAction();
		
		$this->view->setVars($this->data);
  	}

  	protected function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
    	$this->data['text_percent'] = $this->language->get('text_percent');
    	$this->data['text_amount'] = $this->language->get('text_amount');
				
		$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_description'] = $this->language->get('entry_description');
    	$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_logged'] = $this->language->get('entry_logged');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_product'] = $this->language->get('entry_product');
    	$this->data['entry_date_start'] = $this->language->get('entry_date_start');
    	$this->data['entry_date_end'] = $this->language->get('entry_date_end');
    	$this->data['entry_uses_total'] = $this->language->get('entry_uses_total');
		$this->data['entry_uses_customer'] = $this->language->get('entry_uses_customer');
		$this->data['entry_status'] = $this->language->get('entry_status');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_history'] = $this->language->get('tab_history');

		$this->data['token'] = $this->session->get('token');
	
		if ($this->request->hasQuery('coupon_id')) {
			$this->data['coupon_id'] = $this->request->getQueryE('coupon_id');
		} else {
			$this->data['coupon_id'] = 0;
		}
				
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	 	
		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}		
		
		if (isset($this->error['date_start'])) {
			$this->data['error_date_start'] = $this->error['date_start'];
		} else {
			$this->data['error_date_start'] = '';
		}	
		
		if (isset($this->error['date_end'])) {
			$this->data['error_date_end'] = $this->error['date_end'];
		} else {
			$this->data['error_date_end'] = '';
		}	

		$url = '';
			
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}

		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/coupon', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
									
		if (!$this->request->hasQuery('coupon_id')) {
			$this->data['action'] = $this->url->link('sale/coupon/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/coupon/update', 'token=' . $this->session->get('token') . '&coupon_id=' . $this->request->getQueryE('coupon_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/coupon', 'token=' . $this->session->get('token') . $url, 'SSL');
  		
		if ($this->request->hasQuery('coupon_id') && (!$this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$coupon_info = $this->model_sale_coupon->getCoupon($this->request->getQueryE('coupon_id'));
    	}
		
    	if ($this->request->hasPost('name')) {
      		$this->data['name'] = $this->request->getPostE('name');
    	} elseif (!empty($coupon_info)) {
			$this->data['name'] = $coupon_info['name'];
		} else {
      		$this->data['name'] = '';
    	}
		
    	if ($this->request->hasPost('code')) {
      		$this->data['code'] = $this->request->getPostE('code');
    	} elseif (!empty($coupon_info)) {
			$this->data['code'] = $coupon_info['code'];
		} else {
      		$this->data['code'] = '';
    	}
		
    	if ($this->request->hasPost('type')) {
      		$this->data['type'] = $this->request->getPostE('type');
    	} elseif (!empty($coupon_info)) {
			$this->data['type'] = $coupon_info['type'];
		} else {
      		$this->data['type'] = '';
    	}
		
    	if ($this->request->hasPost('discount')) {
      		$this->data['discount'] = $this->request->getPostE('discount');
    	} elseif (!empty($coupon_info)) {
			$this->data['discount'] = $coupon_info['discount'];
		} else {
      		$this->data['discount'] = '';
    	}

    	if ($this->request->hasPost('logged')) {
      		$this->data['logged'] = $this->request->getPostE('logged');
    	} elseif (!empty($coupon_info)) {
			$this->data['logged'] = $coupon_info['logged'];
		} else {
      		$this->data['logged'] = '';
    	}
		
    	if ($this->request->hasPost('shipping')) {
      		$this->data['shipping'] = $this->request->getPostE('shipping');
    	} elseif (!empty($coupon_info)) {
			$this->data['shipping'] = $coupon_info['shipping'];
		} else {
      		$this->data['shipping'] = '';
    	}

    	if ($this->request->hasPost('total')) {
      		$this->data['total'] = $this->request->getPostE('total');
    	} elseif (!empty($coupon_info)) {
			$this->data['total'] = $coupon_info['total'];
		} else {
      		$this->data['total'] = '';
    	}
		
		if ($this->request->hasPost('coupon_product')) {
			$products = $this->request->getPostE('coupon_product');
		} elseif ($this->request->hasQuery('coupon_id')) {		
			$products = $this->model_sale_coupon->getCouponProducts($this->request->getQueryE('coupon_id'));
		} else {
			$products = array();
		}
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		
		$this->data['coupon_product'] = array();
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$this->data['coupon_product'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}

		if ($this->request->hasPost('coupon_category')) {
			$categories = $this->request->getPostE('coupon_category');
		} elseif ($this->request->hasQuery('coupon_id')) {		
			$categories = $this->model_sale_coupon->getCouponCategories($this->request->getQueryE('coupon_id'));
		} else {
			$categories = array();
		}
	
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
	
		$this->data['coupon_category'] = array();
		
		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);
			
			if ($category_info) {
				$this->data['coupon_category'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}
					
		if ($this->request->hasPost('date_start')) {
       		$this->data['date_start'] = $this->request->getPostE('date_start');
		} elseif (!empty($coupon_info)) {
			$this->data['date_start'] = date('Y-m-d', strtotime($coupon_info['date_start']));
		} else {
			$this->data['date_start'] = date('Y-m-d', time());
		}

		if ($this->request->hasPost('date_end')) {
       		$this->data['date_end'] = $this->request->getPostE('date_end');
		} elseif (!empty($coupon_info)) {
			$this->data['date_end'] = date('Y-m-d', strtotime($coupon_info['date_end']));
		} else {
			$this->data['date_end'] = date('Y-m-d', time());
		}

    	if ($this->request->hasPost('uses_total')) {
      		$this->data['uses_total'] = $this->request->getPostE('uses_total');
		} elseif (!empty($coupon_info)) {
			$this->data['uses_total'] = $coupon_info['uses_total'];
    	} else {
      		$this->data['uses_total'] = 1;
    	}
  
    	if ($this->request->hasPost('uses_customer')) {
      		$this->data['uses_customer'] = $this->request->getPostE('uses_customer');
    	} elseif (!empty($coupon_info)) {
			$this->data['uses_customer'] = $coupon_info['uses_customer'];
		} else {
      		$this->data['uses_customer'] = 1;
    	}
 
    	if ($this->request->hasPost('status')) { 
      		$this->data['status'] = $this->request->getPostE('status');
    	} elseif (!empty($coupon_info)) {
			$this->data['status'] = $coupon_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}
		
		$this->view->pick('sale/coupon_form');
		$this->_commonAction();
		
		$this->view->setVars($this->data);		
  	}
	
  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/coupon')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
      	
		if ((utf8_strlen($this->request->getPostE('name')) < 3) || (utf8_strlen($this->request->getPostE('name')) > 128)) {
        	$this->error['name'] = $this->language->get('error_name');
      	}
			
    	if ((utf8_strlen($this->request->getPostE('code')) < 3) || (utf8_strlen($this->request->getPostE('code')) > 10)) {
      		$this->error['code'] = $this->language->get('error_code');
    	}
		
		$coupon_info = $this->model_sale_coupon->getCouponByCode($this->request->getPostE('code'));
		
		if ($coupon_info) {
			if (!$this->request->hasQuery('coupon_id')) {
				$this->error['warning'] = $this->language->get('error_exists');
			} elseif ($coupon_info['coupon_id'] != $this->request->getQueryE('coupon_id'))  {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
	
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/coupon')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
	  	
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}	
	
	public function historyAction() {
    	$this->language->load('sale/coupon');
		
		$this->model_sale_coupon = new \Stupycart\Common\Models\Admin\Sale\Coupon();
				
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_order_id'] = $this->language->get('column_order_id');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_amount'] = $this->language->get('column_amount');
		$this->data['column_date_added'] = $this->language->get('column_date_added');

		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}  
		
		$this->data['histories'] = array();
			
		$results = $this->model_sale_coupon->getCouponHistories($this->request->getQueryE('coupon_id'), ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $result['amount'],
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$history_total = $this->model_sale_coupon->getTotalCouponHistories($this->request->getQueryE('coupon_id'));
			
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->url = $this->url->link('sale/coupon/history', 'token=' . $this->session->get('token') . '&coupon_id=' . $this->request->getQueryE('coupon_id') . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->view->pick('sale/coupon_history');		
		
		$this->view->setVars($this->data);
  	}		
}
?>
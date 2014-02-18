<?php    

namespace Stupycart\Admin\Controllers\Sale;

class CustomerBanIpController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
  
  	public function indexAction() {
		$this->language->load('sale/customer_ban_ip');
		 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer_ban_ip = new \Stupycart\Common\Models\Admin\Sale\CustomerBanIp();
		
    	$this->getList();
  	}
  
  	public function insertAction() {
		$this->language->load('sale/customer_ban_ip');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer_ban_ip = new \Stupycart\Common\Models\Admin\Sale\CustomerBanIp();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
      	  	$this->model_sale_customer_ban_ip->addCustomerBanIp($this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('sale/customer_ban_ip', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
    	
    	$this->getForm();
  	} 
   
  	public function updateAction() {
		$this->language->load('sale/customer_ban_ip');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer_ban_ip = new \Stupycart\Common\Models\Admin\Sale\CustomerBanIp();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_ban_ip->editCustomerBanIp($this->request->getQueryE('customer_ban_ip_id'), $this->request->getPostE());
	  		
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
			
			$this->response->redirect($this->url->link('sale/customer_ban_ip', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
    
    	$this->getForm();
  	}   

  	public function deleteAction() {
		$this->language->load('sale/customer_ban_ip');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer_ban_ip = new \Stupycart\Common\Models\Admin\Sale\CustomerBanIp();
			
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $customer_ban_ip_id) {
				$this->model_sale_customer_ban_ip->deleteCustomerBanIp($customer_ban_ip_id);
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
			
			$this->response->redirect($this->url->link('sale/customer_ban_ip', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
    
    	$this->getList();
  	}  
    
  	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'ip'; 
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
			'href'      => $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('sale/customer_ban_ip/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer_ban_ip/delete', 'token=' . $this->session->get('token') . $url, 'SSL');

		$this->data['customer_ban_ips'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$customer_ban_ip_total = $this->model_sale_customer_ban_ip->getTotalCustomerBanIps($data);
	
		$results = $this->model_sale_customer_ban_ip->getCustomerBanIps($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer_ban_ip/update', 'token=' . $this->session->get('token') . '&customer_ban_ip_id=' . $result['customer_ban_ip_id'] . $url, 'SSL')
			);
			
			$this->data['customer_ban_ips'][] = array(
				'customer_ban_ip_id' => $result['customer_ban_ip_id'],
				'ip'                 => $result['ip'],
				'total'              => $result['total'],
				'customer'           => $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&filter_ip=' . $result['ip'], 'SSL'),
				'selected'           => $this->request->hasPost('selected') && in_array($result['customer_ban_ip_id'], $this->request->getPostE('selected')),
				'action'             => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_customer'] = $this->language->get('column_customer');
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
		
		$this->data['sort_ip'] = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->get('token') . '&sort=ip' . $url, 'SSL');
		
		$url = '';
			
		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $customer_ban_ip_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
				
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->view->pick('sale/customer_ban_ip_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
  
  	protected function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 		
    	$this->data['entry_ip'] = $this->language->get('entry_ip');
 
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['ip'])) {
			$this->data['error_ip'] = $this->error['ip'];
		} else {
			$this->data['error_ip'] = '';
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
			'href'      => $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!$this->request->hasQuery('customer_ban_ip_id')) {
			$this->data['action'] = $this->url->link('sale/customer_ban_ip/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer_ban_ip/update', 'token=' . $this->session->get('token') . '&customer_ban_ip_id=' . $this->request->getQueryE('customer_ban_ip_id') . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->get('token') . $url, 'SSL');

    	if ($this->request->hasQuery('customer_ban_ip_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$customer_ban_ip_info = $this->model_sale_customer_ban_ip->getCustomerBanIp($this->request->getQueryE('customer_ban_ip_id'));
    	}
			
    	if ($this->request->hasPost('ip')) {
      		$this->data['ip'] = $this->request->getPostE('ip');
		} elseif (!empty($customer_ban_ip_info)) { 
			$this->data['ip'] = $customer_ban_ip_info['ip'];
		} else {
      		$this->data['ip'] = '';
    	}
		
		$this->view->pick('sale/customer_ban_ip_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
			 
  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/customer_ban_ip')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((utf8_strlen($this->request->getPostE('ip')) < 1) || (utf8_strlen($this->request->getPostE('ip')) > 40)) {
      		$this->error['ip'] = $this->language->get('error_ip');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}    

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/customer_ban_ip')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	} 
}
?>
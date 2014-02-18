<?php    

namespace Stupycart\Admin\Controllers\Sale;

class CustomerController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
  
  	public function indexAction() {
		$this->language->load('sale/customer');
		 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
		
    	$this->getList();
  	}
  
  	public function insertAction() {
		$this->language->load('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
      	  	$this->model_sale_customer->addCustomer($this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));
		  
			$url = '';

			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_email')) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_customer_group_id')) {
				$url .= '&filter_customer_group_id=' . $this->request->getQueryE('filter_customer_group_id');
			}
			
			if ($this->request->hasQuery('filter_status')) {
				$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
			}
			
			if ($this->request->hasQuery('filter_approved')) {
				$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
			}

			if ($this->request->hasQuery('filter_ip')) {
				$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
			}
					
			if ($this->request->hasQuery('filter_date_added')) {
				$url .= '&filter_date_added=' . $this->request->getQueryE('filter_date_added');
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
			
			$this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
    	
    	$this->getForm();
  	} 
   
  	public function updateAction() {
		$this->language->load('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_customer->editCustomer($this->request->getQueryE('customer_id'), $this->request->getPostE());
	  		
			$this->session->set('success', $this->language->get('text_success'));
	  
			$url = '';

			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_email')) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_customer_group_id')) {
				$url .= '&filter_customer_group_id=' . $this->request->getQueryE('filter_customer_group_id');
			}
			
			if ($this->request->hasQuery('filter_status')) {
				$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
			}
			
			if ($this->request->hasQuery('filter_approved')) {
				$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
			}
			
			if ($this->request->hasQuery('filter_ip')) {
				$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
			}
					
			if ($this->request->hasQuery('filter_date_added')) {
				$url .= '&filter_date_added=' . $this->request->getQueryE('filter_date_added');
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
			
			$this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
    
    	$this->getForm();
  	}   

  	public function deleteAction() {
		$this->language->load('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
			
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $customer_id) {
				$this->model_sale_customer->deleteCustomer($customer_id);
			}
			
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';

			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_email')) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_customer_group_id')) {
				$url .= '&filter_customer_group_id=' . $this->request->getQueryE('filter_customer_group_id');
			}
			
			if ($this->request->hasQuery('filter_status')) {
				$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
			}
			
			if ($this->request->hasQuery('filter_approved')) {
				$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
			}	
				
			if ($this->request->hasQuery('filter_ip')) {
				$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
			}
					
			if ($this->request->hasQuery('filter_date_added')) {
				$url .= '&filter_date_added=' . $this->request->getQueryE('filter_date_added');
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
			
			$this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
    
    	$this->getList();
  	}  
	
	public function approveAction() {
		$this->language->load('sale/customer');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
		
		if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif ($this->request->hasPost('selected')) {
			$approved = 0;
			
			foreach ($this->request->getPostE('selected') as $customer_id) {
				$customer_info = $this->model_sale_customer->getCustomer($customer_id);
				
				if ($customer_info && !$customer_info['approved']) {
					$this->model_sale_customer->approve($customer_id);
					
					$approved++;
				}
			} 
			
			$this->session->set('success', sprintf($this->language->get('text_approved'), $approved));	
			
			$url = '';
		
			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
		
			if ($this->request->hasQuery('filter_email')) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_customer_group_id')) {
				$url .= '&filter_customer_group_id=' . $this->request->getQueryE('filter_customer_group_id');
			}
		
			if ($this->request->hasQuery('filter_status')) {
				$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
			}
			
			if ($this->request->hasQuery('filter_approved')) {
				$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
			}
				
			if ($this->request->hasQuery('filter_ip')) {
				$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
			}
						
			if ($this->request->hasQuery('filter_date_added')) {
				$url .= '&filter_date_added=' . $this->request->getQueryE('filter_date_added');
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
	
			$this->response->redirect($this->url->link('sale/customer', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
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

		if ($this->request->hasQuery('filter_email')) {
			$filter_email = $this->request->getQueryE('filter_email');
		} else {
			$filter_email = null;
		}
		
		if ($this->request->hasQuery('filter_customer_group_id')) {
			$filter_customer_group_id = $this->request->getQueryE('filter_customer_group_id');
		} else {
			$filter_customer_group_id = null;
		}

		if ($this->request->hasQuery('filter_status')) {
			$filter_status = $this->request->getQueryE('filter_status');
		} else {
			$filter_status = null;
		}
		
		if ($this->request->hasQuery('filter_approved')) {
			$filter_approved = $this->request->getQueryE('filter_approved');
		} else {
			$filter_approved = null;
		}
		
		if ($this->request->hasQuery('filter_ip')) {
			$filter_ip = $this->request->getQueryE('filter_ip');
		} else {
			$filter_ip = null;
		}
				
		if ($this->request->hasQuery('filter_date_added')) {
			$filter_date_added = $this->request->getQueryE('filter_date_added');
		} else {
			$filter_date_added = null;
		}		
		
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

		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_email')) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_customer_group_id')) {
			$url .= '&filter_customer_group_id=' . $this->request->getQueryE('filter_customer_group_id');
		}
			
		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
		}
		
		if ($this->request->hasQuery('filter_approved')) {
			$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
		}	
		
		if ($this->request->hasQuery('filter_ip')) {
			$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
		}
					
		if ($this->request->hasQuery('filter_date_added')) {
			$url .= '&filter_date_added=' . $this->request->getQueryE('filter_date_added');
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
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['approve'] = $this->url->link('sale/customer/approve', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['insert'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer/delete', 'token=' . $this->session->get('token') . $url, 'SSL');

		$this->data['customers'] = array();

		$data = array(
			'filter_name'              => $filter_name, 
			'filter_email'             => $filter_email, 
			'filter_customer_group_id' => $filter_customer_group_id, 
			'filter_status'            => $filter_status, 
			'filter_approved'          => $filter_approved, 
			'filter_date_added'        => $filter_date_added,
			'filter_ip'                => $filter_ip,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$customer_total = $this->model_sale_customer->getTotalCustomers($data);
	
		$results = $this->model_sale_customer->getCustomers($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->get('token') . '&customer_id=' . $result['customer_id'] . $url, 'SSL')
			);
			
			$this->data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'name'           => $result['name'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'ip'             => $result['ip'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => $this->request->hasPost('selected') && in_array($result['customer_id'], $this->request->getPostE('selected')),
				'action'         => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');	
		$this->data['text_select'] = $this->language->get('text_select');	
		$this->data['text_default'] = $this->language->get('text_default');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_login'] = $this->language->get('column_login');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_approve'] = $this->language->get('button_approve');
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
		
		if ($this->request->hasQuery('filter_email')) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_customer_group_id')) {
			$url .= '&filter_customer_group_id=' . $this->request->getQueryE('filter_customer_group_id');
		}
			
		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
		}
		
		if ($this->request->hasQuery('filter_approved')) {
			$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
		}	
		
		if ($this->request->hasQuery('filter_ip')) {
			$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
		}
				
		if ($this->request->hasQuery('filter_date_added')) {
			$url .= '&filter_date_added=' . $this->request->getQueryE('filter_date_added');
		}
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
		
		$this->data['sort_name'] = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		$this->data['sort_email'] = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&sort=c.email' . $url, 'SSL');
		$this->data['sort_customer_group'] = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&sort=customer_group' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&sort=c.status' . $url, 'SSL');
		$this->data['sort_approved'] = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&sort=c.approved' . $url, 'SSL');
		$this->data['sort_ip'] = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&sort=c.ip' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&sort=c.date_added' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_email')) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_customer_group_id')) {
			$url .= '&filter_customer_group_id=' . $this->request->getQueryE('filter_customer_group_id');
		}

		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
		}
		
		if ($this->request->hasQuery('filter_approved')) {
			$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
		}
		
		if ($this->request->hasQuery('filter_ip')) {
			$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
		}
				
		if ($this->request->hasQuery('filter_date_added')) {
			$url .= '&filter_date_added=' . $this->request->getQueryE('filter_date_added');
		}
			
		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_customer_group_id'] = $filter_customer_group_id;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_ip'] = $filter_ip;
		$this->data['filter_date_added'] = $filter_date_added;
		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
		
    	$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		
		$this->data['stores'] = $this->model_setting_store->getStores();
				
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->view->pick('sale/customer_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
  
  	protected function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
		$this->data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');
		
		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_default'] = $this->language->get('entry_default');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_points'] = $this->language->get('entry_points');
 
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add_address'] = $this->language->get('button_add_address');
		$this->data['button_add_history'] = $this->language->get('button_add_history');
		$this->data['button_add_transaction'] = $this->language->get('button_add_transaction');
		$this->data['button_add_reward'] = $this->language->get('button_add_reward');
    	$this->data['button_remove'] = $this->language->get('button_remove');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_address'] = $this->language->get('tab_address');
		$this->data['tab_history'] = $this->language->get('tab_history');
		$this->data['tab_transaction'] = $this->language->get('tab_transaction');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_ip'] = $this->language->get('tab_ip');

		$this->data['token'] = $this->session->get('token');

		if ($this->request->hasQuery('customer_id')) {
			$this->data['customer_id'] = $this->request->getQueryE('customer_id');
		} else {
			$this->data['customer_id'] = 0;
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}

 		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
 		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
		if (isset($this->error['address_firstname'])) {
			$this->data['error_address_firstname'] = $this->error['address_firstname'];
		} else {
			$this->data['error_address_firstname'] = '';
		}

 		if (isset($this->error['address_lastname'])) {
			$this->data['error_address_lastname'] = $this->error['address_lastname'];
		} else {
			$this->data['error_address_lastname'] = '';
		}
		
  		if (isset($this->error['address_tax_id'])) {
			$this->data['error_address_tax_id'] = $this->error['address_tax_id'];
		} else {
			$this->data['error_address_tax_id'] = '';
		}
				
		if (isset($this->error['address_address_1'])) {
			$this->data['error_address_address_1'] = $this->error['address_address_1'];
		} else {
			$this->data['error_address_address_1'] = '';
		}
		
		if (isset($this->error['address_city'])) {
			$this->data['error_address_city'] = $this->error['address_city'];
		} else {
			$this->data['error_address_city'] = '';
		}
		
		if (isset($this->error['address_postcode'])) {
			$this->data['error_address_postcode'] = $this->error['address_postcode'];
		} else {
			$this->data['error_address_postcode'] = '';
		}
		
		if (isset($this->error['address_country'])) {
			$this->data['error_address_country'] = $this->error['address_country'];
		} else {
			$this->data['error_address_country'] = '';
		}
		
		if (isset($this->error['address_zone'])) {
			$this->data['error_address_zone'] = $this->error['address_zone'];
		} else {
			$this->data['error_address_zone'] = '';
		}
		
		$url = '';
		
		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_email')) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_customer_group_id')) {
			$url .= '&filter_customer_group_id=' . $this->request->getQueryE('filter_customer_group_id');
		}
		
		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
		}
		
		if ($this->request->hasQuery('filter_approved')) {
			$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
		}	
		
		if ($this->request->hasQuery('filter_date_added')) {
			$url .= '&filter_date_added=' . $this->request->getQueryE('filter_date_added');
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
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!$this->request->hasQuery('customer_id')) {
			$this->data['action'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer/update', 'token=' . $this->session->get('token') . '&customer_id=' . $this->request->getQueryE('customer_id') . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->get('token') . $url, 'SSL');

    	if ($this->request->hasQuery('customer_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$customer_info = $this->model_sale_customer->getCustomer($this->request->getQueryE('customer_id'));
    	}
			
    	if ($this->request->hasPost('firstname')) {
      		$this->data['firstname'] = $this->request->getPostE('firstname');
		} elseif (!empty($customer_info)) { 
			$this->data['firstname'] = $customer_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if ($this->request->hasPost('lastname')) {
      		$this->data['lastname'] = $this->request->getPostE('lastname');
    	} elseif (!empty($customer_info)) { 
			$this->data['lastname'] = $customer_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}

    	if ($this->request->hasPost('email')) {
      		$this->data['email'] = $this->request->getPostE('email');
    	} elseif (!empty($customer_info)) { 
			$this->data['email'] = $customer_info['email'];
		} else {
      		$this->data['email'] = '';
    	}

    	if ($this->request->hasPost('telephone')) {
      		$this->data['telephone'] = $this->request->getPostE('telephone');
    	} elseif (!empty($customer_info)) { 
			$this->data['telephone'] = $customer_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}

    	if ($this->request->hasPost('fax')) {
      		$this->data['fax'] = $this->request->getPostE('fax');
    	} elseif (!empty($customer_info)) { 
			$this->data['fax'] = $customer_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}

    	if ($this->request->hasPost('newsletter')) {
      		$this->data['newsletter'] = $this->request->getPostE('newsletter');
    	} elseif (!empty($customer_info)) { 
			$this->data['newsletter'] = $customer_info['newsletter'];
		} else {
      		$this->data['newsletter'] = '';
    	}
		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
			
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

    	if ($this->request->hasPost('customer_group_id')) {
      		$this->data['customer_group_id'] = $this->request->getPostE('customer_group_id');
    	} elseif (!empty($customer_info)) { 
			$this->data['customer_group_id'] = $customer_info['customer_group_id'];
		} else {
      		$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
    	}
		
    	if ($this->request->hasPost('status')) {
      		$this->data['status'] = $this->request->getPostE('status');
    	} elseif (!empty($customer_info)) { 
			$this->data['status'] = $customer_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}

    	if ($this->request->hasPost('password')) { 
			$this->data['password'] = $this->request->getPostE('password');
		} else {
			$this->data['password'] = '';
		}
		
		if ($this->request->hasPost('confirm')) { 
    		$this->data['confirm'] = $this->request->getPostE('confirm');
		} else {
			$this->data['confirm'] = '';
		}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Admin\Localisation\Country();
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
			
		if ($this->request->hasPost('address')) { 
      		$this->data['addresses'] = $this->request->getPostE('address');
		} elseif ($this->request->hasQuery('customer_id')) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->getQueryE('customer_id'));
		} else {
			$this->data['addresses'] = array();
    	}

    	if ($this->request->hasPost('address_id')) {
      		$this->data['address_id'] = $this->request->getPostE('address_id');
    	} elseif (!empty($customer_info)) { 
			$this->data['address_id'] = $customer_info['address_id'];
		} else {
      		$this->data['address_id'] = '';
    	}
		
		$this->data['ips'] = array();
    	
		if (!empty($customer_info)) {
			$results = $this->model_sale_customer->getIpsByCustomerId($this->request->getQueryE('customer_id'));
		
			foreach ($results as $result) {
				$ban_ip_total = $this->model_sale_customer->getTotalBanIpsByIp($result['ip']);
				
				$this->data['ips'][] = array(
					'ip'         => $result['ip'],
					'total'      => $this->model_sale_customer->getTotalCustomersByIp($result['ip']),
					'date_added' => date('d/m/y', strtotime($result['date_added'])),
					'filter_ip'  => $this->url->link('sale/customer', 'token=' . $this->session->get('token') . '&filter_ip=' . $result['ip'], 'SSL'),
					'ban_ip'     => $ban_ip_total
				);
			}
		}		
		
		$this->view->pick('sale/customer_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
			 
  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((utf8_strlen($this->request->getPostE('firstname')) < 1) || (utf8_strlen($this->request->getPostE('firstname')) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->getPostE('lastname')) < 1) || (utf8_strlen($this->request->getPostE('lastname')) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

		if ((utf8_strlen($this->request->getPostE('email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('email'))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
		$customer_info = $this->model_sale_customer->getCustomerByEmail($this->request->getPostE('email'));
		
		if (!$this->request->hasQuery('customer_id')) {
			if ($customer_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($customer_info && ($this->request->getQueryE('customer_id') != $customer_info['customer_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		
    	if ((utf8_strlen($this->request->getPostE('telephone')) < 3) || (utf8_strlen($this->request->getPostE('telephone')) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if ($this->request->getPostE('password') || (!$this->request->hasQuery('customer_id'))) {
      		if ((utf8_strlen($this->request->getPostE('password')) < 4) || (utf8_strlen($this->request->getPostE('password')) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->getPostE('password') != $this->request->getPostE('confirm')) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}

		if ($this->request->hasPost('address')) {
			foreach ($this->request->getPostE('address') as $key => $value) {
				if ((utf8_strlen($value['firstname']) < 1) || (utf8_strlen($value['firstname']) > 32)) {
					$this->error['address_firstname'][$key] = $this->language->get('error_firstname');
				}
				
				if ((utf8_strlen($value['lastname']) < 1) || (utf8_strlen($value['lastname']) > 32)) {
					$this->error['address_lastname'][$key] = $this->language->get('error_lastname');
				}	
				
				if ((utf8_strlen($value['address_1']) < 3) || (utf8_strlen($value['address_1']) > 128)) {
					$this->error['address_address_1'][$key] = $this->language->get('error_address_1');
				}
			
				if ((utf8_strlen($value['city']) < 2) || (utf8_strlen($value['city']) > 128)) {
					$this->error['address_city'][$key] = $this->language->get('error_city');
				} 
	
				$this->model_localisation_country = new \Stupycart\Common\Models\Admin\Localisation\Country();
				
				$country_info = $this->model_localisation_country->getCountry($value['country_id']);
						
				if ($country_info) {
					if ($country_info['postcode_required'] && (utf8_strlen($value['postcode']) < 2) || (utf8_strlen($value['postcode']) > 10)) {
						$this->error['address_postcode'][$key] = $this->language->get('error_postcode');
					}
					
					// VAT Validation
					require_once(_ROOT_. '/libs/helper/vat.php');
					
					if ($this->config->get('config_vat') && $value['tax_id'] && (vat_validation($country_info['iso_code_2'], $value['tax_id']) == 'invalid')) {
						$this->error['address_tax_id'][$key] = $this->language->get('error_vat');
					}
				}
			
				if ($value['country_id'] == '') {
					$this->error['address_country'][$key] = $this->language->get('error_country');
				}
				
				if (!isset($value['zone_id']) || $value['zone_id'] == '') {
					$this->error['address_zone'][$key] = $this->language->get('error_zone');
				}	
			}
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
    	if (!$this->user->hasPermission('modify', 'sale/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	} 
	
	public function loginAction() {
		$json = array();
		
		if ($this->request->hasQuery('customer_id')) {
			$customer_id = $this->request->getQueryE('customer_id');
		} else {
			$customer_id = 0;
		}
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
		
		$customer_info = $this->model_sale_customer->getCustomer($customer_id);
				
		if ($customer_info) {
			$token = md5(mt_rand());
			
			$this->model_sale_customer->editToken($customer_id, $token);
			
			if ($this->request->hasQuery('store_id')) {
				$store_id = $this->request->getQueryE('store_id');
			} else {
				$store_id = 0;
			}
					
			$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
			
			$store_info = $this->model_setting_store->getStore($store_id);
			
			if ($store_info) {
				$this->response->redirect($store_info['url'] . 'account/login?token=' . $token, true);
		return;
			} else { 
				$this->response->redirect(HTTP_CATALOG . 'account/login?token=' . $token, true);
		return;
			}
		} else {
			$this->language->load('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_not_found'] = $this->language->get('text_not_found');

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('error/not_found', 'token=' . $this->session->get('token'), 'SSL'),
				'separator' => ' :: '
			);
		
			$this->view->pick('error/not_found');
		$this->_commonAction();
		
			$this->view->setVars($this->data);
		}
	}
	
	public function historyAction() {
    	$this->language->load('sale/customer');
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) { 
			$this->model_sale_customer->addHistory($this->request->getQueryE('customer_id'), $this->request->getPostE('comment'));
				
			$this->data['success'] = $this->language->get('text_success');
		} else {
			$this->data['success'] = '';
		}
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}		
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_comment'] = $this->language->get('column_comment');
		
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}  
		
		$this->data['histories'] = array();
			
		$results = $this->model_sale_customer->getHistories($this->request->getQueryE('customer_id'), ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'comment'     => $result['comment'],
        		'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$transaction_total = $this->model_sale_customer->getTotalHistories($this->request->getQueryE('customer_id'));
			
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer/history', 'token=' . $this->session->get('token') . '&customer_id=' . $this->request->getQueryE('customer_id') . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->view->pick('sale/customer_history');		
		
		$this->view->setVars($this->data);
	}
		
	public function transactionAction() {
    	$this->language->load('sale/customer');
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) { 
			$this->model_sale_customer->addTransaction($this->request->getQueryE('customer_id'), $this->request->getPostE('description'), $this->request->getPostE('amount'));
				
			$this->data['success'] = $this->language->get('text_success');
		} else {
			$this->data['success'] = '';
		}
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}		
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_balance'] = $this->language->get('text_balance');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_amount'] = $this->language->get('column_amount');
		
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}  
		
		$this->data['transactions'] = array();
			
		$results = $this->model_sale_customer->getTransactions($this->request->getQueryE('customer_id'), ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
        		'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$this->data['balance'] = $this->currency->format($this->model_sale_customer->getTransactionTotal($this->request->getQueryE('customer_id')), $this->config->get('config_currency'));
		
		$transaction_total = $this->model_sale_customer->getTotalTransactions($this->request->getQueryE('customer_id'));
			
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer/transaction', 'token=' . $this->session->get('token') . '&customer_id=' . $this->request->getQueryE('customer_id') . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->view->pick('sale/customer_transaction');		
		
		$this->view->setVars($this->data);
	}
			
	public function rewardAction() {
    	$this->language->load('sale/customer');
		
		$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();

		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) { 
			$this->model_sale_customer->addReward($this->request->getQueryE('customer_id'), $this->request->getPostE('description'), $this->request->getPostE('points'));
				
			$this->data['success'] = $this->language->get('text_success');
		} else {
			$this->data['success'] = '';
		}
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}	
				
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_balance'] = $this->language->get('text_balance');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_points'] = $this->language->get('column_points');

		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}  
		
		$this->data['rewards'] = array();
			
		$results = $this->model_sale_customer->getRewards($this->request->getQueryE('customer_id'), ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['rewards'][] = array(
				'points'      => $result['points'],
				'description' => $result['description'],
        		'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$this->data['balance'] = $this->model_sale_customer->getRewardTotal($this->request->getQueryE('customer_id'));
		
		$reward_total = $this->model_sale_customer->getTotalRewards($this->request->getQueryE('customer_id'));
			
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $reward_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer/reward', 'token=' . $this->session->get('token') . '&customer_id=' . $this->request->getQueryE('customer_id') . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->view->pick('sale/customer_reward');		
		
		$this->view->setVars($this->data);
	}
	
	public function addBanIP() {
		$this->language->load('sale/customer');
		
		$json = array();

		if ($this->request->hasPost('ip')) { 
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$json['error'] = $this->language->get('error_permission');
			} else {
				$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
				
				$this->model_sale_customer->addBanIP($this->request->getPostE('ip'));
				
				$json['success'] = $this->language->get('text_success');
			}
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;
	}
	
	public function removeBanIP() {
		$this->language->load('sale/customer');
		
		$json = array();

		if ($this->request->hasPost('ip')) { 
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$json['error'] = $this->language->get('error_permission');
			} else {
				$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
				
				$this->model_sale_customer->removeBanIP($this->request->getPostE('ip'));
				
				$json['success'] = $this->language->get('text_success');
			}
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;
	}

	public function autocompleteAction() {
		$json = array();
		
		if ($this->request->hasQuery('filter_name')) {
			$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
			
			$data = array(
				'filter_name' => $this->request->getQueryE('filter_name'),
				'start'       => 0,
				'limit'       => 20
			);
		
			$results = $this->model_sale_customer->getCustomers($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'], 
					'customer_group_id' => $result['customer_group_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'customer_group'    => $result['customer_group'],
					'firstname'         => $result['firstname'],
					'lastname'          => $result['lastname'],
					'email'             => $result['email'],
					'telephone'         => $result['telephone'],
					'fax'               => $result['fax'],
					'address'           => $this->model_sale_customer->getAddresses($result['customer_id'])
				);					
			}
		}

		$sort_order = array();
	  
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setContent(json_encode($json));
		return $this->response;
	}		
		
	public function countryAction() {
		$json = array();
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Admin\Localisation\Country();

    	$country_info = $this->model_localisation_country->getCountry($this->request->getQueryE('country_id'));
		
		if ($country_info) {
			$this->model_localisation_zone = new \Stupycart\Common\Models\Admin\Localisation\Zone();

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
		
	public function addressAction() {
		$json = array();
		
		if (!(!$this->request->getQueryE('address_id'))) {
			$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
			
			$json = $this->model_sale_customer->getAddress($this->request->getQueryE('address_id'));
		}

		$this->response->setContent(json_encode($json));
		return $this->response;		
	}
}
?>
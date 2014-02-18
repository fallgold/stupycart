<?php    

namespace Stupycart\Admin\Controllers\Sale;

class AffiliateController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
  
  	public function indexAction() {
		$this->language->load('sale/affiliate');
		 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();
		
    	$this->getList();
  	}
  
  	public function insertAction() {
		$this->language->load('sale/affiliate');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
      	  	$this->model_sale_affiliate->addAffiliate($this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));
		  
			$url = '';

			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
						
			if ($this->request->hasQuery('filter_email')) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
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
			
			$this->response->redirect($this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
    	
    	$this->getForm();
  	} 
   
  	public function updateAction() {
		$this->language->load('sale/affiliate');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_affiliate->editAffiliate($this->request->getQueryE('affiliate_id'), $this->request->getPostE());
	  		
			$this->session->set('success', $this->language->get('text_success'));
	  
			$url = '';

			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_email')) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
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
			
			$this->response->redirect($this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
    
    	$this->getForm();
  	}   

  	public function deleteAction() {
		$this->language->load('sale/affiliate');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();
			
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $affiliate_id) {
				$this->model_sale_affiliate->deleteAffiliate($affiliate_id);
			}
			
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';

			if ($this->request->hasQuery('filter_name')) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('filter_email')) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
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
			
			$this->response->redirect($this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
    
    	$this->getList();
  	}  
		 
	public function approveAction() {
		$this->language->load('sale/affiliate');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();	
		
		if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif ($this->request->hasPost('selected')) {
			$approved = 0;
			
			foreach ($this->request->getPostE('selected') as $affiliate_id) {
				$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
				
				if ($affiliate_info && !$affiliate_info['approved']) {
					$this->model_sale_affiliate->approve($affiliate_id);
				
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
		
			$this->response->redirect($this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
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
			'href'      => $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['approve'] = $this->url->link('sale/affiliate/approve', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['insert'] = $this->url->link('sale/affiliate/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/affiliate/delete', 'token=' . $this->session->get('token') . $url, 'SSL');

		$this->data['affiliates'] = array();

		$data = array(
			'filter_name'       => $filter_name, 
			'filter_email'      => $filter_email, 
			'filter_status'     => $filter_status, 
			'filter_approved'   => $filter_approved, 
			'filter_date_added' => $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'             => $this->config->get('config_admin_limit')
		);
		
		$affiliate_total = $this->model_sale_affiliate->getTotalAffiliates($data);
	
		$results = $this->model_sale_affiliate->getAffiliates($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/affiliate/update', 'token=' . $this->session->get('token') . '&affiliate_id=' . $result['affiliate_id'] . $url, 'SSL')
			);
			
			$this->data['affiliates'][] = array(
				'affiliate_id' => $result['affiliate_id'],
				'name'         => $result['name'],
				'email'        => $result['email'],
				'balance'      => $this->currency->format($result['balance'], $this->config->get('config_currency')),
				'status'       => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'approved'     => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'date_added'   => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'     => $this->request->hasPost('selected') && in_array($result['affiliate_id'], $this->request->getPostE('selected')),
				'action'       => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_balance'] = $this->language->get('column_balance');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
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
			
		if ($this->request->hasQuery('filter_status')) {
			$url .= '&filter_status=' . $this->request->getQueryE('filter_status');
		}
		
		if ($this->request->hasQuery('filter_approved')) {
			$url .= '&filter_approved=' . $this->request->getQueryE('filter_approved');
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
		
		$this->data['sort_name'] = $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		$this->data['sort_email'] = $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . '&sort=a.email' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . '&sort=a.status' . $url, 'SSL');
		$this->data['sort_approved'] = $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . '&sort=a.approved' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . '&sort=a.date_added' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_email')) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
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

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $affiliate_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_date_added'] = $filter_date_added;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('sale/affiliate_list');
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
		$this->data['text_cheque'] = $this->language->get('text_cheque');
		$this->data['text_paypal'] = $this->language->get('text_paypal');
		$this->data['text_bank'] = $this->language->get('text_bank');
				
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_commission'] = $this->language->get('entry_commission');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_payment'] = $this->language->get('entry_payment');
		$this->data['entry_cheque'] = $this->language->get('entry_cheque');
		$this->data['entry_paypal'] = $this->language->get('entry_paypal');
		$this->data['entry_bank_name'] = $this->language->get('entry_bank_name');
		$this->data['entry_bank_branch_number'] = $this->language->get('entry_bank_branch_number');
		$this->data['entry_bank_swift_code'] = $this->language->get('entry_bank_swift_code');
		$this->data['entry_bank_account_name'] = $this->language->get('entry_bank_account_name');
		$this->data['entry_bank_account_number'] = $this->language->get('entry_bank_account_number');
		$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
 		$this->data['entry_description'] = $this->language->get('entry_description');
 
		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add_transaction'] = $this->language->get('button_add_transaction');
    	$this->data['button_remove'] = $this->language->get('button_remove');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_payment'] = $this->language->get('tab_payment');
		$this->data['tab_transaction'] = $this->language->get('tab_transaction');

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
		
		if (isset($this->error['address_1'])) {
			$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}
		
		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}
		
		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}

		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}
						
		$url = '';
		
		if ($this->request->hasQuery('filter_name')) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->getQueryE('filter_name'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('filter_email')) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->getQueryE('filter_email'), ENT_QUOTES, 'UTF-8'));
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
			'href'      => $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!$this->request->hasQuery('affiliate_id')) {
			$this->data['action'] = $this->url->link('sale/affiliate/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/affiliate/update', 'token=' . $this->session->get('token') . '&affiliate_id=' . $this->request->getQueryE('affiliate_id') . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('sale/affiliate', 'token=' . $this->session->get('token') . $url, 'SSL');

    	if ($this->request->hasQuery('affiliate_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->getQueryE('affiliate_id'));
    	}

		$this->data['token'] = $this->session->get('token');

		if ($this->request->hasQuery('affiliate_id')) {
			$this->data['affiliate_id'] = $this->request->getQueryE('affiliate_id');
		} else {
			$this->data['affiliate_id'] = 0;
		}
					
    	if ($this->request->hasPost('firstname')) {
      		$this->data['firstname'] = $this->request->getPostE('firstname');
		} elseif (!empty($affiliate_info)) { 
			$this->data['firstname'] = $affiliate_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if ($this->request->hasPost('lastname')) {
      		$this->data['lastname'] = $this->request->getPostE('lastname');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['lastname'] = $affiliate_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}

    	if ($this->request->hasPost('email')) {
      		$this->data['email'] = $this->request->getPostE('email');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['email'] = $affiliate_info['email'];
		} else {
      		$this->data['email'] = '';
    	}

    	if ($this->request->hasPost('telephone')) {
      		$this->data['telephone'] = $this->request->getPostE('telephone');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['telephone'] = $affiliate_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}

    	if ($this->request->hasPost('fax')) {
      		$this->data['fax'] = $this->request->getPostE('fax');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['fax'] = $affiliate_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}

    	if ($this->request->hasPost('company')) {
      		$this->data['company'] = $this->request->getPostE('company');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['company'] = $affiliate_info['company'];
		} else {
      		$this->data['company'] = '';
    	}
		
    	if ($this->request->hasPost('address_1')) {
      		$this->data['address_1'] = $this->request->getPostE('address_1');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['address_1'] = $affiliate_info['address_1'];
		} else {
      		$this->data['address_1'] = '';
    	}
				
    	if ($this->request->hasPost('address_2')) {
      		$this->data['address_2'] = $this->request->getPostE('address_2');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['address_2'] = $affiliate_info['address_2'];
		} else {
      		$this->data['address_2'] = '';
    	}

    	if ($this->request->hasPost('city')) {
      		$this->data['city'] = $this->request->getPostE('city');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['city'] = $affiliate_info['city'];
		} else {
      		$this->data['city'] = '';
    	}

    	if ($this->request->hasPost('postcode')) {
      		$this->data['postcode'] = $this->request->getPostE('postcode');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['postcode'] = $affiliate_info['postcode'];
		} else {
      		$this->data['postcode'] = '';
    	}
    	
		if ($this->request->hasPost('country_id')) {
      		$this->data['country_id'] = $this->request->getPostE('country_id');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['country_id'] = $affiliate_info['country_id'];
		} else {
      		$this->data['country_id'] = '';
    	}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Admin\Localisation\Country();
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
				
		if ($this->request->hasPost('zone_id')) {
      		$this->data['zone_id'] = $this->request->getPostE('zone_id');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['zone_id'] = $affiliate_info['zone_id'];
		} else {
      		$this->data['zone_id'] = '';
    	}

		if ($this->request->hasPost('code')) {
      		$this->data['code'] = $this->request->getPostE('code');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['code'] = $affiliate_info['code'];
		} else {
      		$this->data['code'] = uniqid();
    	}
		
		if ($this->request->hasPost('commission')) {
      		$this->data['commission'] = $this->request->getPostE('commission');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['commission'] = $affiliate_info['commission'];
		} else {
      		$this->data['commission'] = $this->config->get('config_commission');
    	}
		
		if ($this->request->hasPost('tax')) {
      		$this->data['tax'] = $this->request->getPostE('tax');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['tax'] = $affiliate_info['tax'];
		} else {
      		$this->data['tax'] = '';
    	}		

		if ($this->request->hasPost('payment')) {
      		$this->data['payment'] = $this->request->getPostE('payment');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['payment'] = $affiliate_info['payment'];
		} else {
      		$this->data['payment'] = 'cheque';
    	}	

		if ($this->request->hasPost('cheque')) {
      		$this->data['cheque'] = $this->request->getPostE('cheque');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['cheque'] = $affiliate_info['cheque'];
		} else {
      		$this->data['cheque'] = '';
    	}	

		if ($this->request->hasPost('paypal')) {
      		$this->data['paypal'] = $this->request->getPostE('paypal');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['paypal'] = $affiliate_info['paypal'];
		} else {
      		$this->data['paypal'] = '';
    	}	

		if ($this->request->hasPost('bank_name')) {
      		$this->data['bank_name'] = $this->request->getPostE('bank_name');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['bank_name'] = $affiliate_info['bank_name'];
		} else {
      		$this->data['bank_name'] = '';
    	}	

		if ($this->request->hasPost('bank_branch_number')) {
      		$this->data['bank_branch_number'] = $this->request->getPostE('bank_branch_number');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['bank_branch_number'] = $affiliate_info['bank_branch_number'];
		} else {
      		$this->data['bank_branch_number'] = '';
    	}

		if ($this->request->hasPost('bank_swift_code')) {
      		$this->data['bank_swift_code'] = $this->request->getPostE('bank_swift_code');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['bank_swift_code'] = $affiliate_info['bank_swift_code'];
		} else {
      		$this->data['bank_swift_code'] = '';
    	}

		if ($this->request->hasPost('bank_account_name')) {
      		$this->data['bank_account_name'] = $this->request->getPostE('bank_account_name');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['bank_account_name'] = $affiliate_info['bank_account_name'];
		} else {
      		$this->data['bank_account_name'] = '';
    	}

		if ($this->request->hasPost('bank_account_number')) {
      		$this->data['bank_account_number'] = $this->request->getPostE('bank_account_number');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['bank_account_number'] = $affiliate_info['bank_account_number'];
		} else {
      		$this->data['bank_account_number'] = '';
    	}
																												
    	if ($this->request->hasPost('status')) {
      		$this->data['status'] = $this->request->getPostE('status');
    	} elseif (!empty($affiliate_info)) { 
			$this->data['status'] = $affiliate_info['status'];
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

		$this->view->pick('sale/affiliate_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((utf8_strlen($this->request->getPostE('firstname')) < 1) || (utf8_strlen($this->request->getPostE('firstname')) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->getPostE('lastname')) < 1) || (utf8_strlen($this->request->getPostE('lastname')) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

		if ((utf8_strlen($this->request->getPostE('email')) > 96) || (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('email')))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
		$affiliate_info = $this->model_sale_affiliate->getAffiliateByEmail($this->request->getPostE('email'));
		
		if (!$this->request->hasQuery('affiliate_id')) {
			if ($affiliate_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($affiliate_info && ($this->request->getQueryE('affiliate_id') != $affiliate_info['affiliate_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		
    	if ((utf8_strlen($this->request->getPostE('telephone')) < 3) || (utf8_strlen($this->request->getPostE('telephone')) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if ($this->request->getPostE('password') || (!$this->request->hasQuery('affiliate_id'))) {
      		if ((utf8_strlen($this->request->getPostE('password')) < 4) || (utf8_strlen($this->request->getPostE('password')) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->getPostE('password') != $this->request->getPostE('confirm')) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}
		
    	if ((utf8_strlen($this->request->getPostE('address_1')) < 3) || (utf8_strlen($this->request->getPostE('address_1')) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((utf8_strlen($this->request->getPostE('city')) < 2) || (utf8_strlen($this->request->getPostE('city')) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Admin\Localisation\Country();
		
		$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('country_id'));
		
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->getPostE('postcode')) < 2) || (utf8_strlen($this->request->getPostE('postcode')) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
		
    	if ($this->request->getPostE('country_id') == '') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if (!$this->request->hasPost('zone_id') || $this->request->getPostE('zone_id') == '') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}

    	if (!$this->request->getPostE('code')) {
      		$this->error['code'] = $this->language->get('error_code');
    	}
					
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}    

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/affiliate')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
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
		
	public function transactionAction() {
    	$this->language->load('sale/affiliate');
		
		$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->user->hasPermission('modify', 'sale/affiliate')) { 
			$this->model_sale_affiliate->addTransaction($this->request->getQueryE('affiliate_id'), $this->request->getPostE('description'), $this->request->getPostE('amount'));
				
			$this->data['success'] = $this->language->get('text_success');
		} else {
			$this->data['success'] = '';
		}

		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && !$this->user->hasPermission('modify', 'sale/affiliate')) {
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
			
		$results = $this->model_sale_affiliate->getTransactions($this->request->getQueryE('affiliate_id'), ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
        		'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$this->data['balance'] = $this->currency->format($this->model_sale_affiliate->getTransactionTotal($this->request->getQueryE('affiliate_id')), $this->config->get('config_currency'));
		
		$transaction_total = $this->model_sale_affiliate->getTotalTransactions($this->request->getQueryE('affiliate_id'));
			
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/affiliate/transaction', 'token=' . $this->session->get('token') . '&affiliate_id=' . $this->request->getQueryE('affiliate_id') . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->view->pick('sale/affiliate_transaction');		
		
		$this->view->setVars($this->data);
	}
		
	public function autocompleteAction() {
		$affiliate_data = array();
		
		if ($this->request->hasQuery('filter_name')) {
			$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();
			
			$data = array(
				'filter_name' => $this->request->getQueryE('filter_name'),
				'start'       => 0,
				'limit'       => 20
			);
		
			$results = $this->model_sale_affiliate->getAffiliates($data);
			
			foreach ($results as $result) {
				$affiliate_data[] = array(
					'affiliate_id' => $result['affiliate_id'],
					'name'         => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')
				);
			}
		}
		
		$this->response->setContent(json_encode($affiliate_data));
		return $this->response;
	}		
}
?>
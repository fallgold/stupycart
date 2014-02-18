<?php  

namespace Stupycart\Admin\Controllers\Sale;

class VoucherController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
     
  	public function indexAction() {
		$this->language->load('sale/voucher');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_voucher = new \Stupycart\Common\Models\Admin\Sale\Voucher();
		
		$this->getList();
  	}
  
  	public function insertAction() {
    	$this->language->load('sale/voucher');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_voucher = new \Stupycart\Common\Models\Admin\Sale\Voucher();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_voucher->addVoucher($this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('sale/voucher', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
    
    	$this->getForm();
  	}

  	public function updateAction() {
    	$this->language->load('sale/voucher');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_voucher = new \Stupycart\Common\Models\Admin\Sale\Voucher();
				
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_sale_voucher->editVoucher($this->request->getQueryE('voucher_id'), $this->request->getPostE());
      		
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
			
			$this->response->redirect($this->url->link('sale/voucher', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
    
    	$this->getForm();
  	}

  	public function deleteAction() {
    	$this->language->load('sale/voucher');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_voucher = new \Stupycart\Common\Models\Admin\Sale\Voucher();
		
    	if ($this->request->hasPost('selected') && $this->validateDelete()) { 
			foreach ($this->request->getPostE('selected') as $voucher_id) {
				$this->model_sale_voucher->deleteVoucher($voucher_id);
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
			
			$this->response->redirect($this->url->link('sale/voucher', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getList();
  	}

  	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'v.date_added';
		}
		
		if ($this->request->hasQuery('order')) {
			$order = $this->request->getQueryE('order');
		} else {
			$order = 'DESC';
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
			'href'      => $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('sale/voucher/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/voucher/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		$this->data['vouchers'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$voucher_total = $this->model_sale_voucher->getTotalVouchers();
	
		$results = $this->model_sale_voucher->getVouchers($data);
 
    	foreach ($results as $result) {
			$action = array();
									
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/voucher/update', 'token=' . $this->session->get('token') . '&voucher_id=' . $result['voucher_id'] . $url, 'SSL')
			);
						
			$this->data['vouchers'][] = array(
				'voucher_id' => $result['voucher_id'],
				'code'       => $result['code'],
				'from'       => $result['from_name'],
				'to'         => $result['to_name'],
				'theme'      => $result['theme'],
				'amount'     => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => $this->request->hasPost('selected') && in_array($result['voucher_id'], $this->request->getPostE('selected')),
				'action'     => $action
			);
		}
									
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_send'] = $this->language->get('text_send');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_from'] = $this->language->get('column_from');
		$this->data['column_to'] = $this->language->get('column_to');
		$this->data['column_theme'] = $this->language->get('column_theme');
		$this->data['column_amount'] = $this->language->get('column_amount');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
		
		$this->data['sort_code'] = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . '&sort=v.code' . $url, 'SSL');
		$this->data['sort_from'] = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . '&sort=v.from_name' . $url, 'SSL');
		$this->data['sort_to'] = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . '&sort=v.to_name' . $url, 'SSL');
		$this->data['sort_theme'] = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . '&sort=theme' . $url, 'SSL');
		$this->data['sort_amount'] = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . '&sort=v.amount' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . '&sort=v.date_end' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . '&sort=v.date_added' . $url, 'SSL');
				
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $voucher_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('sale/voucher_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}

  	protected function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
    	$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_from_name'] = $this->language->get('entry_from_name');
		$this->data['entry_from_email'] = $this->language->get('entry_from_email');
		$this->data['entry_to_name'] = $this->language->get('entry_to_name');
		$this->data['entry_to_email'] = $this->language->get('entry_to_email');
		$this->data['entry_theme'] = $this->language->get('entry_theme');
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_status'] = $this->language->get('entry_status');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_voucher_history'] = $this->language->get('tab_voucher_history');
		
		if ($this->request->hasQuery('voucher_id')) {
			$this->data['voucher_id'] = $this->request->getQueryE('voucher_id');
		} else {
			$this->data['voucher_id'] = 0;
		}
		 		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}		
		
		if (isset($this->error['from_name'])) {
			$this->data['error_from_name'] = $this->error['from_name'];
		} else {
			$this->data['error_from_name'] = '';
		}	
		
		if (isset($this->error['from_email'])) {
			$this->data['error_from_email'] = $this->error['from_email'];
		} else {
			$this->data['error_from_email'] = '';
		}	
		
		if (isset($this->error['to_name'])) {
			$this->data['error_to_name'] = $this->error['to_name'];
		} else {
			$this->data['error_to_name'] = '';
		}	
		
		if (isset($this->error['to_email'])) {
			$this->data['error_to_email'] = $this->error['to_email'];
		} else {
			$this->data['error_to_email'] = '';
		}	
		
		if (isset($this->error['amount'])) {
			$this->data['error_amount'] = $this->error['amount'];
		} else {
			$this->data['error_amount'] = '';
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
			'href'      => $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
									
		if (!$this->request->hasQuery('voucher_id')) {
			$this->data['action'] = $this->url->link('sale/voucher/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/voucher/update', 'token=' . $this->session->get('token') . '&voucher_id=' . $this->request->getQueryE('voucher_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/voucher', 'token=' . $this->session->get('token') . $url, 'SSL');
  		
		if ($this->request->hasQuery('voucher_id') && (!$this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$voucher_info = $this->model_sale_voucher->getVoucher($this->request->getQueryE('voucher_id'));
    	}
		
		$this->data['token'] = $this->session->get('token');

    	if ($this->request->hasPost('code')) {
      		$this->data['code'] = $this->request->getPostE('code');
    	} elseif (!empty($voucher_info)) {
			$this->data['code'] = $voucher_info['code'];
		} else {
      		$this->data['code'] = '';
    	}
		
    	if ($this->request->hasPost('from_name')) {
      		$this->data['from_name'] = $this->request->getPostE('from_name');
    	} elseif (!empty($voucher_info)) {
			$this->data['from_name'] = $voucher_info['from_name'];
		} else {
      		$this->data['from_name'] = '';
    	}
		
    	if ($this->request->hasPost('from_email')) {
      		$this->data['from_email'] = $this->request->getPostE('from_email');
    	} elseif (!empty($voucher_info)) {
			$this->data['from_email'] = $voucher_info['from_email'];
		} else {
      		$this->data['from_email'] = '';
    	}

    	if ($this->request->hasPost('to_name')) {
      		$this->data['to_name'] = $this->request->getPostE('to_name');
    	} elseif (!empty($voucher_info)) {
			$this->data['to_name'] = $voucher_info['to_name'];
		} else {
      		$this->data['to_name'] = '';
    	}
		
    	if ($this->request->hasPost('to_email')) {
      		$this->data['to_email'] = $this->request->getPostE('to_email');
    	} elseif (!empty($voucher_info)) {
			$this->data['to_email'] = $voucher_info['to_email'];
		} else {
      		$this->data['to_email'] = '';
    	}
 
 		$this->model_sale_voucher_theme = new \Stupycart\Common\Models\Admin\Sale\VoucherTheme();
			
		$this->data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

    	if ($this->request->hasPost('voucher_theme_id')) {
      		$this->data['voucher_theme_id'] = $this->request->getPostE('voucher_theme_id');
    	} elseif (!empty($voucher_info)) { 
			$this->data['voucher_theme_id'] = $voucher_info['voucher_theme_id'];
		} else {
      		$this->data['voucher_theme_id'] = '';
    	}	
		
    	if ($this->request->hasPost('message')) {
      		$this->data['message'] = $this->request->getPostE('message');
    	} elseif (!empty($voucher_info)) {
			$this->data['message'] = $voucher_info['message'];
		} else {
      		$this->data['message'] = '';
    	}
		
    	if ($this->request->hasPost('amount')) {
      		$this->data['amount'] = $this->request->getPostE('amount');
    	} elseif (!empty($voucher_info)) {
			$this->data['amount'] = $voucher_info['amount'];
		} else {
      		$this->data['amount'] = '';
    	}
	
    	if ($this->request->hasPost('status')) { 
      		$this->data['status'] = $this->request->getPostE('status');
    	} elseif (!empty($voucher_info)) {
			$this->data['status'] = $voucher_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}

		$this->view->pick('sale/voucher_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);		
  	}
	
  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/voucher')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
    	if ((utf8_strlen($this->request->getPostE('code')) < 3) || (utf8_strlen($this->request->getPostE('code')) > 10)) {
      		$this->error['code'] = $this->language->get('error_code');
    	}
		
		$voucher_info = $this->model_sale_voucher->getVoucherByCode($this->request->getPostE('code'));
		
		if ($voucher_info) {
			if (!$this->request->hasQuery('voucher_id')) {
				$this->error['warning'] = $this->language->get('error_exists');
			} elseif ($voucher_info['voucher_id'] != $this->request->getQueryE('voucher_id'))  {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
					      
    	if ((utf8_strlen($this->request->getPostE('to_name')) < 1) || (utf8_strlen($this->request->getPostE('to_name')) > 64)) {
      		$this->error['to_name'] = $this->language->get('error_to_name');
    	}    	
		
		if ((utf8_strlen($this->request->getPostE('to_email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('to_email'))) {
      		$this->error['to_email'] = $this->language->get('error_email');
    	}
		
    	if ((utf8_strlen($this->request->getPostE('from_name')) < 1) || (utf8_strlen($this->request->getPostE('from_name')) > 64)) {
      		$this->error['from_name'] = $this->language->get('error_from_name');
    	}  
		
		if ((utf8_strlen($this->request->getPostE('from_email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('from_email'))) {
      		$this->error['from_email'] = $this->language->get('error_email');
    	}
		
		if ($this->request->getPostE('amount') < 1) {
      		$this->error['amount'] = $this->language->get('error_amount');
    	}

    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/voucher')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		$this->model_sale_order = new \Stupycart\Common\Models\Admin\Sale\Order();
		
		foreach ($this->request->getPostE('selected') as $voucher_id) {
			$order_voucher_info = $this->model_sale_order->getOrderVoucherByVoucherId($voucher_id);
			
			if ($order_voucher_info) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $this->url->link('sale/order/info', 'token=' . $this->session->get('token') . '&order_id=' . $order_voucher_info['order_id'], 'SSL')); 
				
				break;       
			}
		}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}	
	
	public function historyAction() {
    	$this->language->load('sale/voucher');
		
		$this->model_sale_voucher = new \Stupycart\Common\Models\Admin\Sale\Voucher();
				
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
			
		$results = $this->model_sale_voucher->getVoucherHistories($this->request->getQueryE('voucher_id'), ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $this->currency->format($result['amount'], $this->config->get('config_currency')),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$history_total = $this->model_sale_voucher->getTotalVoucherHistories($this->request->getQueryE('voucher_id'));
			
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->url = $this->url->link('sale/voucher/history', 'token=' . $this->session->get('token') . '&voucher_id=' . $this->request->getQueryE('voucher_id') . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->view->pick('sale/voucher_history');		
		
		$this->view->setVars($this->data);
  	}
	
	public function sendAction() {
    	$this->language->load('sale/voucher');
		
		$json = array();
    	
     	if (!$this->user->hasPermission('modify', 'sale/voucher')) {
      		$json['error'] = $this->language->get('error_permission'); 
    	} elseif ($this->request->hasQuery('voucher_id')) {
			$this->model_sale_voucher = new \Stupycart\Common\Models\Admin\Sale\Voucher();
			
			$this->model_sale_voucher->sendVoucher($this->request->getQueryE('voucher_id'));
			
			$json['success'] = $this->language->get('text_sent');
		}	
		
		$this->response->setContent(json_encode($json));
		return $this->response;			
  	}	
}
?>
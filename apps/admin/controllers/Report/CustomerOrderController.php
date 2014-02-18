<?php

namespace Stupycart\Admin\Controllers\Report;

class CustomerOrderController extends \Stupycart\Admin\Controllers\ControllerBase {
	public function indexAction() {     
		$this->language->load('report/customer_order');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if ($this->request->hasQuery('filter_date_start')) {
			$filter_date_start = $this->request->getQueryE('filter_date_start');
		} else {
			$filter_date_start = '';
		}

		if ($this->request->hasQuery('filter_date_end')) {
			$filter_date_end = $this->request->getQueryE('filter_date_end');
		} else {
			$filter_date_end = '';
		}
		
		if ($this->request->hasQuery('filter_order_status_id')) {
			$filter_order_status_id = $this->request->getQueryE('filter_order_status_id');
		} else {
			$filter_order_status_id = 0;
		}	
				
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}

		$url = '';
		
		if ($this->request->hasQuery('filter_date_start')) {
			$url .= '&filter_date_start=' . $this->request->getQueryE('filter_date_start');
		}
		
		if ($this->request->hasQuery('filter_date_end')) {
			$url .= '&filter_date_end=' . $this->request->getQueryE('filter_date_end');
		}

		if ($this->request->hasQuery('filter_order_status_id')) {
			$url .= '&filter_order_status_id=' . $this->request->getQueryE('filter_order_status_id');
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
			'href'      => $this->url->link('report/customer_order', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->model_report_customer = new \Stupycart\Common\Models\Admin\Report\Customer();
		
		$this->data['customers'] = array();
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
				
		$customer_total = $this->model_report_customer->getTotalOrders($data); 
		
		$results = $this->model_report_customer->getOrders($data);
		
		foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->get('token') . '&customer_id=' . $result['customer_id'] . $url, 'SSL')
			);
						
			$this->data['customers'][] = array(
				'customer'       => $result['customer'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'orders'         => $result['orders'],
				'products'       => $result['products'],
				'total'          => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'action'         => $action
			);
		}
		 
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_products'] = $this->language->get('column_products');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->get('token');
		
		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
		$url = '';
						
		if ($this->request->hasQuery('filter_date_start')) {
			$url .= '&filter_date_start=' . $this->request->getQueryE('filter_date_start');
		}
		
		if ($this->request->hasQuery('filter_date_end')) {
			$url .= '&filter_date_end=' . $this->request->getQueryE('filter_date_end');
		}

		if ($this->request->hasQuery('filter_order_status_id')) {
			$url .= '&filter_order_status_id=' . $this->request->getQueryE('filter_order_status_id');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/customer_order', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
		$this->data['filter_order_status_id'] = $filter_order_status_id;
				 
		$this->view->pick('report/customer_order');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
}
?>
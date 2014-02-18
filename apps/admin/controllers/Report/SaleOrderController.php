<?php

namespace Stupycart\Admin\Controllers\Report;

class SaleOrderController extends \Stupycart\Admin\Controllers\ControllerBase { 
	public function indexAction() {  
		$this->language->load('report/sale_order');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->request->hasQuery('filter_date_start')) {
			$filter_date_start = $this->request->getQueryE('filter_date_start');
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if ($this->request->hasQuery('filter_date_end')) {
			$filter_date_end = $this->request->getQueryE('filter_date_end');
		} else {
			$filter_date_end = date('Y-m-d');
		}
		
		if ($this->request->hasQuery('filter_group')) {
			$filter_group = $this->request->getQueryE('filter_group');
		} else {
			$filter_group = 'week';
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
		
		if ($this->request->hasQuery('filter_group')) {
			$url .= '&filter_group=' . $this->request->getQueryE('filter_group');
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
			'href'      => $this->url->link('report/sale_order', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->model_report_sale = new \Stupycart\Common\Models\Admin\Report\Sale();
		
		$this->data['orders'] = array();
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_group'           => $filter_group,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$order_total = $this->model_report_sale->getTotalOrders($data);
		
		$results = $this->model_report_sale->getOrders($data);
		
		foreach ($results as $result) {
			$this->data['orders'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'orders'     => $result['orders'],
				'products'   => $result['products'],
				'tax'        => $this->currency->format($result['tax'], $this->config->get('config_currency')),
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
    	$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_products'] = $this->language->get('column_products');
		$this->data['column_tax'] = $this->language->get('column_tax');
		$this->data['column_total'] = $this->language->get('column_total');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_group'] = $this->language->get('entry_group');	
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->get('token');
		
		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['groups'] = array();

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';
						
		if ($this->request->hasQuery('filter_date_start')) {
			$url .= '&filter_date_start=' . $this->request->getQueryE('filter_date_start');
		}
		
		if ($this->request->hasQuery('filter_date_end')) {
			$url .= '&filter_date_end=' . $this->request->getQueryE('filter_date_end');
		}
		
		if ($this->request->hasQuery('filter_group')) {
			$url .= '&filter_group=' . $this->request->getQueryE('filter_group');
		}		

		if ($this->request->hasQuery('filter_order_status_id')) {
			$url .= '&filter_order_status_id=' . $this->request->getQueryE('filter_order_status_id');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/sale_order', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
				 
		$this->view->pick('report/sale_order');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
}
?>
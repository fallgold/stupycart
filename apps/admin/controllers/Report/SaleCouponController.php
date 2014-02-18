<?php

namespace Stupycart\Admin\Controllers\Report;

class SaleCouponController extends \Stupycart\Admin\Controllers\ControllerBase {
	public function indexAction() {     
		$this->language->load('report/sale_coupon');

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
			'href'      => $this->url->link('report/sale_coupon', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->model_report_coupon = new \Stupycart\Common\Models\Admin\Report\Coupon();
		
		$this->data['coupons'] = array();
		
		$data = array(
			'filter_date_start'	=> $filter_date_start, 
			'filter_date_end'	=> $filter_date_end, 
			'start'             => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'             => $this->config->get('config_admin_limit')
		);
				
		$coupon_total = $this->model_report_coupon->getTotalCoupons($data); 
		
		$results = $this->model_report_coupon->getCoupons($data);
	
		foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/coupon/update', 'token=' . $this->session->get('token') . '&coupon_id=' . $result['coupon_id'] . $url, 'SSL')
			);
						
			$this->data['coupons'][] = array(
				'name'   => $result['name'],
				'code'   => $result['code'],
				'orders' => $result['orders'],
				'total'  => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'action' => $action
			);
		}
				 
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		
		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->get('token');
		
		$url = '';
						
		if ($this->request->hasQuery('filter_date_start')) {
			$url .= '&filter_date_start=' . $this->request->getQueryE('filter_date_start');
		}
		
		if ($this->request->hasQuery('filter_date_end')) {
			$url .= '&filter_date_end=' . $this->request->getQueryE('filter_date_end');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/sale_coupon', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;	
				
		$this->view->pick('report/sale_coupon');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
}
?>
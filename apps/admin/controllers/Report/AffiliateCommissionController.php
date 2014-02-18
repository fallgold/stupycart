<?php

namespace Stupycart\Admin\Controllers\Report;

class AffiliateCommissionController extends \Stupycart\Admin\Controllers\ControllerBase {
	public function indexAction() {     
		$this->language->load('report/affiliate_commission');

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
			'href'      => $this->url->link('report/affiliate_commission', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->model_report_affiliate = new \Stupycart\Common\Models\Admin\Report\Affiliate();
		
		$this->data['affiliates'] = array();
		
		$data = array(
			'filter_date_start'	=> $filter_date_start, 
			'filter_date_end'	=> $filter_date_end, 
			'start'             => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'             => $this->config->get('config_admin_limit')
		);
		
		$affiliate_total = $this->model_report_affiliate->getTotalCommission($data); 
		
		$results = $this->model_report_affiliate->getCommission($data);
		
		foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/affiliate/update', 'token=' . $this->session->get('token') . '&affiliate_id=' . $result['affiliate_id'] . $url, 'SSL')
			);
						
			$this->data['affiliates'][] = array(
				'affiliate'  => $result['affiliate'],
				'email'      => $result['email'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'commission' => $this->currency->format($result['commission'], $this->config->get('config_currency')),
				'orders'     => $result['orders'],
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'action'     => $action
			);
		}
					 
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_affiliate'] = $this->language->get('column_affiliate');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_commission'] = $this->language->get('column_commission');
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
		$pagination->total = $affiliate_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/affiliate_commission', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;	
				 
		$this->view->pick('report/affiliate_commission');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
}
?>
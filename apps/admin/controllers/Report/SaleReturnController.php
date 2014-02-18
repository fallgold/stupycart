<?php

namespace Stupycart\Admin\Controllers\Report;

class SaleReturnController extends \Stupycart\Admin\Controllers\ControllerBase {
	public function indexAction() {     
		$this->language->load('report/sale_return');

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
		
		if ($this->request->hasQuery('filter_group')) {
			$filter_group = $this->request->getQueryE('filter_group');
		} else {
			$filter_group = 'week';
		}
		
		if ($this->request->hasQuery('filter_return_status_id')) {
			$filter_return_status_id = $this->request->getQueryE('filter_return_status_id');
		} else {
			$filter_return_status_id = 0;
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

		if ($this->request->hasQuery('filter_return_status_id')) {
			$url .= '&filter_return_status_id=' . $this->request->getQueryE('filter_return_status_id');
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
			'href'      => $this->url->link('report/sale_return', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->model_report_return = new \Stupycart\Common\Models\Admin\Report\Return();
		
		$this->data['returns'] = array();
		
		$data = array(
			'filter_date_start'	      => $filter_date_start, 
			'filter_date_end'	      => $filter_date_end, 
			'filter_group'            => $filter_group,
			'filter_return_status_id' => $filter_return_status_id,
			'start'                   => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                   => $this->config->get('config_admin_limit')
		);
		
		$return_total = $this->model_report_return->getTotalReturns($data);
		
		$results = $this->model_report_return->getReturns($data);
		
		foreach ($results as $result) {
			$this->data['returns'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'returns'    => $result['returns']
			);
		}
				 
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
    	$this->data['column_returns'] = $this->language->get('column_returns');
		$this->data['column_total'] = $this->language->get('column_total');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_group'] = $this->language->get('entry_group');	
		$this->data['entry_status'] = $this->language->get('entry_status');
				
		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->get('token');
		
		$this->model_localisation_return_status = new \Stupycart\Common\Models\Admin\Localisation\ReturnStatus();
		
		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

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

		if ($this->request->hasQuery('filter_return_status_id')) {
			$url .= '&filter_return_status_id=' . $this->request->getQueryE('filter_return_status_id');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $return_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/sale_return', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_return_status_id'] = $filter_return_status_id;
				 
		$this->view->pick('report/sale_return');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
}
?>
<?php  

namespace Stupycart\Admin\Controllers\Report;

class CustomerOnlineController extends \Stupycart\Admin\Controllers\ControllerBase {  
  	public function indexAction() {
		$this->language->load('report/customer_online');
		
    	$this->document->setTitle($this->language->get('heading_title'));
		
		if ($this->request->hasQuery('filter_ip')) {
			$filter_ip = $this->request->getQueryE('filter_ip');
		} else {
			$filter_ip = NULL;
		}
		
		if ($this->request->hasQuery('filter_customer')) {
			$filter_customer = $this->request->getQueryE('filter_customer');
		} else {
			$filter_customer = NULL;
		}
						
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}
																		
		$url = '';
		
		if ($this->request->hasQuery('filter_customer')) {
			$url .= '&filter_customer=' . urlencode($this->request->getQueryE('filter_customer'));
		}
		
		if ($this->request->hasQuery('filter_ip')) {
			$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
		}
						
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
						
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('report/customer_online', 'token=' . $this->session->get('token') . $url, 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->model_report_online = new \Stupycart\Common\Models\Admin\Report\Online();
    	$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
		
		$this->data['customers'] = array();

		$data = array(
			'filter_ip'       => $filter_ip, 
			'filter_customer' => $filter_customer, 
			'start'           => ($page - 1) * 20,
			'limit'           => 20
		);
		
		$customer_total = $this->model_report_online->getTotalCustomersOnline($data);
		
		$results = $this->model_report_online->getCustomersOnline($data);
    	
		foreach ($results as $result) {
			$action = array();
			
			if ($result['customer_id']) {
				$action[] = array(
					'text' => 'Edit',
					'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->get('token') . '&customer_id=' . $result['customer_id'], 'SSL')
				);
			}
			
			$customer_info = $this->model_sale_customer->getCustomer($result['customer_id']);
					
			if ($customer_info) {
				$customer = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
			} else {
				$customer = $this->language->get('text_guest');
			}
								
      		$this->data['customers'][] = array(
				'ip'         => $result['ip'],
				'customer'   => $customer,
				'url'        => $result['url'],
				'referer'    => $result['referer'],
				'date_added' => date('d/m/Y H:i:s', strtotime($result['date_added'])),
				'action'     => $action
			);
		}	
		
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_url'] = $this->language->get('column_url');
		$this->data['column_referer'] = $this->language->get('column_referer');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_filter'] = $this->language->get('button_filter');
				
		$this->data['token'] = $this->session->get('token');
		
		$url = '';
		
		if ($this->request->hasQuery('filter_customer')) {
			$url .= '&filter_customer=' . urlencode($this->request->getQueryE('filter_customer'));
		}
		
		if ($this->request->hasQuery('filter_ip')) {
			$url .= '&filter_ip=' . $this->request->getQueryE('filter_ip');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = 20; 
		$pagination->url = $this->url->link('report/customer_online', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_ip'] = $filter_ip;		
				
		$this->view->pick('report/customer_online');
		$this->_commonAction();
		
		$this->view->setVars($this->data);
  	}
}
?>
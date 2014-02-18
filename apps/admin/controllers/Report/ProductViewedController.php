<?php

namespace Stupycart\Admin\Controllers\Report;

class ProductViewedController extends \Stupycart\Admin\Controllers\ControllerBase {
	public function indexAction() {     
		$this->language->load('report/product_viewed');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}

		$url = '';
				
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
			'href'      => $this->url->link('report/product_viewed', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->model_report_product = new \Stupycart\Common\Models\Admin\Report\Product();
		
		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
				
		$product_viewed_total = $this->model_report_product->getTotalProductsViewed($data); 
		
		$product_views_total = $this->model_report_product->getTotalProductViews(); 
		
		$this->data['products'] = array();
		
		$results = $this->model_report_product->getProductsViewed($data);
		
		foreach ($results as $result) {
			if ($result['viewed']) {
				$percent = round($result['viewed'] / $product_views_total * 100, 2);
			} else {
				$percent = 0;
			}
					
			$this->data['products'][] = array(
				'name'    => $result['name'],
				'model'   => $result['model'],
				'viewed'  => $result['viewed'],
				'percent' => $percent . '%'			
			);
		}
 		
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_viewed'] = $this->language->get('column_viewed');
		$this->data['column_percent'] = $this->language->get('column_percent');
		
		$this->data['button_reset'] = $this->language->get('button_reset');

		$url = '';		
				
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
				
		$this->data['reset'] = $this->url->link('report/product_viewed/reset', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}
						
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $product_viewed_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/product_viewed', 'token=' . $this->session->get('token') . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
				 
		$this->view->pick('report/product_viewed');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	public function resetAction() {
		$this->language->load('report/product_viewed');
		
		$this->model_report_product = new \Stupycart\Common\Models\Admin\Report\Product();
		
		$this->model_report_product->reset();
		
		$this->session->set('success', $this->language->get('text_success'));
		
		$this->response->redirect($this->url->link('report/product_viewed', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
	}
}
?>
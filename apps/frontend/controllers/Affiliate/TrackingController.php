<?php 

namespace Stupycart\Frontend\Controllers\Affiliate;

class TrackingController extends \Stupycart\Frontend\Controllers\ControllerBase { 
	public function indexAction() {
		if (!$this->affiliate->isLogged()) {
	  		$this->session->set('redirect', $this->url->link('affiliate/tracking', '', 'SSL'));
	  
	  		$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'), true);
		return;
    	} 
	
		$this->language->load('affiliate/tracking');

		$this->document->setTitle($this->language->get('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('affiliate/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('affiliate/tracking', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_description'] = sprintf($this->language->get('text_description'), $this->config->get('config_name'));
		$this->data['text_code'] = $this->language->get('text_code');
		$this->data['text_generator'] = $this->language->get('text_generator');
		$this->data['text_link'] = $this->language->get('text_link');
		
		$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['code'] = $this->affiliate->getCode();
		
		$this->data['continue'] = $this->url->link('affiliate/account', '', 'SSL');

		$this->view->pick('affiliate/tracking');
		
		$this->_commonAction();
				
		$this->view->setVars($this->data);		
  	}
	
	public function autocompleteAction() {
		$json = array();
		
		if ($this->request->hasQuery('filter_name')) {
			$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
			 
			$data = array(
				'filter_name' => $this->request->getQueryE('filter_name'),
				'start'       => 0,
				'limit'       => 20
			);
			
			$results = $this->model_catalog_product->getProducts($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'link' => str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $result['product_id'] . '&tracking=' . $this->affiliate->getCode()))			
				);	
			}
		}

		$this->response->setContent(json_encode($json));
		return $this->response;
	}
}
?>
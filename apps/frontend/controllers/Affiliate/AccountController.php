<?php 

namespace Stupycart\Frontend\Controllers\Affiliate;

class AccountController extends \Stupycart\Frontend\Controllers\ControllerBase { 
	public function indexAction() {
		if (!$this->affiliate->isLogged()) {
	  		$this->session->set('redirect', $this->url->link('affiliate/account', '', 'SSL'));
	  
	  		$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'), true);
		return;
    	} 
	
		$this->language->load('affiliate/account');

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

		$this->document->setTitle($this->language->get('heading_title'));

    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_my_account'] = $this->language->get('text_my_account');
    	$this->data['text_my_tracking'] = $this->language->get('text_my_tracking');
		$this->data['text_my_transactions'] = $this->language->get('text_my_transactions');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_password'] = $this->language->get('text_password');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_tracking'] = $this->language->get('text_tracking');
		$this->data['text_transaction'] = $this->language->get('text_transaction');
		
		if ($this->session->has('success')) {
    		$this->data['success'] = $this->session->get('success');
			
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}

    	$this->data['edit'] = $this->url->link('affiliate/edit', '', 'SSL');
		$this->data['password'] = $this->url->link('affiliate/password', '', 'SSL');
		$this->data['payment'] = $this->url->link('affiliate/payment', '', 'SSL');
		$this->data['tracking'] = $this->url->link('affiliate/tracking', '', 'SSL');
    	$this->data['transaction'] = $this->url->link('affiliate/transaction', '', 'SSL');

		$this->view->pick('affiliate/account');
		
		$this->_commonAction();
				
		$this->view->setVars($this->data);		
  	}
}
?>
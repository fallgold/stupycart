<?php

namespace Stupycart\Frontend\Controllers\Affiliate;

class PasswordController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();
	     
  	public function indexAction() {	
    	if (!$this->affiliate->isLogged()) {
      		$this->session->set('redirect', $this->url->link('affiliate/password', '', 'SSL'));

      		$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'), true);
		return;
    	}

		$this->language->load('affiliate/password');

    	$this->document->setTitle($this->language->get('heading_title'));
			  
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_affiliate_affiliate = new \Stupycart\Common\Models\Affiliate\Affiliate();
			
			$this->model_affiliate_affiliate->editPassword($this->affiliate->getEmail(), $this->request->getPostE('password'));
 
      		$this->session->set('success', $this->language->get('text_success'));
	  
	  		$this->response->redirect($this->url->link('affiliate/account', '', 'SSL'), true);
		return;
    	}

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
			'href'      => $this->url->link('affiliate/password', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_password'] = $this->language->get('text_password');

    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');

    	$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_back'] = $this->language->get('button_back');
    	
		if (isset($this->error['password'])) { 
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) { 
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
	
    	$this->data['action'] = $this->url->link('affiliate/password', '', 'SSL');
		
		if ($this->request->hasPost('password')) {
    		$this->data['password'] = $this->request->getPostE('password');
		} else {
			$this->data['password'] = '';
		}

		if ($this->request->hasPost('confirm')) {
    		$this->data['confirm'] = $this->request->getPostE('confirm');
		} else {
			$this->data['confirm'] = '';
		}

    	$this->data['back'] = $this->url->link('affiliate/account', '', 'SSL');

		$this->view->pick('affiliate/password');
		
		$this->_commonAction();
								
		$this->view->setVars($this->data);				
  	}
  
  	protected function validate() {
    	if ((utf8_strlen($this->request->getPostE('password')) < 4) || (utf8_strlen($this->request->getPostE('password')) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->getPostE('confirm') != $this->request->getPostE('password')) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}  
	
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
}
?>

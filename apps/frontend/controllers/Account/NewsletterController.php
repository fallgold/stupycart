<?php 

namespace Stupycart\Frontend\Controllers\Account;

class NewsletterController extends \Stupycart\Frontend\Controllers\ControllerBase {  
	public function indexAction() {
		if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->link('account/newsletter', '', 'SSL'));
	  
	  		$this->response->redirect($this->url->link('account/login', '', 'SSL'), true);
		return;
    	} 
		
		$this->language->load('account/newsletter');
    	
		$this->document->setTitle($this->language->get('heading_title'));
				
		if ($this->request->getServer('REQUEST_METHOD') == 'POST') {
			$this->model_account_customer = new \Stupycart\Common\Models\Account\Customer();
			
			$this->model_account_customer->editNewsletter($this->request->getPostE('newsletter'));
			
			$this->session->set('success', $this->language->get('text_success'));
			
			$this->response->redirect($this->url->link('account/account', '', 'SSL'), true);
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
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_newsletter'),
			'href'      => $this->url->link('account/newsletter', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

    	$this->data['action'] = $this->url->link('account/newsletter', '', 'SSL');
		
		$this->data['newsletter'] = $this->customer->getNewsletter();
		
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		$this->view->pick('account/newsletter');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);			
  	}
}
?>
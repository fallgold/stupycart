<?php 

namespace Stupycart\Frontend\Controllers\Affiliate;

class LogoutController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	if ($this->affiliate->isLogged()) {
      		$this->affiliate->logout();
			
      		$this->response->redirect($this->url->link('affiliate/logout', '', 'SSL'), true);
		return;
    	}
 
    	$this->language->load('affiliate/logout');
		
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
        	'text'      => $this->language->get('text_logout'),
			'href'      => $this->url->link('affiliate/logout', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');

		$this->view->pick('common/success');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);	
  	}
}
?>
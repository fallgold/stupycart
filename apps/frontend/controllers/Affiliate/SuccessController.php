<?php 

namespace Stupycart\Frontend\Controllers\Affiliate;

class SuccessController extends \Stupycart\Frontend\Controllers\ControllerBase {  
	public function indexAction() {
    	$this->language->load('affiliate/success');
  
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
        	'text'      => $this->language->get('text_success'),
			'href'      => $this->url->link('affiliate/success'),
        	'separator' => $this->language->get('text_separator')
      	);

    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), $this->url->link('information/contact'));
		
    	$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = $this->url->link('affiliate/account', '', 'SSL');

		$this->view->pick('common/success');
		
		$this->_commonAction();
				
		$this->view->setVars($this->data);				
  	}
}
?>
<?php    

namespace Stupycart\Admin\Controllers\Error;

class NotFoundController extends \Stupycart\Admin\Controllers\ControllerBase {    
	public function indexAction() { 
    	$this->language->load('error/not_found');
 
    	$this->document->setTitle($this->language->get('heading_title'));

    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_not_found'] = $this->language->get('text_not_found');

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('error/not_found', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);

		$this->view->pick('error/not_found');
		$this->_commonAction();
				
		$this->view->setVars($this->data);	
  	}
}
?>
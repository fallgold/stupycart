<?php

namespace Stupycart\Frontend\Controllers\Common;

class MaintenanceController extends \Stupycart\Frontend\Controllers\ControllerBase {
    public function indexAction() {
        if ($this->config->get('config_maintenance')) {
			$route = '';
			
			if ($this->request->hasQuery('route')) {
				$part = explode('/', $this->request->getQueryE('route'));
				
				if (isset($part[0])) {
					$route .= $part[0];
				}			
			}
			
			// Show site if logged in as admin
			
			$this->user = new \Libs\Opencart\User($this->registry);
	
			if (($route != 'payment') && !$this->user->isLogged()) {
				return $this->forward('common/maintenance/info');
			}						
        }
    }
		
	public function infoAction() {
        $this->language->load('common/maintenance');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->data['heading_title'] = $this->language->get('heading_title');
                
        $this->document->breadcrumbs = array();

        $this->document->breadcrumbs[] = array(
            'text'      => $this->language->get('text_maintenance'),
			'href'      => $this->url->link('common/maintenance'),
            'separator' => false
        ); 
        
        $this->data['message'] = $this->language->get('text_message');
      
           $this->view->pick('common/maintenance');
		
		$this->_commonAction();
		
		$this->view->setVars($this->data);
    }
}
?>
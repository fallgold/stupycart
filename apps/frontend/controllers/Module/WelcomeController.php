<?php  

namespace Stupycart\Frontend\Controllers\Module;

class WelcomeController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction($setting) {
		$this->language->load('module/welcome');
		
    	$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
    	
		$this->data['message'] = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

		$this->view->pick('module/welcome');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
}
?>
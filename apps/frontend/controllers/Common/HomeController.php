<?php  

namespace Stupycart\Frontend\Controllers\Common;

class HomeController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));

		$this->data['heading_title'] = $this->config->get('config_title');
		
		$this->view->pick('common/home');
		
		$this->_commonAction();
										
		$this->view->setVars($this->data);
	}
}
?>
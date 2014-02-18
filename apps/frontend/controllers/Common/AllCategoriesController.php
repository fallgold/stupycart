<?php  

namespace Stupycart\Frontend\Controllers\Common;

class AllCategoriesController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {	
		if(!$this->isVisitorMobile()) {
			$this->response->redirect($this->url->link('common/home'), true);
		} else {
			$this->document->setTitle($this->config->get('config_title'));
			$this->document->setDescription($this->config->get('config_meta_description'));

			$this->data['heading_title'] = $this->config->get('config_title');
			
			
			$this->view->pick('common/all_categories');
			
		$this->_commonAction();
		}
		$this->view->setVars($this->data);
	}
}
?>
<?php

namespace Stupycart\Admin\Controllers\Common;

class FooterController extends \Stupycart\Admin\Controllers\ControllerBase {   
	public function indexAction() {
		$this->language->load('common/footer');
		
		$this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);
		
		if (file_exists(DIR_SYSTEM . 'config/svn/svn.ver')) {
			$this->data['text_footer'] .= '.r' . trim(file_get_contents(DIR_SYSTEM . 'config/svn/svn.ver'));
		}
		
		$this->view->pick('common/footer');
	
    	$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
  	}
}
?>
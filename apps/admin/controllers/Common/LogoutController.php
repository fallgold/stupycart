<?php       

namespace Stupycart\Admin\Controllers\Common;

class LogoutController extends \Stupycart\Admin\Controllers\ControllerBase {   
	public function indexAction() { 
    	$this->user->logout();
 
 		$this->session->remove('token');

		$this->response->redirect($this->url->link('common/login', '', 'SSL'), true);
		return;
  	}
}  
?>
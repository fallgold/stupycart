<?php 

namespace Stupycart\Frontend\Controllers\Product;

class SearchMobileController extends \Stupycart\Frontend\Controllers\ControllerBase { 	
	public function indexAction() { 		
		$query = "";
		foreach($_POST as $key => $value) {
		   $query .= '&' . $key . '=' . $value;
		}		
		
		$this->response->redirect($this->url->link('product/search') . $query, true);	
  	}
}
?>
<?php  

namespace Stupycart\Frontend\Controllers\Common;

class ContentTopController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->model_design_layout = new \Stupycart\Common\Models\Design\Layout();
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		$this->model_catalog_information = new \Stupycart\Common\Models\Catalog\Information();
		
		if ($this->request->hasQuery('route')) {
			$route = (string)$this->request->getQueryE('route');
		} else {
			$route = 'common/home';
		}
		
		$layout_id = 0;
		
		if ($route == 'product/category' && $this->request->hasQuery('path')) {
			$path = explode('_', (string)$this->request->getQueryE('path'));
				
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));			
		}
		
		if ($route == 'product/product' && $this->request->hasQuery('product_id')) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->getQueryE('product_id'));
		}
		
		if ($route == 'information/information' && $this->request->hasQuery('information_id')) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->getQueryE('information_id'));
		}
		
		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}
				
		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$module_data = array();
		
		$this->model_setting_extension = new \Stupycart\Common\Models\Setting\Extension();
		
		$extensions = $this->model_setting_extension->getExtensions('module');		
		
		foreach ($extensions as $extension) {
			$modules = $this->config->get($extension['code'] . '_module');
			
			if ($modules) {
				foreach ($modules as $module) {
					if ($module['layout_id'] == $layout_id && $module['position'] == 'content_top' && $module['status']) {
						$module_data[] = array(
							'code'       => $extension['code'],
							'setting'    => $module,
							'sort_order' => $module['sort_order']
						);				
					}
				}
			}
		}
		
		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}
		
		array_multisort($sort_order, SORT_ASC, $module_data);
		
		$this->data['modules'] = array();
		
		foreach ($module_data as $module) {
			$module = $this->getChild('module/' . $module['code'], $module['setting']);
			
			if ($module) {
				$this->data['modules'][] = $module;
			}
		}

		$this->view->pick('common/content_top');
								
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
}
?>
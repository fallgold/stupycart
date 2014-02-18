<?php  

namespace Stupycart\Frontend\Controllers\Module;

class FilterController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction($setting) {
		if ($this->request->hasQuery('path')) {
			$parts = explode('_', (string)$this->request->getQueryE('path'));
		} else {
			$parts = array();
		}
		
		$category_id = end($parts);
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		
		$category_info = $this->model_catalog_category->getCategory($category_id);
		
		if ($category_info) {
			$this->language->load('module/filter');
		
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['button_filter'] = $this->language->get('button_filter');
			
			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}	

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}	
			
			if ($this->request->hasQuery('limit')) {
				$url .= '&limit=' . $this->request->getQueryE('limit');
			}
									
			$this->data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->getQueryE('path') . $url));
			
			if ($this->request->hasQuery('filter')) {
				$this->data['filter_category'] = explode(',', $this->request->getQueryE('filter'));
			} else {
				$this->data['filter_category'] = array();
			}
			
			$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();

			$this->data['filter_groups'] = array();
			
			$filter_groups = $this->model_catalog_category->getCategoryFilters($category_id);
			
			if ($filter_groups) {
				foreach ($filter_groups as $filter_group) {
					$filter_data = array();
					
					foreach ($filter_group['filter'] as $filter) {
						$data = array(
							'filter_category_id' => $category_id,
							'filter_filter'      => $filter['filter_id']
						);	
						
						$filter_data[] = array(
							'filter_id' => $filter['filter_id'],
							'name'      => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($data) . ')' : '')
						);
					}
					
					$this->data['filter_groups'][] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);
				} 
			
				$this->view->pick('module/filter');
				
				$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
			}
		}
  	}
}
?>
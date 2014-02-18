<?php 

namespace Stupycart\Admin\Controllers\Catalog;

class CategoryController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
 
	public function indexAction() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
		 
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->addCategory($this->request->getPostE());

			$this->session->set('success', $this->language->get('text_success'));

			$url = '';

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
						
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return; 
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_category->editCategory($this->request->getQueryE('category_id'), $this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));
			
			$url = '';

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
						
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}

			$this->session->set('success', $this->language->get('text_success'));
			
			$url = '';

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
			
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
		
		$this->getList();
	}
	
	public function repairAction() {
		$this->language->load('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
		
		if ($this->validateRepair()) {
			$this->model_catalog_category->repairCategories();

			$this->session->set('success', $this->language->get('text_success'));
			
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
		
		$this->getList();	
	}
	
	protected function getList() {
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}
		
		$url = '';
		
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
						
   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
									
		$this->data['insert'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['repair'] = $this->url->link('catalog/category/repair', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		$this->data['categories'] = array();
		
		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
				
		$category_total = $this->model_catalog_category->getTotalCategories();
		
		$results = $this->model_catalog_category->getCategories($data);

		foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/category/update', 'token=' . $this->session->get('token') . '&category_id=' . $result['category_id'] . $url, 'SSL')
			);

			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => $this->request->hasPost('selected') && in_array($result['category_id'], $this->request->getPostE('selected')),
				'action'      => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 		$this->data['button_repair'] = $this->language->get('button_repair');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}
		
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->view->pick('catalog/category_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_parent'] = $this->language->get('entry_parent');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_top'] = $this->language->get('entry_top');
		$this->data['entry_column'] = $this->language->get('entry_column');		
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	
 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('category_id')) {
			$this->data['action'] = $this->url->link('catalog/category/insert', 'token=' . $this->session->get('token'), 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/category/update', 'token=' . $this->session->get('token') . '&category_id=' . $this->request->getQueryE('category_id'), 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->get('token'), 'SSL');

		if ($this->request->hasQuery('category_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$category_info = $this->model_catalog_category->getCategory($this->request->getQueryE('category_id'));
    	}
		
		$this->data['token'] = $this->session->get('token');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if ($this->request->hasPost('category_description')) {
			$this->data['category_description'] = $this->request->getPostE('category_description');
		} elseif ($this->request->hasQuery('category_id')) {
			$this->data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->getQueryE('category_id'));
		} else {
			$this->data['category_description'] = array();
		}

		if ($this->request->hasPost('path')) {
			$this->data['path'] = $this->request->getPostE('path');
		} elseif (!empty($category_info)) {
			$this->data['path'] = $category_info['path'];
		} else {
			$this->data['path'] = '';
		}
		
		if ($this->request->hasPost('parent_id')) {
			$this->data['parent_id'] = $this->request->getPostE('parent_id');
		} elseif (!empty($category_info)) {
			$this->data['parent_id'] = $category_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
		}

		$this->model_catalog_filter = new \Stupycart\Common\Models\Admin\Catalog\Filter();
		
		if ($this->request->hasPost('category_filter')) {
			$filters = $this->request->getPostE('category_filter');
		} elseif ($this->request->hasQuery('category_id')) {		
			$filters = $this->model_catalog_category->getCategoryFilters($this->request->getQueryE('category_id'));
		} else {
			$filters = array();
		}
	
		$this->data['category_filters'] = array();
		
		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);
			
			if ($filter_info) {
				$this->data['category_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}	
										
		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if ($this->request->hasPost('category_store')) {
			$this->data['category_store'] = $this->request->getPostE('category_store');
		} elseif ($this->request->hasQuery('category_id')) {
			$this->data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->getQueryE('category_id'));
		} else {
			$this->data['category_store'] = array(0);
		}			
		
		if ($this->request->hasPost('keyword')) {
			$this->data['keyword'] = $this->request->getPostE('keyword');
		} elseif (!empty($category_info)) {
			$this->data['keyword'] = $category_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if ($this->request->hasPost('image')) {
			$this->data['image'] = $this->request->getPostE('image');
		} elseif (!empty($category_info)) {
			$this->data['image'] = $category_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->model_tool_image = new \Stupycart\Common\Models\Admin\Tool\Image();

		if ($this->request->hasPost('image') && file_exists(DIR_IMAGE . $this->request->getPostE('image'))) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->getPostE('image'), 100, 100);
		} elseif (!empty($category_info) && $category_info['image'] && file_exists(DIR_IMAGE . $category_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		if ($this->request->hasPost('top')) {
			$this->data['top'] = $this->request->getPostE('top');
		} elseif (!empty($category_info)) {
			$this->data['top'] = $category_info['top'];
		} else {
			$this->data['top'] = 0;
		}
		
		if ($this->request->hasPost('column')) {
			$this->data['column'] = $this->request->getPostE('column');
		} elseif (!empty($category_info)) {
			$this->data['column'] = $category_info['column'];
		} else {
			$this->data['column'] = 1;
		}
				
		if ($this->request->hasPost('sort_order')) {
			$this->data['sort_order'] = $this->request->getPostE('sort_order');
		} elseif (!empty($category_info)) {
			$this->data['sort_order'] = $category_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		if ($this->request->hasPost('status')) {
			$this->data['status'] = $this->request->getPostE('status');
		} elseif (!empty($category_info)) {
			$this->data['status'] = $category_info['status'];
		} else {
			$this->data['status'] = 1;
		}
				
		if ($this->request->hasPost('category_layout')) {
			$this->data['category_layout'] = $this->request->getPostE('category_layout');
		} elseif ($this->request->hasQuery('category_id')) {
			$this->data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->getQueryE('category_id'));
		} else {
			$this->data['category_layout'] = array();
		}

		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->view->pick('catalog/category_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->getPostE('category_description') as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
					
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}
	
	protected function validateRepair() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 
		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}
			
	public function autocompleteAction() {
		$json = array();
		
		if ($this->request->hasQuery('filter_name')) {
			$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
			
			$data = array(
				'filter_name' => $this->request->getQueryE('filter_name'),
				'start'       => 0,
				'limit'       => 20
			);
			
			$results = $this->model_catalog_category->getCategories($data);
				
			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}		
		}

		$sort_order = array();
	  
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setContent(json_encode($json));
		return $this->response;
	}		
}
?>
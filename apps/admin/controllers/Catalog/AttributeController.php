<?php 

namespace Stupycart\Admin\Controllers\Catalog;

class AttributeController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
   
  	public function indexAction() {
		$this->language->load('catalog/attribute');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_attribute = new \Stupycart\Common\Models\Admin\Catalog\Attribute();
		
    	$this->getList();
  	}
              
  	public function insertAction() {
		$this->language->load('catalog/attribute');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_attribute = new \Stupycart\Common\Models\Admin\Catalog\Attribute();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
      		$this->model_catalog_attribute->addAttribute($this->request->getPostE());
		  	
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
						
      		$this->response->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
	
    	$this->getForm();
  	}

  	public function updateAction() {
		$this->language->load('catalog/attribute');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_attribute = new \Stupycart\Common\Models\Admin\Catalog\Attribute();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
	  		$this->model_catalog_attribute->editAttribute($this->request->getQueryE('attribute_id'), $this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
			
			$this->response->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getForm();
  	}

  	public function deleteAction() {
		$this->language->load('catalog/attribute');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_attribute = new \Stupycart\Common\Models\Admin\Catalog\Attribute();
		
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $attribute_id) {
				$this->model_catalog_attribute->deleteAttribute($attribute_id);
			}
			      		
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
			
			$this->response->redirect($this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
   		}
	
    	$this->getList();
  	}
    
  	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'ad.name';
		}
		
		if ($this->request->hasQuery('order')) {
			$order = $this->request->getQueryE('order');
		} else {
			$order = 'ASC';
		}
		
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}
				
		$url = '';
			
		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}

		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
		
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
			'href'      => $this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('catalog/attribute/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/attribute/delete', 'token=' . $this->session->get('token') . $url, 'SSL');	

		$this->data['attributes'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$attribute_total = $this->model_catalog_attribute->getTotalAttributes();
	
		$results = $this->model_catalog_attribute->getAttributes($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/attribute/update', 'token=' . $this->session->get('token') . '&attribute_id=' . $result['attribute_id'] . $url, 'SSL')
			);
						
			$this->data['attributes'][] = array(
				'attribute_id'    => $result['attribute_id'],
				'name'            => $result['name'],
				'attribute_group' => $result['attribute_group'],
				'sort_order'      => $result['sort_order'],
				'selected'        => $this->request->hasPost('selected') && in_array($result['attribute_id'], $this->request->getPostE('selected')),
				'action'          => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_attribute_group'] = $this->language->get('column_attribute_group');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
		
		$this->data['sort_name'] = $this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . '&sort=ad.name' . $url, 'SSL');
		$this->data['sort_attribute_group'] = $this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . '&sort=attribute_group' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . '&sort=a.sort_order' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $attribute_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('catalog/attribute_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
  
  	protected function getForm() {
     	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_attribute_group'] = $this->language->get('entry_attribute_group');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    
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
		
		$url = '';
			
		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}

		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
		
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
			'href'      => $this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('attribute_id')) {
			$this->data['action'] = $this->url->link('catalog/attribute/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/attribute/update', 'token=' . $this->session->get('token') . '&attribute_id=' . $this->request->getQueryE('attribute_id') . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('catalog/attribute', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('attribute_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($this->request->getQueryE('attribute_id'));
		}
				
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('attribute_description')) {
			$this->data['attribute_description'] = $this->request->getPostE('attribute_description');
		} elseif ($this->request->hasQuery('attribute_id')) {
			$this->data['attribute_description'] = $this->model_catalog_attribute->getAttributeDescriptions($this->request->getQueryE('attribute_id'));
		} else {
			$this->data['attribute_description'] = array();
		}

		if ($this->request->hasPost('attribute_group_id')) {
			$this->data['attribute_group_id'] = $this->request->getPostE('attribute_group_id');
		} elseif (!empty($attribute_info)) {
			$this->data['attribute_group_id'] = $attribute_info['attribute_group_id'];
		} else {
			$this->data['attribute_group_id'] = '';
		}
		
		$this->model_catalog_attribute_group = new \Stupycart\Common\Models\Admin\Catalog\AttributeGroup();
				
		$this->data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();	

		if ($this->request->hasPost('sort_order')) {
			$this->data['sort_order'] = $this->request->getPostE('sort_order');
		} elseif (!empty($attribute_info)) {
			$this->data['sort_order'] = $attribute_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		$this->view->pick('catalog/attribute_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);	
  	}
  	
	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->getPostE('attribute_description') as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		
		foreach ($this->request->getPostE('selected') as $attribute_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByAttributeId($attribute_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
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
			$this->model_catalog_attribute = new \Stupycart\Common\Models\Admin\Catalog\Attribute();
			
			$data = array(
				'filter_name' => $this->request->getQueryE('filter_name'),
				'start'       => 0,
				'limit'       => 20
			);
			
			$json = array();
			
			$results = $this->model_catalog_attribute->getAttributes($data);
			
			foreach ($results as $result) {
				$json[] = array(
					'attribute_id'    => $result['attribute_id'], 
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute_group' => $result['attribute_group']
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
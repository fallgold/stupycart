<?php 

namespace Stupycart\Admin\Controllers\Localisation;

class LanguageController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
  
	public function indexAction() {
		$this->language->load('localisation/language');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('localisation/language');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_language->addLanguage($this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('localisation/language', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('localisation/language');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_language->editLanguage($this->request->getQueryE('language_id'), $this->request->getPostE());
			
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
					
			$this->response->redirect($this->url->link('localisation/language', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('localisation/language');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $language_id) {
				$this->model_localisation_language->deleteLanguage($language_id);
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

			$this->response->redirect($this->url->link('localisation/language', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'name';
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
			'href'      => $this->url->link('localisation/language', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
	
		$this->data['insert'] = $this->url->link('localisation/language/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/language/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
	
		$this->data['languages'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$language_total = $this->model_localisation_language->getTotalLanguages();
		
		$results = $this->model_localisation_language->getLanguages($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/language/update', 'token=' . $this->session->get('token') . '&language_id=' . $result['language_id'] . $url, 'SSL')
			);
					
			$this->data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : null),
				'code'        => $result['code'],
				'sort_order'  => $result['sort_order'],
				'selected'    => $this->request->hasPost('selected') && in_array($result['language_id'], $this->request->getPostE('selected')),
				'action'      => $action	
			);		
		}
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
    	$this->data['column_code'] = $this->language->get('column_code');
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
					
		$this->data['sort_name'] = $this->url->link('localisation/language', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		$this->data['sort_code'] = $this->url->link('localisation/language', 'token=' . $this->session->get('token') . '&sort=code' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('localisation/language', 'token=' . $this->session->get('token') . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $language_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/language', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/language_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_locale'] = $this->language->get('entry_locale');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_directory'] = $this->language->get('entry_directory');
		$this->data['entry_filename'] = $this->language->get('entry_filename');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');

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
			$this->data['error_name'] = '';
		}

 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}
		
 		if (isset($this->error['locale'])) {
			$this->data['error_locale'] = $this->error['locale'];
		} else {
			$this->data['error_locale'] = '';
		}		
		
 		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = '';
		}	
		
 		if (isset($this->error['directory'])) {
			$this->data['error_directory'] = $this->error['directory'];
		} else {
			$this->data['error_directory'] = '';
		}	
		
 		if (isset($this->error['filename'])) {
			$this->data['error_filename'] = $this->error['filename'];
		} else {
			$this->data['error_filename'] = '';
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
			'href'      => $this->url->link('localisation/language', 'token=' . $this->session->get('token') . $url, 'SSL'),      		
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('language_id')) {
			$this->data['action'] = $this->url->link('localisation/language/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/language/update', 'token=' . $this->session->get('token') . '&language_id=' . $this->request->getQueryE('language_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('localisation/language', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('language_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$language_info = $this->model_localisation_language->getLanguage($this->request->getQueryE('language_id'));
		}

		if ($this->request->hasPost('name')) {
			$this->data['name'] = $this->request->getPostE('name');
		} elseif (!empty($language_info)) {
			$this->data['name'] = $language_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if ($this->request->hasPost('code')) {
			$this->data['code'] = $this->request->getPostE('code');
		} elseif (!empty($language_info)) {
			$this->data['code'] = $language_info['code'];
		} else {
			$this->data['code'] = '';
		}

		if ($this->request->hasPost('locale')) {
			$this->data['locale'] = $this->request->getPostE('locale');
		} elseif (!empty($language_info)) {
			$this->data['locale'] = $language_info['locale'];
		} else {
			$this->data['locale'] = '';
		}
		
		if ($this->request->hasPost('image')) {
			$this->data['image'] = $this->request->getPostE('image');
		} elseif (!empty($language_info)) {
			$this->data['image'] = $language_info['image'];
		} else {
			$this->data['image'] = '';
		}

		if ($this->request->hasPost('directory')) {
			$this->data['directory'] = $this->request->getPostE('directory');
		} elseif (!empty($language_info)) {
			$this->data['directory'] = $language_info['directory'];
		} else {
			$this->data['directory'] = '';
		}

		if ($this->request->hasPost('filename')) {
			$this->data['filename'] = $this->request->getPostE('filename');
		} elseif (!empty($language_info)) {
			$this->data['filename'] = $language_info['filename'];
		} else {
			$this->data['filename'] = '';
		}

		if ($this->request->hasPost('sort_order')) {
			$this->data['sort_order'] = $this->request->getPostE('sort_order');
		} elseif (!empty($language_info)) {
			$this->data['sort_order'] = $language_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}

    	if ($this->request->hasPost('status')) {
      		$this->data['status'] = $this->request->getPostE('status');
    	} elseif (!empty($language_info)) {
			$this->data['status'] = $language_info['status'];
		} else {
      		$this->data['status'] = 1;
    	}

		$this->view->pick('localisation/language_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->getPostE('name')) < 3) || (utf8_strlen($this->request->getPostE('name')) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (utf8_strlen($this->request->getPostE('code')) < 2) {
			$this->error['code'] = $this->language->get('error_code');
		}

		if (!$this->request->getPostE('locale')) {
			$this->error['locale'] = $this->language->get('error_locale');
		}
		
		if (!$this->request->getPostE('directory')) { 
			$this->error['directory'] = $this->language->get('error_directory'); 
		}

		if (!$this->request->getPostE('filename')) {
			$this->error['filename'] = $this->language->get('error_filename');
		}
		
		if ((utf8_strlen($this->request->getPostE('image')) < 3) || (utf8_strlen($this->request->getPostE('image')) > 32)) {
			$this->error['image'] = $this->language->get('error_image');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} 
		
		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		$this->model_sale_order = new \Stupycart\Common\Models\Admin\Sale\Order();
		
		foreach ($this->request->getPostE('selected') as $language_id) {
			$language_info = $this->model_localisation_language->getLanguage($language_id);

			if ($language_info) {
				if ($this->config->get('config_language') == $language_info['code']) {
					$this->error['warning'] = $this->language->get('error_default');
				}
				
				if ($this->config->get('config_admin_language') == $language_info['code']) {
					$this->error['warning'] = $this->language->get('error_admin');
				}	
			
				$store_total = $this->model_setting_store->getTotalStoresByLanguage($language_info['code']);
	
				if ($store_total) {
					$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
				}
			}
				
			$order_total = $this->model_sale_order->getTotalOrdersByLanguageId($language_id);

			if ($order_total) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
			}		
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}	
}
?>
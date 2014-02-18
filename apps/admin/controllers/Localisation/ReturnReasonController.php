<?php 

namespace Stupycart\Admin\Controllers\Localisation;

class ReturnReasonController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
   
  	public function indexAction() {
		$this->language->load('localisation/return_reason');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_return_reason = new \Stupycart\Common\Models\Admin\Localisation\ReturnReason();
		
    	$this->getList();
  	}
              
  	public function insertAction() {
		$this->language->load('localisation/return_reason');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_return_reason = new \Stupycart\Common\Models\Admin\Localisation\ReturnReason();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
      		$this->model_localisation_return_reason->addReturnReason($this->request->getPostE());
		  	
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
						
      		$this->response->redirect($this->url->link('localisation/return_reason', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
	
    	$this->getForm();
  	}

  	public function updateAction() {
		$this->language->load('localisation/return_reason');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_return_reason = new \Stupycart\Common\Models\Admin\Localisation\ReturnReason();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
	  		$this->model_localisation_return_reason->editReturnReason($this->request->getQueryE('return_reason_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('localisation/return_reason', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getForm();
  	}

  	public function deleteAction() {
		$this->language->load('localisation/return_reason');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_return_reason = new \Stupycart\Common\Models\Admin\Localisation\ReturnReason();
		
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $return_reason_id) {
				$this->model_localisation_return_reason->deleteReturnReason($return_reason_id);
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
			
			$this->response->redirect($this->url->link('localisation/return_reason', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
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
			'href'      => $this->url->link('localisation/return_reason', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('localisation/return_reason/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/return_reason/delete', 'token=' . $this->session->get('token') . $url, 'SSL');	

		$this->data['return_reasons'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$return_reason_total = $this->model_localisation_return_reason->getTotalReturnReasons();
	
		$results = $this->model_localisation_return_reason->getReturnReasons($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/return_reason/update', 'token=' . $this->session->get('token') . '&return_reason_id=' . $result['return_reason_id'] . $url, 'SSL')
			);
						
			$this->data['return_reasons'][] = array(
				'return_reason_id' => $result['return_reason_id'],
				'name'          => $result['name'],
				'selected'      => $this->request->hasPost('selected') && in_array($result['return_reason_id'], $this->request->getPostE('selected')),
				'action'        => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
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
		
		$this->data['sort_name'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $return_reason_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/return_reason', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/return_reason_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
  
  	protected function getForm() {
     	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['entry_name'] = $this->language->get('entry_name');

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
			'href'      => $this->url->link('localisation/return_reason', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('return_reason_id')) {
			$this->data['action'] = $this->url->link('localisation/return_reason/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/return_reason/update', 'token=' . $this->session->get('token') . '&return_reason_id=' . $this->request->getQueryE('return_reason_id') . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('return_reason')) {
			$this->data['return_reason'] = $this->request->getPostE('return_reason');
		} elseif ($this->request->hasQuery('return_reason_id')) {
			$this->data['return_reason'] = $this->model_localisation_return_reason->getReturnReasonDescriptions($this->request->getQueryE('return_reason_id'));
		} else {
			$this->data['return_reason'] = array();
		}

		$this->view->pick('localisation/return_reason_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);	
  	}
  	
	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/return_reason')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->getPostE('return_reason') as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
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
		if (!$this->user->hasPermission('modify', 'localisation/return_reason')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->model_sale_return = new \Stupycart\Common\Models\Admin\Sale\ReturnModel();
		
		foreach ($this->request->getPostE('selected') as $return_reason_id) {
			$return_total = $this->model_sale_return->getTotalReturnsByReturnReasonId($return_reason_id);
		
			if ($return_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_return'), $return_total);	
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
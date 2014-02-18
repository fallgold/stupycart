<?php 

namespace Stupycart\Admin\Controllers\Localisation;

class ReturnStatusController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
   
  	public function indexAction() {
		$this->language->load('localisation/return_status');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_return_status = new \Stupycart\Common\Models\Admin\Localisation\ReturnStatus();
		
    	$this->getList();
  	}
              
  	public function insertAction() {
		$this->language->load('localisation/return_status');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_return_status = new \Stupycart\Common\Models\Admin\Localisation\ReturnStatus();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
      		$this->model_localisation_return_status->addReturnStatus($this->request->getPostE());
		  	
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
						
      		$this->response->redirect($this->url->link('localisation/return_status', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
	
    	$this->getForm();
  	}

  	public function updateAction() {
		$this->language->load('localisation/return_status');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_return_status = new \Stupycart\Common\Models\Admin\Localisation\ReturnStatus();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
	  		$this->model_localisation_return_status->editReturnStatus($this->request->getQueryE('return_status_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('localisation/return_status', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getForm();
  	}

  	public function deleteAction() {
		$this->language->load('localisation/return_status');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_return_status = new \Stupycart\Common\Models\Admin\Localisation\ReturnStatus();
		
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $return_status_id) {
				$this->model_localisation_return_status->deleteReturnStatus($return_status_id);
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
			
			$this->response->redirect($this->url->link('localisation/return_status', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
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
			'href'      => $this->url->link('localisation/return_status', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('localisation/return_status/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/return_status/delete', 'token=' . $this->session->get('token') . $url, 'SSL');	

		$this->data['return_statuses'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$return_status_total = $this->model_localisation_return_status->getTotalReturnStatuses();
	
		$results = $this->model_localisation_return_status->getReturnStatuses($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/return_status/update', 'token=' . $this->session->get('token') . '&return_status_id=' . $result['return_status_id'] . $url, 'SSL')
			);
						
			$this->data['return_statuses'][] = array(
				'return_status_id' => $result['return_status_id'],
				'name'          => $result['name'] . (($result['return_status_id'] == $this->config->get('config_return_status_id')) ? $this->language->get('text_default') : null),
				'selected'      => $this->request->hasPost('selected') && in_array($result['return_status_id'], $this->request->getPostE('selected')),
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
		
		$this->data['sort_name'] = $this->url->link('localisation/return_status', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $return_status_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/return_status', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/return_status_list');
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
			'href'      => $this->url->link('localisation/return_status', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('return_status_id')) {
			$this->data['action'] = $this->url->link('localisation/return_status/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/return_status/update', 'token=' . $this->session->get('token') . '&return_status_id=' . $this->request->getQueryE('return_status_id') . $url, 'SSL');
		}
			
		$this->data['cancel'] = $this->url->link('localisation/return_status', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('return_status')) {
			$this->data['return_status'] = $this->request->getPostE('return_status');
		} elseif ($this->request->hasQuery('return_status_id')) {
			$this->data['return_status'] = $this->model_localisation_return_status->getReturnStatusDescriptions($this->request->getQueryE('return_status_id'));
		} else {
			$this->data['return_status'] = array();
		}

		$this->view->pick('localisation/return_status_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);	
  	}
  	
	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/return_status')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->getPostE('return_status') as $language_id => $value) {
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
		if (!$this->user->hasPermission('modify', 'localisation/return_status')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->model_sale_return = new \Stupycart\Common\Models\Admin\Sale\ReturnModel();
		
		foreach ($this->request->getPostE('selected') as $return_status_id) {
    		if ($this->config->get('config_return_status_id') == $return_status_id) {
	  			$this->error['warning'] = $this->language->get('error_default');	
			}  
			
			$return_total = $this->model_sale_return->getTotalReturnsByReturnStatusId($return_status_id);
		
			if ($return_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_return'), $return_total);	
			}  
			
			$return_total = $this->model_sale_return->getTotalReturnHistoriesByReturnStatusId($return_status_id);
		
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
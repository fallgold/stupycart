<?php  

namespace Stupycart\Admin\Controllers\User;

class UserController extends \Stupycart\Admin\Controllers\ControllerBase {  
	private $error = array();
   
  	public function indexAction() {
    	$this->language->load('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));
	
		$this->model_user_user = new \Stupycart\Common\Models\Admin\User\User();
		
    	$this->getList();
  	}
   
  	public function insertAction() {
    	$this->language->load('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_user_user = new \Stupycart\Common\Models\Admin\User\User();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_user_user->addUser($this->request->getPostE());
			
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
						
			$this->response->redirect($this->url->link('user/user', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getForm();
  	}

  	public function updateAction() {
    	$this->language->load('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_user_user = new \Stupycart\Common\Models\Admin\User\User();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_user_user->editUser($this->request->getQueryE('user_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('user/user', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getForm();
  	}
 
  	public function deleteAction() { 
    	$this->language->load('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_user_user = new \Stupycart\Common\Models\Admin\User\User();
		
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {
      		foreach ($this->request->getPostE('selected') as $user_id) {
				$this->model_user_user->deleteUser($user_id);	
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
			
			$this->response->redirect($this->url->link('user/user', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getList();
  	}

  	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'username';
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
			'href'      => $this->url->link('user/user', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = $this->url->link('user/user/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('user/user/delete', 'token=' . $this->session->get('token') . $url, 'SSL');			
			
    	$this->data['users'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$user_total = $this->model_user_user->getTotalUsers();
		
		$results = $this->model_user_user->getUsers($data);
    	
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('user/user/update', 'token=' . $this->session->get('token') . '&user_id=' . $result['user_id'] . $url, 'SSL')
			);
					
      		$this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'username'   => $result['username'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => $this->request->hasPost('selected') && in_array($result['user_id'], $this->request->getPostE('selected')),
				'action'     => $action
			);
		}	
			
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
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
					
		$this->data['sort_username'] = $this->url->link('user/user', 'token=' . $this->session->get('token') . '&sort=username' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('user/user', 'token=' . $this->session->get('token') . '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('user/user', 'token=' . $this->session->get('token') . '&sort=date_added' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
				
		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('user/user', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
								
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('user/user_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
	
	protected function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
    	$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}

 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
	 	if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
	 	if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
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
			'href'      => $this->url->link('user/user', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('user_id')) {
			$this->data['action'] = $this->url->link('user/user/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('user/user/update', 'token=' . $this->session->get('token') . '&user_id=' . $this->request->getQueryE('user_id') . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('user/user', 'token=' . $this->session->get('token') . $url, 'SSL');

    	if ($this->request->hasQuery('user_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$user_info = $this->model_user_user->getUser($this->request->getQueryE('user_id'));
    	}

    	if ($this->request->hasPost('username')) {
      		$this->data['username'] = $this->request->getPostE('username');
    	} elseif (!empty($user_info)) {
			$this->data['username'] = $user_info['username'];
		} else {
      		$this->data['username'] = '';
    	}
  
  		if ($this->request->hasPost('password')) {
    		$this->data['password'] = $this->request->getPostE('password');
		} else {
			$this->data['password'] = '';
		}
		
  		if ($this->request->hasPost('confirm')) {
    		$this->data['confirm'] = $this->request->getPostE('confirm');
		} else {
			$this->data['confirm'] = '';
		}
  
    	if ($this->request->hasPost('firstname')) {
      		$this->data['firstname'] = $this->request->getPostE('firstname');
    	} elseif (!empty($user_info)) {
			$this->data['firstname'] = $user_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if ($this->request->hasPost('lastname')) {
      		$this->data['lastname'] = $this->request->getPostE('lastname');
    	} elseif (!empty($user_info)) {
			$this->data['lastname'] = $user_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
   		}
  
    	if ($this->request->hasPost('email')) {
      		$this->data['email'] = $this->request->getPostE('email');
    	} elseif (!empty($user_info)) {
			$this->data['email'] = $user_info['email'];
		} else {
      		$this->data['email'] = '';
    	}

    	if ($this->request->hasPost('user_group_id')) {
      		$this->data['user_group_id'] = $this->request->getPostE('user_group_id');
    	} elseif (!empty($user_info)) {
			$this->data['user_group_id'] = $user_info['user_group_id'];
		} else {
      		$this->data['user_group_id'] = '';
    	}
		
		$this->model_user_user_group = new \Stupycart\Common\Models\Admin\User\UserGroup();
		
    	$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();
 
     	if ($this->request->hasPost('status')) {
      		$this->data['status'] = $this->request->getPostE('status');
    	} elseif (!empty($user_info)) {
			$this->data['status'] = $user_info['status'];
		} else {
      		$this->data['status'] = 0;
    	}
		
		$this->view->pick('user/user_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);	
  	}
  	
  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	if ((utf8_strlen($this->request->getPostE('username')) < 3) || (utf8_strlen($this->request->getPostE('username')) > 20)) {
      		$this->error['username'] = $this->language->get('error_username');
    	}
		
		$user_info = $this->model_user_user->getUserByUsername($this->request->getPostE('username'));
		
		if (!$this->request->hasQuery('user_id')) {
			if ($user_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($user_info && ($this->request->getQueryE('user_id') != $user_info['user_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		
    	if ((utf8_strlen($this->request->getPostE('firstname')) < 1) || (utf8_strlen($this->request->getPostE('firstname')) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->getPostE('lastname')) < 1) || (utf8_strlen($this->request->getPostE('lastname')) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ($this->request->getPostE('password') || (!$this->request->hasQuery('user_id'))) {
      		if ((utf8_strlen($this->request->getPostE('password')) < 4) || (utf8_strlen($this->request->getPostE('password')) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->getPostE('password') != $this->request->getPostE('confirm')) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}
	
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

  	protected function validateDelete() { 
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	} 
	  	  
		foreach ($this->request->getPostE('selected') as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->error['warning'] = $this->language->get('error_account');
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
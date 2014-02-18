<?php

namespace Stupycart\Admin\Controllers\Common;

class ResetController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
	
	public function indexAction() {
		if ($this->user->isLogged()) {
			$this->response->redirect($this->url->link('common/home', '', 'SSL'), true);
		return;
		}
				
		if (!$this->config->get('config_password')) {
			$this->response->redirect($this->url->link('common/login', '', 'SSL'), true);
		return;
		}
						
		if ($this->request->hasQuery('code')) {
			$code = $this->request->getQueryE('code');
		} else {
			$code = '';
		}
		
		$this->model_user_user = new \Stupycart\Common\Models\Admin\User\User();
		
		$user_info = $this->model_user_user->getUserByCode($code);
		
		if ($user_info) {
			$this->language->load('common/reset');
			
			if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
				$this->model_user_user->editPassword($user_info['user_id'], $this->request->getPostE('password'));
	 
				$this->session->set('success', $this->language->get('text_success'));
		  
				$this->response->redirect($this->url->link('common/login', '', 'SSL'), true);
		return;
			}
			
			$this->data['breadcrumbs'] = array();
	
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),        	
				'separator' => false
			); 
			
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_reset'),
				'href'      => $this->url->link('common/reset', '', 'SSL'),       	
				'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
	
			$this->data['text_password'] = $this->language->get('text_password');
	
			$this->data['entry_password'] = $this->language->get('entry_password');
			$this->data['entry_confirm'] = $this->language->get('entry_confirm');
	
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
	
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
			
			$this->data['action'] = $this->url->link('common/reset', 'code=' . $code, 'SSL');
	 
			$this->data['cancel'] = $this->url->link('common/login', '', 'SSL');
			
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
			
			$this->view->pick('common/reset');
		$this->_commonAction();
									
			$this->view->setVars($this->data);						
		} else {
			$this->model_setting_setting->editSettingValue('config', 'config_password', '0');
			
			return $this->forward('common/login');
		}
	}

	protected function validate() {
    	if ((utf8_strlen($this->request->getPostE('password')) < 4) || (utf8_strlen($this->request->getPostE('password')) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->getPostE('confirm') != $this->request->getPostE('password')) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}  

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
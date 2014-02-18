<?php  

namespace Stupycart\Admin\Controllers\Common;

class LoginController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
	          
	public function indexAction() { 
    	$this->language->load('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && $this->request->hasQuery('token') && ($this->request->getQueryE('token') == $this->session->get('token'))) {
			$this->response->redirect($this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) { 
			$this->session->set('token', md5(mt_rand()));
		
			if ($this->request->hasPost('redirect')) {
				$this->response->redirect($this->request->getPostE('redirect') . '&token=' . $this->session->get('token'), true);
		return;
			} else {
				$this->response->redirect($this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
			}
		}
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');
		
		$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_login'] = $this->language->get('button_login');
		
		if (($this->session->has('token') && !$this->request->hasQuery('token')) || (($this->request->hasQuery('token') && ($this->session->has('token') && ($this->request->getQueryE('token') != $this->session->get('token')))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}
		
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
				
    	$this->data['action'] = $this->url->link('common/login', '', 'SSL');

		if ($this->request->hasPost('username')) {
			$this->data['username'] = $this->request->getPostE('username');
		} else {
			$this->data['username'] = '';
		}
		
		if ($this->request->hasPost('password')) {
			$this->data['password'] = $this->request->getPostE('password');
		} else {
			$this->data['password'] = '';
		}

		if ($this->request->hasQuery('route')) {
			$route = $this->request->getQueryE('route');
			
			unset($this->request->getQueryE('route'));
			
			if ($this->request->hasQuery('token')) {
				unset($this->request->getQueryE('token'));
			}
			
			$url = '';
						
			if ($this->request->getQueryE()) {
				$url .= http_build_query($this->request->getQueryE());
			}
			
			$this->data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$this->data['redirect'] = '';	
		}
		
		if ($this->config->get('config_password')) {
			$this->data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		} else {
			$this->data['forgotten'] = '';
		}
		
		$this->view->pick('common/login');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
		
	protected function validate() {
		if ($this->request->hasPost('username') && $this->request->hasPost('password') && !$this->user->login($this->request->getPostE('username'), $this->request->getPostE('password'))) {
			$this->error['warning'] = $this->language->get('error_login');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}  
?>
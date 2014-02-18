<?php 

namespace Stupycart\Frontend\Controllers\Affiliate;

class LoginController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();
	
	public function indexAction() {
		if ($this->affiliate->isLogged()) {  
      		$this->response->redirect($this->url->link('affiliate/account', '', 'SSL'), true);
		return;
    	}
	
    	$this->language->load('affiliate/login');

    	$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->model_affiliate_affiliate = new \Stupycart\Common\Models\Affiliate\Affiliate();
						
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->request->hasPost('email') && $this->request->hasPost('password') && $this->validate()) {
			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if ($this->request->hasPost('redirect') && (strpos($this->request->getPostE('redirect'), $this->config->get('config_url')) !== false || strpos($this->request->getPostE('redirect'), $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->getPostE('redirect')), true);
		return;
			} else {
				$this->response->redirect($this->url->link('affiliate/account', '', 'SSL'), true);
		return;
			} 
		}
		
      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	);
 
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('affiliate/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_login'),
			'href'      => $this->url->link('affiliate/login', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);
				
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_description'] = sprintf($this->language->get('text_description'), $this->config->get('config_name'), $this->config->get('config_name'), $this->config->get('config_commission') . '%');
		$this->data['text_new_affiliate'] = $this->language->get('text_new_affiliate');
    	$this->data['text_register_account'] = $this->language->get('text_register_account'); 	
		$this->data['text_returning_affiliate'] = $this->language->get('text_returning_affiliate');
		$this->data['text_i_am_returning_affiliate'] = $this->language->get('text_i_am_returning_affiliate');
    	$this->data['text_forgotten'] = $this->language->get('text_forgotten');

    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_login'] = $this->language->get('button_login');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['action'] = $this->url->link('affiliate/login', '', 'SSL');
		$this->data['register'] = $this->url->link('affiliate/register', '', 'SSL');
		$this->data['forgotten'] = $this->url->link('affiliate/forgotten', '', 'SSL');
    	
		if ($this->request->hasPost('redirect')) {
			$this->data['redirect'] = $this->request->getPostE('redirect');
		} elseif ($this->session->has('redirect')) {
      		$this->data['redirect'] = $this->session->get('redirect');
	  		
			$this->session->remove('redirect');		  	
    	} else {
			$this->data['redirect'] = '';
		}

		if ($this->session->has('success')) {
    		$this->data['success'] = $this->session->get('success');
    
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}

		if ($this->request->hasPost('email')) {
			$this->data['email'] = $this->request->getPostE('email');
		} else {
			$this->data['email'] = '';
		}

		if ($this->request->hasPost('password')) {
			$this->data['password'] = $this->request->getPostE('password');
		} else {
			$this->data['password'] = '';
		}
				
		$this->view->pick('affiliate/login');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);
  	}
  
  	protected function validate() {
    	if (!$this->affiliate->login($this->request->getPostE('email'), $this->request->getPostE('password'))) {
      		$this->error['warning'] = $this->language->get('error_login');
    	}

		$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByEmail($this->request->getPostE('email'));
		
    	if ($affiliate_info && !$affiliate_info['approved']) {
      		$this->error['warning'] = $this->language->get('error_approved');
    	}	
			
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}  	
  	}
}
?>
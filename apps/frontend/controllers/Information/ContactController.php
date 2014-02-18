<?php 

namespace Stupycart\Frontend\Controllers\Information;

class ContactController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array(); 
	    
  	public function indexAction() {
		$this->language->load('information/contact');

    	$this->document->setTitle($this->language->get('heading_title'));  
	 
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$mail = new \Libs\Opencart\Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->config->get('config_email'));
	  		$mail->setFrom($this->request->getPostE('email'));
	  		$mail->setSender($this->request->getPostE('name'));
	  		$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->getPostE('name')), ENT_QUOTES, 'UTF-8'));
	  		$mail->setText(strip_tags(html_entity_decode($this->request->getPostE('enquiry'), ENT_QUOTES, 'UTF-8')));
      		$mail->send();

	  		$this->response->redirect($this->url->link('information/contact/success'), true);
		return;
    	}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
        	'separator' => $this->language->get('text_separator')
      	);	
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_location'] = $this->language->get('text_location');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_telephone'] = $this->language->get('text_telephone');
    	$this->data['text_fax'] = $this->language->get('text_fax');

    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');

		if (isset($this->error['name'])) {
    		$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}		
		
		if (isset($this->error['enquiry'])) {
			$this->data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$this->data['error_enquiry'] = '';
		}		
		
 		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}	

    	$this->data['button_continue'] = $this->language->get('button_continue');
    
		$this->data['action'] = $this->url->link('information/contact');
		$this->data['store'] = $this->config->get('config_name');
    	$this->data['address'] = nl2br($this->config->get('config_address'));
    	$this->data['telephone'] = $this->config->get('config_telephone');
    	$this->data['fax'] = $this->config->get('config_fax');
    	
		if ($this->request->hasPost('name')) {
			$this->data['name'] = $this->request->getPostE('name');
		} else {
			$this->data['name'] = $this->customer->getFirstName();
		}

		if ($this->request->hasPost('email')) {
			$this->data['email'] = $this->request->getPostE('email');
		} else {
			$this->data['email'] = $this->customer->getEmail();
		}
		
		if ($this->request->hasPost('enquiry')) {
			$this->data['enquiry'] = $this->request->getPostE('enquiry');
		} else {
			$this->data['enquiry'] = '';
		}
		
		if ($this->request->hasPost('captcha')) {
			$this->data['captcha'] = $this->request->getPostE('captcha');
		} else {
			$this->data['captcha'] = '';
		}		

		$this->view->pick('information/contact');
		
		$this->_commonAction();
				
 		$this->view->setVars($this->data);		
  	}

  	public function successAction() {
		$this->language->load('information/contact');

		$this->document->setTitle($this->language->get('heading_title')); 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('information/contact'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');

		$this->view->pick('common/success');
		
		$this->_commonAction();
				
 		$this->view->setVars($this->data); 
	}
	
  	protected function validate() {
    	if ((utf8_strlen($this->request->getPostE('name')) < 3) || (utf8_strlen($this->request->getPostE('name')) > 32)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('email'))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((utf8_strlen($this->request->getPostE('enquiry')) < 10) || (utf8_strlen($this->request->getPostE('enquiry')) > 3000)) {
      		$this->error['enquiry'] = $this->language->get('error_enquiry');
    	}

    	if ((!$this->session->get('captcha')) || ($this->session->get('captcha') != $this->request->getPostE('captcha'))) {
      		$this->error['captcha'] = $this->language->get('error_captcha');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  	  
  	}

	public function captchaAction() {
		
		$captcha = new \Libs\Opencart\Captcha();
		
		$this->session->set('captcha', $captcha->getCode());
		
		$captcha->showImage();
	}	
}
?>

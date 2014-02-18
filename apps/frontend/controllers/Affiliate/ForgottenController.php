<?php

namespace Stupycart\Frontend\Controllers\Affiliate;

class ForgottenController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();

	public function indexAction() {
		if ($this->affiliate->isLogged()) {
			$this->response->redirect($this->url->link('affiliate/account', '', 'SSL'), true);
		return;
		}

		$this->language->load('affiliate/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_affiliate_affiliate = new \Stupycart\Common\Models\Affiliate\Affiliate();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->language->load('mail/forgotten');
			
			$password = substr(md5(mt_rand()), 0, 10);
			
			$this->model_affiliate_affiliate->editPassword($this->request->getPostE('email'), $password);
			
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			
			$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;

			$mail = new \Libs\Opencart\Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->request->getPostE('email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'), true);
		return;
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
        	'text'      => $this->language->get('text_forgotten'),
			'href'      => $this->url->link('affiliate/forgotten', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_your_email'] = $this->language->get('text_your_email');
		$this->data['text_email'] = $this->language->get('text_email');

		$this->data['entry_email'] = $this->language->get('entry_email');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['action'] = $this->url->link('affiliate/forgotten', '', 'SSL');
 
		$this->data['back'] = $this->url->link('affiliate/login', '', 'SSL');

		$this->view->pick('affiliate/forgotten');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);		
	}

	protected function validate() {
		if (!$this->request->hasPost('email')) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_affiliate_affiliate->getTotalAffiliatesByEmail($this->request->getPostE('email'))) {
			$this->error['warning'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
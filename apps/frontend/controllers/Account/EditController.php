<?php

namespace Stupycart\Frontend\Controllers\Account;

class EditController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();

	public function indexAction() {
		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->link('account/edit', '', 'SSL'));

			$this->response->redirect($this->url->link('account/login', '', 'SSL'), true);
		return;
		}

		$this->language->load('account/edit');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_account_customer = new \Stupycart\Common\Models\Account\Customer();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_account_customer->editCustomer($this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('account/account', '', 'SSL'), true);
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
			'href'      => $this->url->link('account/account', '', 'SSL'),        	
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_edit'),
			'href'      => $this->url->link('account/edit', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_your_details'] = $this->language->get('text_your_details');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}	
		
		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}	

		$this->data['action'] = $this->url->link('account/edit', '', 'SSL');

		if ($this->request->getServer('REQUEST_METHOD') != 'POST') {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		}

		if ($this->request->hasPost('firstname')) {
			$this->data['firstname'] = $this->request->getPostE('firstname');
		} elseif (isset($customer_info)) {
			$this->data['firstname'] = $customer_info['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if ($this->request->hasPost('lastname')) {
			$this->data['lastname'] = $this->request->getPostE('lastname');
		} elseif (isset($customer_info)) {
			$this->data['lastname'] = $customer_info['lastname'];
		} else {
			$this->data['lastname'] = '';
		}

		if ($this->request->hasPost('email')) {
			$this->data['email'] = $this->request->getPostE('email');
		} elseif (isset($customer_info)) {
			$this->data['email'] = $customer_info['email'];
		} else {
			$this->data['email'] = '';
		}

		if ($this->request->hasPost('telephone')) {
			$this->data['telephone'] = $this->request->getPostE('telephone');
		} elseif (isset($customer_info)) {
			$this->data['telephone'] = $customer_info['telephone'];
		} else {
			$this->data['telephone'] = '';
		}

		if ($this->request->hasPost('fax')) {
			$this->data['fax'] = $this->request->getPostE('fax');
		} elseif (isset($customer_info)) {
			$this->data['fax'] = $customer_info['fax'];
		} else {
			$this->data['fax'] = '';
		}

		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		$this->view->pick('account/edit');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);	
	}

	protected function validate() {
		if ((utf8_strlen($this->request->getPostE('firstname')) < 1) || (utf8_strlen($this->request->getPostE('firstname')) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen($this->request->getPostE('lastname')) < 1) || (utf8_strlen($this->request->getPostE('lastname')) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->getPostE('email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('email'))) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		if (($this->customer->getEmail() != $this->request->getPostE('email')) && $this->model_account_customer->getTotalCustomersByEmail($this->request->getPostE('email'))) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->getPostE('telephone')) < 3) || (utf8_strlen($this->request->getPostE('telephone')) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
<?php

namespace Stupycart\Frontend\Controllers\Affiliate;

class PaymentController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();

	public function indexAction() {
		if (!$this->affiliate->isLogged()) {
			$this->session->set('redirect', $this->url->link('affiliate/payment', '', 'SSL'));

			$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'), true);
		return;
		}

		$this->language->load('affiliate/payment');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_affiliate_affiliate = new \Stupycart\Common\Models\Affiliate\Affiliate();
		
		if ($this->request->getServer('REQUEST_METHOD') == 'POST') {
			$this->model_affiliate_affiliate->editPayment($this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('affiliate/account', '', 'SSL'), true);
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
        	'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('affiliate/payment', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_your_payment'] = $this->language->get('text_your_payment');
		$this->data['text_cheque'] = $this->language->get('text_cheque');
		$this->data['text_paypal'] = $this->language->get('text_paypal');
		$this->data['text_bank'] = $this->language->get('text_bank');
		
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_payment'] = $this->language->get('entry_payment');
		$this->data['entry_cheque'] = $this->language->get('entry_cheque');
		$this->data['entry_paypal'] = $this->language->get('entry_paypal');
		$this->data['entry_bank_name'] = $this->language->get('entry_bank_name');
		$this->data['entry_bank_branch_number'] = $this->language->get('entry_bank_branch_number');
		$this->data['entry_bank_swift_code'] = $this->language->get('entry_bank_swift_code');
		$this->data['entry_bank_account_name'] = $this->language->get('entry_bank_account_name');
		$this->data['entry_bank_account_number'] = $this->language->get('entry_bank_account_number');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['action'] = $this->url->link('affiliate/payment', '', 'SSL');

		if ($this->request->getServer('REQUEST_METHOD') != 'POST') {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
		}

		if ($this->request->hasPost('tax')) {
    		$this->data['tax'] = $this->request->getPostE('tax');
		} elseif (!empty($affiliate_info)) {
			$this->data['tax'] = $affiliate_info['tax'];		
		} else {
			$this->data['tax'] = '';
		}
		
		if ($this->request->hasPost('payment')) {
    		$this->data['payment'] = $this->request->getPostE('payment');
		} elseif (!empty($affiliate_info)) {
			$this->data['payment'] = $affiliate_info['payment'];			
		} else {
			$this->data['payment'] = 'cheque';
		}

		if ($this->request->hasPost('cheque')) {
    		$this->data['cheque'] = $this->request->getPostE('cheque');
		} elseif (!empty($affiliate_info)) {
			$this->data['cheque'] = $affiliate_info['cheque'];			
		} else {
			$this->data['cheque'] = '';
		}

		if ($this->request->hasPost('paypal')) {
    		$this->data['paypal'] = $this->request->getPostE('paypal');
		} elseif (!empty($affiliate_info)) {
			$this->data['paypal'] = $affiliate_info['paypal'];		
		} else {
			$this->data['paypal'] = '';
		}

		if ($this->request->hasPost('bank_name')) {
    		$this->data['bank_name'] = $this->request->getPostE('bank_name');
		} elseif (!empty($affiliate_info)) {
			$this->data['bank_name'] = $affiliate_info['bank_name'];			
		} else {
			$this->data['bank_name'] = '';
		}

		if ($this->request->hasPost('bank_branch_number')) {
    		$this->data['bank_branch_number'] = $this->request->getPostE('bank_branch_number');
		} elseif (!empty($affiliate_info)) {
			$this->data['bank_branch_number'] = $affiliate_info['bank_branch_number'];		
		} else {
			$this->data['bank_branch_number'] = '';
		}

		if ($this->request->hasPost('bank_swift_code')) {
    		$this->data['bank_swift_code'] = $this->request->getPostE('bank_swift_code');
		} elseif (!empty($affiliate_info)) {
			$this->data['bank_swift_code'] = $affiliate_info['bank_swift_code'];			
		} else {
			$this->data['bank_swift_code'] = '';
		}

		if ($this->request->hasPost('bank_account_name')) {
    		$this->data['bank_account_name'] = $this->request->getPostE('bank_account_name');
		} elseif (!empty($affiliate_info)) {
			$this->data['bank_account_name'] = $affiliate_info['bank_account_name'];		
		} else {
			$this->data['bank_account_name'] = '';
		}
		
		if ($this->request->hasPost('bank_account_number')) {
    		$this->data['bank_account_number'] = $this->request->getPostE('bank_account_number');
		} elseif (!empty($affiliate_info)) {
			$this->data['bank_account_number'] = $affiliate_info['bank_account_number'];			
		} else {
			$this->data['bank_account_number'] = '';
		}
		
		$this->data['back'] = $this->url->link('affiliate/account', '', 'SSL');

		$this->view->pick('affiliate/payment');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);		
	}
}
?>
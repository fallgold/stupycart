<?php  

namespace Stupycart\Frontend\Controllers\Module;

class AffiliateController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->language->load('module/affiliate');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_register'] = $this->language->get('text_register');
    	$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');	
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_password'] = $this->language->get('text_password');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_tracking'] = $this->language->get('text_tracking');
		$this->data['text_transaction'] = $this->language->get('text_transaction');
		
		$this->data['logged'] = $this->affiliate->isLogged();
		$this->data['register'] = $this->url->link('affiliate/register', '', 'SSL');
    	$this->data['login'] = $this->url->link('affiliate/login', '', 'SSL');
		$this->data['logout'] = $this->url->link('affiliate/logout', '', 'SSL');
		$this->data['forgotten'] = $this->url->link('affiliate/forgotten', '', 'SSL');
		$this->data['account'] = $this->url->link('affiliate/account', '', 'SSL');
		$this->data['edit'] = $this->url->link('affiliate/edit', '', 'SSL');
		$this->data['password'] = $this->url->link('affiliate/password', '', 'SSL');
		$this->data['payment'] = $this->url->link('affiliate/payment', '', 'SSL');
		$this->data['tracking'] = $this->url->link('affiliate/tracking', '', 'SSL');
		$this->data['transaction'] = $this->url->link('affiliate/transaction', '', 'SSL');
		
		$this->view->pick('module/affiliate');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
}
?>
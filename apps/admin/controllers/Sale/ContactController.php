<?php 

namespace Stupycart\Admin\Controllers\Sale;

class ContactController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
	 
	public function indexAction() {
		$this->language->load('sale/contact');
 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_customer_all'] = $this->language->get('text_customer_all');	
		$this->data['text_customer'] = $this->language->get('text_customer');	
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_affiliate_all'] = $this->language->get('text_affiliate_all');	
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');	
		$this->data['text_product'] = $this->language->get('text_product');	

		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_message'] = $this->language->get('entry_message');
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['token'] = $this->session->get('token');

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/contact', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
    	$this->data['cancel'] = $this->url->link('sale/contact', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
				
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(0);
				
		$this->view->pick('sale/contact');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	public function sendAction() {
		$this->language->load('sale/contact');
		
		$json = array();
		
		if ($this->request->getServer('REQUEST_METHOD') == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/contact')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}
					
			if (!$this->request->getPostE('subject')) {
				$json['error']['subject'] = $this->language->get('error_subject');
			}
	
			if (!$this->request->getPostE('message')) {
				$json['error']['message'] = $this->language->get('error_message');
			}
			
			if (!$json) {
				$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
			
				$store_info = $this->model_setting_store->getStore($this->request->getPostE('store_id'));			
				
				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}
	
				$this->model_sale_customer = new \Stupycart\Common\Models\Admin\Sale\Customer();
				
				$this->model_sale_customer_group = new \Stupycart\Common\Models\Admin\Sale\CustomerGroup();
				
				$this->model_sale_affiliate = new \Stupycart\Common\Models\Admin\Sale\Affiliate();
	
				$this->model_sale_order = new \Stupycart\Common\Models\Admin\Sale\Order();
	
				if ($this->request->hasQuery('page')) {
					$page = $this->request->getQueryE('page');
				} else {
					$page = 1;
				}
								
				$email_total = 0;
							
				$emails = array();
				
				switch ($this->request->getPostE('to')) {
					case 'newsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);
						
						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);
							
						$results = $this->model_sale_customer->getCustomers($customer_data);
					
						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'customer_all':
						$customer_data = array(
							'start'  => ($page - 1) * 10,
							'limit'  => 10
						);
									
						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);
										
						$results = $this->model_sale_customer->getCustomers($customer_data);
				
						foreach ($results as $result) {
							$emails[] = $result['email'];
						}						
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->getPostE('customer_group_id'),
							'start'                    => ($page - 1) * 10,
							'limit'                    => 10
						);
						
						$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);
										
						$results = $this->model_sale_customer->getCustomers($customer_data);
				
						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}						
						break;
					case 'customer':
						if (!(!$this->request->getPostE('customer'))) {					
							foreach ($this->request->getPostE('customer') as $customer_id) {
								$customer_info = $this->model_sale_customer->getCustomer($customer_id);
								
								if ($customer_info) {
									$emails[] = $customer_info['email'];
								}
							}
						}
						break;	
					case 'affiliate_all':
						$affiliate_data = array(
							'start'  => ($page - 1) * 10,
							'limit'  => 10
						);
						
						$email_total = $this->model_sale_affiliate->getTotalAffiliates($affiliate_data);		
						
						$results = $this->model_sale_affiliate->getAffiliates($affiliate_data);
				
						foreach ($results as $result) {
							$emails[] = $result['email'];
						}						
						break;	
					case 'affiliate':
						if (!(!$this->request->getPostE('affiliate'))) {					
							foreach ($this->request->getPostE('affiliate') as $affiliate_id) {
								$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
								
								if ($affiliate_info) {
									$emails[] = $affiliate_info['email'];
								}
							}
						}
						break;											
					case 'product':
						if ($this->request->hasPost('product')) {
							$email_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->getPostE('product'));	
							
							$results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->getPostE('product'), ($page - 1) * 10, 10);
													
							foreach ($results as $result) {
								$emails[] = $result['email'];
							}
						}
						break;												
				}
				
				if ($emails) {
					$start = ($page - 1) * 10;
					$end = $start + 10;
					
					if ($end < $email_total) {
						$json['success'] = sprintf($this->language->get('text_sent'), $start, $email_total);
					} else { 
						$json['success'] = $this->language->get('text_success');
					}				
						
					if ($end < $email_total) {
						$json['next'] = str_replace('&amp;', '&', $this->url->link('sale/contact/send', 'token=' . $this->session->get('token') . '&page=' . ($page + 1), 'SSL'));
					} else {
						$json['next'] = '';
					}
										
					$message  = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>' . $this->request->getPostE('subject') . '</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($this->request->getPostE('message'), ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";
					
					foreach ($emails as $email) {
						$mail = new \Libs\Opencart\Mail();	
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->hostname = $this->config->get('config_smtp_host');
						$mail->username = $this->config->get('config_smtp_username');
						$mail->password = $this->config->get('config_smtp_password');
						$mail->port = $this->config->get('config_smtp_port');
						$mail->timeout = $this->config->get('config_smtp_timeout');				
						$mail->setTo($email);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($store_name);
						$mail->setSubject(html_entity_decode($this->request->getPostE('subject'), ENT_QUOTES, 'UTF-8'));					
						$mail->setHtml($message);
						$mail->send();
					}
				}
			}
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;	
	}
}
?>
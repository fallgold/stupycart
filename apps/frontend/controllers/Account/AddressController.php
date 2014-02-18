<?php 

namespace Stupycart\Frontend\Controllers\Account;

class AddressController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();
	  
  	public function indexAction() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->link('account/address', '', 'SSL'));

	  		$this->response->redirect($this->url->link('account/login', '', 'SSL'), true);
		return; 
    	}
	
    	$this->language->load('account/address');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
		
		$this->getList();
  	}

  	public function insertAction() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->link('account/address', '', 'SSL'));

	  		$this->response->redirect($this->url->link('account/login', '', 'SSL'), true);
		return; 
    	} 

    	$this->language->load('account/address');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
			
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_account_address->addAddress($this->request->getPostE());
			
      		$this->session->set('success', $this->language->get('text_insert'));

	  		$this->response->redirect($this->url->link('account/address', '', 'SSL'), true);
		return;
    	} 
	  	
		$this->getForm();
  	}

  	public function updateAction() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->link('account/address', '', 'SSL'));

	  		$this->response->redirect($this->url->link('account/login', '', 'SSL'), true);
		return; 
    	} 
		
    	$this->language->load('account/address');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
       		$this->model_account_address->editAddress($this->request->getQueryE('address_id'), $this->request->getPostE());
	  		
			// Default Shipping Address
			if ($this->session->has('shipping_address_id') && ($this->request->getQueryE('address_id') == $this->session->get('shipping_address_id'))) {
				$this->session->set('shipping_country_id', $this->request->getPostE('country_id'));
				$this->session->set('shipping_zone_id', $this->request->getPostE('zone_id'));
				$this->session->set('shipping_postcode', $this->request->getPostE('postcode'));
				
				$this->session->remove('shipping_method');	
				$this->session->remove('shipping_methods');
			}
			
			// Default Payment Address
			if ($this->session->has('payment_address_id') && ($this->request->getQueryE('address_id') == $this->session->get('payment_address_id'))) {
				$this->session->set('payment_country_id', $this->request->getPostE('country_id'));
				$this->session->set('payment_zone_id', $this->request->getPostE('zone_id'));
	  			
				$this->session->remove('payment_method');
				$this->session->remove('payment_methods');
			}
			
			$this->session->set('success', $this->language->get('text_update'));
	  
	  		$this->response->redirect($this->url->link('account/address', '', 'SSL'), true);
		return;
    	} 
	  	
		$this->getForm();
  	}

  	public function deleteAction() {
    	if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->link('account/address', '', 'SSL'));

	  		$this->response->redirect($this->url->link('account/login', '', 'SSL'), true);
		return; 
    	} 
			
    	$this->language->load('account/address');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
		
    	if ($this->request->hasQuery('address_id') && $this->validateDelete()) {
			$this->model_account_address->deleteAddress($this->request->getQueryE('address_id'));	
			
			// Default Shipping Address
			if ($this->session->has('shipping_address_id') && ($this->request->getQueryE('address_id') == $this->session->get('shipping_address_id'))) {
				$this->session->remove('shipping_address_id');
				$this->session->remove('shipping_country_id');
				$this->session->remove('shipping_zone_id');
				$this->session->remove('shipping_postcode');				
				$this->session->remove('shipping_method');
				$this->session->remove('shipping_methods');
			}
			
			// Default Payment Address
			if ($this->session->has('payment_address_id') && ($this->request->getQueryE('address_id') == $this->session->get('payment_address_id'))) {
				$this->session->remove('payment_address_id');
				$this->session->remove('payment_country_id');
				$this->session->remove('payment_zone_id');				
				$this->session->remove('payment_method');
				$this->session->remove('payment_methods');
			}
			
			$this->session->set('success', $this->language->get('text_delete'));
	  
	  		$this->response->redirect($this->url->link('account/address', '', 'SSL'), true);
		return;
    	}
	
		$this->getList();	
  	}

  	protected function getList() {
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
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/address', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_address_book'] = $this->language->get('text_address_book');
   
    	$this->data['button_new_address'] = $this->language->get('button_new_address');
    	$this->data['button_edit'] = $this->language->get('button_edit');
    	$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_back'] = $this->language->get('button_back');

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
		
    	$this->data['addresses'] = array();
		
		$results = $this->model_account_address->getAddresses();

    	foreach ($results as $result) {
			if ($result['address_format']) {
      			$format = $result['address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
		
    		$find = array(
	  			'{firstname}',
	  			'{lastname}',
	  			'{company}',
      			'{address_1}',
      			'{address_2}',
     			'{city}',
      			'{postcode}',
      			'{zone}',
				'{zone_code}',
      			'{country}'
			);
	
			$replace = array(
	  			'firstname' => $result['firstname'],
	  			'lastname'  => $result['lastname'],
	  			'company'   => $result['company'],
      			'address_1' => $result['address_1'],
      			'address_2' => $result['address_2'],
      			'city'      => $result['city'],
      			'postcode'  => $result['postcode'],
      			'zone'      => $result['zone'],
				'zone_code' => $result['zone_code'],
      			'country'   => $result['country']  
			);

      		$this->data['addresses'][] = array(
        		'address_id' => $result['address_id'],
        		'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
        		'update'     => $this->url->link('account/address/update', 'address_id=' . $result['address_id'], 'SSL'),
				'delete'     => $this->url->link('account/address/delete', 'address_id=' . $result['address_id'], 'SSL')
      		);
    	}

    	$this->data['insert'] = $this->url->link('account/address/insert', '', 'SSL');
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');
				
		$this->view->pick('account/address_list');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);		
  	}

  	protected function getForm() {
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
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/address', '', 'SSL'),        	
        	'separator' => $this->language->get('text_separator')
      	);
		
		if (!$this->request->hasQuery('address_id')) {
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_edit_address'),
				'href'      => $this->url->link('account/address/insert', '', 'SSL'),       		
        		'separator' => $this->language->get('text_separator')
      		);
		} else {
      		$this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_edit_address'),
				'href'      => $this->url->link('account/address/update', 'address_id=' . $this->request->getQueryE('address_id'), 'SSL'),       		
        		'separator' => $this->language->get('text_separator')
      		);
		}
						
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_edit_address'] = $this->language->get('text_edit_address');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');		
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
    	$this->data['entry_default'] = $this->language->get('entry_default');

    	$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_back'] = $this->language->get('button_back');

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
		
  		if (isset($this->error['company_id'])) {
			$this->data['error_company_id'] = $this->error['company_id'];
		} else {
			$this->data['error_company_id'] = '';
		}
		
  		if (isset($this->error['tax_id'])) {
			$this->data['error_tax_id'] = $this->error['tax_id'];
		} else {
			$this->data['error_tax_id'] = '';
		}
										
		if (isset($this->error['address_1'])) {
    		$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
		
		if (isset($this->error['city'])) {
    		$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
    		$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
		
		if (!$this->request->hasQuery('address_id')) {
    		$this->data['action'] = $this->url->link('account/address/insert', '', 'SSL');
		} else {
    		$this->data['action'] = $this->url->link('account/address/update', 'address_id=' . $this->request->getQueryE('address_id'), 'SSL');
		}
		
    	if ($this->request->hasQuery('address_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$address_info = $this->model_account_address->getAddress($this->request->getQueryE('address_id'));
		}
	
    	if ($this->request->hasPost('firstname')) {
      		$this->data['firstname'] = $this->request->getPostE('firstname');
    	} elseif (!empty($address_info)) {
      		$this->data['firstname'] = $address_info['firstname'];
    	} else {
			$this->data['firstname'] = '';
		}

    	if ($this->request->hasPost('lastname')) {
      		$this->data['lastname'] = $this->request->getPostE('lastname');
    	} elseif (!empty($address_info)) {
      		$this->data['lastname'] = $address_info['lastname'];
    	} else {
			$this->data['lastname'] = '';
		}

    	if ($this->request->hasPost('company')) {
      		$this->data['company'] = $this->request->getPostE('company');
    	} elseif (!empty($address_info)) {
			$this->data['company'] = $address_info['company'];
		} else {
      		$this->data['company'] = '';
    	}
		
		if ($this->request->hasPost('company_id')) {
    		$this->data['company_id'] = $this->request->getPostE('company_id');
    	} elseif (!empty($address_info)) {
			$this->data['company_id'] = $address_info['company_id'];			
		} else {
			$this->data['company_id'] = '';
		}
		
		if ($this->request->hasPost('tax_id')) {
    		$this->data['tax_id'] = $this->request->getPostE('tax_id');
    	} elseif (!empty($address_info)) {
			$this->data['tax_id'] = $address_info['tax_id'];			
		} else {
			$this->data['tax_id'] = '';
		}
		
		$this->model_account_customer_group = new \Stupycart\Common\Models\Account\CustomerGroup();
		
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());
		
		if ($customer_group_info) {
			$this->data['company_id_display'] = $customer_group_info['company_id_display'];
		} else {
			$this->data['company_id_display'] = '';
		}
		
		if ($customer_group_info) {
			$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
		} else {
			$this->data['tax_id_display'] = '';
		}
								
    	if ($this->request->hasPost('address_1')) {
      		$this->data['address_1'] = $this->request->getPostE('address_1');
    	} elseif (!empty($address_info)) {
			$this->data['address_1'] = $address_info['address_1'];
		} else {
      		$this->data['address_1'] = '';
    	}

    	if ($this->request->hasPost('address_2')) {
      		$this->data['address_2'] = $this->request->getPostE('address_2');
    	} elseif (!empty($address_info)) {
			$this->data['address_2'] = $address_info['address_2'];
		} else {
      		$this->data['address_2'] = '';
    	}	

    	if ($this->request->hasPost('postcode')) {
      		$this->data['postcode'] = $this->request->getPostE('postcode');
    	} elseif (!empty($address_info)) {
			$this->data['postcode'] = $address_info['postcode'];			
		} else {
      		$this->data['postcode'] = '';
    	}

    	if ($this->request->hasPost('city')) {
      		$this->data['city'] = $this->request->getPostE('city');
    	} elseif (!empty($address_info)) {
			$this->data['city'] = $address_info['city'];
		} else {
      		$this->data['city'] = '';
    	}

    	if ($this->request->hasPost('country_id')) {
      		$this->data['country_id'] = $this->request->getPostE('country_id');
    	}  elseif (!empty($address_info)) {
      		$this->data['country_id'] = $address_info['country_id'];			
    	} else {
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if ($this->request->hasPost('zone_id')) {
      		$this->data['zone_id'] = $this->request->getPostE('zone_id');
    	}  elseif (!empty($address_info)) {
      		$this->data['zone_id'] = $address_info['zone_id'];
    	} else {
      		$this->data['zone_id'] = '';
    	}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();

    	if ($this->request->hasPost('default')) {
      		$this->data['default'] = $this->request->getPostE('default');
    	} elseif ($this->request->hasQuery('address_id')) {
      		$this->data['default'] = $this->customer->getAddressId() == $this->request->getQueryE('address_id');
    	} else {
			$this->data['default'] = false;
		}

    	$this->data['back'] = $this->url->link('account/address', '', 'SSL');
		
		$this->view->pick('account/address_form');
		
		$this->_commonAction();
						
		$this->view->setVars($this->data);	
  	}
	
  	protected function validateForm() {
    	if ((utf8_strlen($this->request->getPostE('firstname')) < 1) || (utf8_strlen($this->request->getPostE('firstname')) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->getPostE('lastname')) < 1) || (utf8_strlen($this->request->getPostE('lastname')) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((utf8_strlen($this->request->getPostE('address_1')) < 3) || (utf8_strlen($this->request->getPostE('address_1')) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((utf8_strlen($this->request->getPostE('city')) < 2) || (utf8_strlen($this->request->getPostE('city')) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
		$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('country_id'));
		
		if ($country_info) {
			if ($country_info['postcode_required'] && (utf8_strlen($this->request->getPostE('postcode')) < 2) || (utf8_strlen($this->request->getPostE('postcode')) > 10)) {
				$this->error['postcode'] = $this->language->get('error_postcode');
			}
			
			// VAT Validation
			require_once(_ROOT_. '/libs/helper/vat.php');
			
			if ($this->config->get('config_vat') && !(!$this->request->getPostE('tax_id')) && (vat_validation($country_info['iso_code_2'], $this->request->getPostE('tax_id')) == 'invalid')) {
				$this->error['tax_id'] = $this->language->get('error_vat');
			}		
		}
		
    	if ($this->request->getPostE('country_id') == '') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if (!$this->request->hasPost('zone_id') || $this->request->getPostE('zone_id') == '') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}
		
    	if (!$this->error) {
      		return true;
		} else {
      		return false;
    	}
  	}

  	protected function validateDelete() {
    	if ($this->model_account_address->getTotalAddresses() == 1) {
      		$this->error['warning'] = $this->language->get('error_delete');
    	}

    	if ($this->customer->getAddressId() == $this->request->getQueryE('address_id')) {
      		$this->error['warning'] = $this->language->get('error_default');
    	}

    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}
	
	public function countryAction() {
		$json = array();
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();

    	$country_info = $this->model_localisation_country->getCountry($this->request->getQueryE('country_id'));
		
		if ($country_info) {
			$this->model_localisation_zone = new \Stupycart\Common\Models\Localisation\Zone();

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->getQueryE('country_id')),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setContent(json_encode($json));
		return $this->response;
	}
}
?>
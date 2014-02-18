<?php

namespace Stupycart\Frontend\Controllers\Affiliate;

class EditController extends \Stupycart\Frontend\Controllers\ControllerBase {
	private $error = array();

	public function indexAction() {
		if (!$this->affiliate->isLogged()) {
			$this->session->set('redirect', $this->url->link('affiliate/edit', '', 'SSL'));

			$this->response->redirect($this->url->link('affiliate/login', '', 'SSL'), true);
		return;
		}

		$this->language->load('affiliate/edit');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_affiliate_affiliate = new \Stupycart\Common\Models\Affiliate\Affiliate();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_affiliate_affiliate->editAffiliate($this->request->getPostE());
			
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
        	'text'      => $this->language->get('text_edit'),
			'href'      => $this->url->link('affiliate/edit', '', 'SSL'),       	
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_your_details'] = $this->language->get('text_your_details');
    	$this->data['text_your_address'] = $this->language->get('text_your_address');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_website'] = $this->language->get('entry_website');
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');

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
		
		$this->data['action'] = $this->url->link('affiliate/edit', '', 'SSL');

		if ($this->request->getServer('REQUEST_METHOD') != 'POST') {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
		}

		if ($this->request->hasPost('firstname')) {
			$this->data['firstname'] = $this->request->getPostE('firstname');
		} elseif (!empty($affiliate_info)) {
			$this->data['firstname'] = $affiliate_info['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if ($this->request->hasPost('lastname')) {
			$this->data['lastname'] = $this->request->getPostE('lastname');
		} elseif (!empty($affiliate_info)) {
			$this->data['lastname'] = $affiliate_info['lastname'];
		} else {
			$this->data['lastname'] = '';
		}

		if ($this->request->hasPost('email')) {
			$this->data['email'] = $this->request->getPostE('email');
		} elseif (!empty($affiliate_info)) {
			$this->data['email'] = $affiliate_info['email'];
		} else {
			$this->data['email'] = '';
		}

		if ($this->request->hasPost('telephone')) {
			$this->data['telephone'] = $this->request->getPostE('telephone');
		} elseif (!empty($affiliate_info)) {
			$this->data['telephone'] = $affiliate_info['telephone'];
		} else {
			$this->data['telephone'] = '';
		}

		if ($this->request->hasPost('fax')) {
			$this->data['fax'] = $this->request->getPostE('fax');
		} elseif (!empty($affiliate_info)) {
			$this->data['fax'] = $affiliate_info['fax'];
		} else {
			$this->data['fax'] = '';
		}
		
		if ($this->request->hasPost('company')) {
    		$this->data['company'] = $this->request->getPostE('company');
		} elseif (!empty($affiliate_info)) {
			$this->data['company'] = $affiliate_info['company'];		
		} else {
			$this->data['company'] = '';
		}

		if ($this->request->hasPost('website')) {
    		$this->data['website'] = $this->request->getPostE('website');
		} elseif (!empty($affiliate_info)) {
			$this->data['website'] = $affiliate_info['website'];		
		} else {
			$this->data['website'] = '';
		}
				
		if ($this->request->hasPost('address_1')) {
    		$this->data['address_1'] = $this->request->getPostE('address_1');
		} elseif (!empty($affiliate_info)) {
			$this->data['address_1'] = $affiliate_info['address_1'];		
		} else {
			$this->data['address_1'] = '';
		}

		if ($this->request->hasPost('address_2')) {
    		$this->data['address_2'] = $this->request->getPostE('address_2');
		} elseif (!empty($affiliate_info)) {
			$this->data['address_2'] = $affiliate_info['address_2'];		
		} else {
			$this->data['address_2'] = '';
		}

		if ($this->request->hasPost('postcode')) {
    		$this->data['postcode'] = $this->request->getPostE('postcode');
		} elseif (!empty($affiliate_info)) {
			$this->data['postcode'] = $affiliate_info['postcode'];		
		} else {
			$this->data['postcode'] = '';
		}
		
		if ($this->request->hasPost('city')) {
    		$this->data['city'] = $this->request->getPostE('city');
		} elseif (!empty($affiliate_info)) {
			$this->data['city'] = $affiliate_info['city'];		
		} else {
			$this->data['city'] = '';
		}

    	if ($this->request->hasPost('country_id')) {
      		$this->data['country_id'] = $this->request->getPostE('country_id');
		} elseif (!empty($affiliate_info)) {
			$this->data['country_id'] = $affiliate_info['country_id'];			
		} else {	
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if ($this->request->hasPost('zone_id')) {
      		$this->data['zone_id'] = $this->request->getPostE('zone_id'); 	
		} elseif (!empty($affiliate_info)) {
			$this->data['zone_id'] = $affiliate_info['zone_id'];		
		} else {
      		$this->data['zone_id'] = '';
    	}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->data['back'] = $this->url->link('affiliate/account', '', 'SSL');

		$this->view->pick('affiliate/edit');
		
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
		
		if (($this->affiliate->getEmail() != $this->request->getPostE('email')) && $this->model_affiliate_affiliate->getTotalAffiliatesByEmail($this->request->getPostE('email'))) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->getPostE('telephone')) < 3) || (utf8_strlen($this->request->getPostE('telephone')) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
    	if ((utf8_strlen($this->request->getPostE('address_1')) < 3) || (utf8_strlen($this->request->getPostE('address_1')) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((utf8_strlen($this->request->getPostE('city')) < 2) || (utf8_strlen($this->request->getPostE('city')) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
		$this->model_localisation_country = new \Stupycart\Common\Models\Localisation\Country();
		
		$country_info = $this->model_localisation_country->getCountry($this->request->getPostE('country_id'));
		
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->getPostE('postcode')) < 2) || (utf8_strlen($this->request->getPostE('postcode')) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
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
<?php

namespace Stupycart\Admin\Controllers\Shipping;

class WeightController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
	
	public function indexAction() {  
		$this->language->load('shipping/weight');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
				 
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('weight', $this->request->getPostE());	

			$this->session->set('success', $this->language->get('text_success'));
									
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/weight', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/weight', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL'); 

		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();
		
		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->getPostE('weight_' . $geo_zone['geo_zone_id') . '_rate'])) {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->getPostE('weight_' . $geo_zone['geo_zone_id') . '_rate'];
			} else {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->config->get('weight_' . $geo_zone['geo_zone_id'] . '_rate');
			}		
			
			if (isset($this->request->getPostE('weight_' . $geo_zone['geo_zone_id') . '_status'])) {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->getPostE('weight_' . $geo_zone['geo_zone_id') . '_status'];
			} else {
				$this->data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('weight_' . $geo_zone['geo_zone_id'] . '_status');
			}		
		}
		
		$this->data['geo_zones'] = $geo_zones;

		if ($this->request->hasPost('weight_tax_class_id')) {
			$this->data['weight_tax_class_id'] = $this->request->getPostE('weight_tax_class_id');
		} else {
			$this->data['weight_tax_class_id'] = $this->config->get('weight_tax_class_id');
		}
		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();
				
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		if ($this->request->hasPost('weight_status')) {
			$this->data['weight_status'] = $this->request->getPostE('weight_status');
		} else {
			$this->data['weight_status'] = $this->config->get('weight_status');
		}
		
		if ($this->request->hasPost('weight_sort_order')) {
			$this->data['weight_sort_order'] = $this->request->getPostE('weight_sort_order');
		} else {
			$this->data['weight_sort_order'] = $this->config->get('weight_sort_order');
		}	

		$this->view->pick('shipping/weight');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
		
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/weight')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
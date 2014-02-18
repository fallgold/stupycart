<?php

namespace Stupycart\Admin\Controllers\Shipping;

class AusPostController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	
	public function indexAction() {   
		$this->language->load('shipping/auspost');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('auspost', $this->request->getPostE());             
			
			$this->session->set('success', $this->language->get('text_success'));
			
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_standard'] = $this->language->get('entry_standard');
		$this->data['entry_express'] = $this->language->get('entry_express');
		$this->data['entry_display_time'] = $this->language->get('entry_display_time');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');         
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
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
			'href'      => $this->url->link('shipping/auspost', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/auspost', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL');
		
		if ($this->request->hasPost('auspost_postcode')) {
			$this->data['auspost_postcode'] = $this->request->getPostE('auspost_postcode');
		} else {
			$this->data['auspost_postcode'] = $this->config->get('auspost_postcode');
		}
				
		if ($this->request->hasPost('auspost_standard')) {
			$this->data['auspost_standard'] = $this->request->getPostE('auspost_standard');
		} else {
			$this->data['auspost_standard'] = $this->config->get('auspost_standard');
		}
		
		if ($this->request->hasPost('auspost_express')) {
			$this->data['auspost_express'] = $this->request->getPostE('auspost_express');
		} else {
			$this->data['auspost_express'] = $this->config->get('auspost_express');
		}
		
		if ($this->request->hasPost('auspost_display_time')) {
			$this->data['auspost_display_time'] = $this->request->getPostE('auspost_display_time');
		} else {
			$this->data['auspost_display_time'] = $this->config->get('auspost_display_time');
		}
		
		if ($this->request->hasPost('auspost_weight_class_id')) {
			$this->data['auspost_weight_class_id'] = $this->request->getPostE('auspost_weight_class_id');
		} else {
			$this->data['auspost_weight_class_id'] = $this->config->get('auspost_weight_class_id');
		}
		
		$this->model_localisation_weight_class = new \Stupycart\Common\Models\Admin\Localisation\WeightClass();
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
						
		if ($this->request->hasPost('auspost_tax_class_id')) {
			$this->data['auspost_tax_class_id'] = $this->request->getPostE('auspost_tax_class_id');
		} else {
			$this->data['auspost_tax_class_id'] = $this->config->get('auspost_tax_class_id');
		}
		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		if ($this->request->hasPost('auspost_geo_zone_id')) {
			$this->data['auspost_geo_zone_id'] = $this->request->getPostE('auspost_geo_zone_id');
		} else {
			$this->data['auspost_geo_zone_id'] = $this->config->get('auspost_geo_zone_id');
		}
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('auspost_status')) {
			$this->data['auspost_status'] = $this->request->getPostE('auspost_status');
		} else {
			$this->data['auspost_status'] = $this->config->get('auspost_status');
		}
		
		if ($this->request->hasPost('auspost_sort_order')) {
			$this->data['auspost_sort_order'] = $this->request->getPostE('auspost_sort_order');
		} else {
			$this->data['auspost_sort_order'] = $this->config->get('auspost_sort_order');
		}                               
		
		$this->view->pick('shipping/auspost');
		$this->_commonAction();
		
		$this->response->setContent($this->render(TRUE), $this->config->get('config_compression'));
		return $this->response;
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/auspost')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!preg_match('/^[0-9]{4}$/', $this->request->getPostE('auspost_postcode'))){
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
	
		if (!$this->error) {
			return true;
		} else {
			return false;
		}       
	}
}
?>
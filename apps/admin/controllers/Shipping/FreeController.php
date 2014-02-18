<?php

namespace Stupycart\Admin\Controllers\Shipping;

class FreeController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	
	public function indexAction() {   
		$this->language->load('shipping/free');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
				
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('free', $this->request->getPostE());		
					
			$this->session->set('success', $this->language->get('text_success'));
						
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_total'] = $this->language->get('entry_total');
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
			'href'      => $this->url->link('shipping/free', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/free', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL');
	
		if ($this->request->hasPost('free_total')) {
			$this->data['free_total'] = $this->request->getPostE('free_total');
		} else {
			$this->data['free_total'] = $this->config->get('free_total');
		}

		if ($this->request->hasPost('free_geo_zone_id')) {
			$this->data['free_geo_zone_id'] = $this->request->getPostE('free_geo_zone_id');
		} else {
			$this->data['free_geo_zone_id'] = $this->config->get('free_geo_zone_id');
		}
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('free_status')) {
			$this->data['free_status'] = $this->request->getPostE('free_status');
		} else {
			$this->data['free_status'] = $this->config->get('free_status');
		}
		
		if ($this->request->hasPost('free_sort_order')) {
			$this->data['free_sort_order'] = $this->request->getPostE('free_sort_order');
		} else {
			$this->data['free_sort_order'] = $this->config->get('free_sort_order');
		}				
								
		$this->view->pick('shipping/free');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/free')) {
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
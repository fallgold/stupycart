<?php

namespace Stupycart\Admin\Controllers\Shipping;

class FedexController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	
	public function indexAction() {   
		$this->language->load('shipping/fedex');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
				
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fedex', $this->request->getPostE());		
					
			$this->session->set('success', $this->language->get('text_success'));
						
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_regular_pickup'] = $this->language->get('text_regular_pickup');
		$this->data['text_request_courier'] = $this->language->get('text_request_courier');
		$this->data['text_drop_box'] = $this->language->get('text_drop_box');
		$this->data['text_business_service_center'] = $this->language->get('text_business_service_center');
		$this->data['text_station'] = $this->language->get('text_station');
		
		$this->data['text_fedex_envelope'] = $this->language->get('text_fedex_envelope');
		$this->data['text_fedex_pak'] = $this->language->get('text_fedex_pak');
		$this->data['text_fedex_box'] = $this->language->get('text_fedex_box');
		$this->data['text_fedex_tube'] = $this->language->get('text_fedex_tube');
		$this->data['text_fedex_10kg_box'] = $this->language->get('text_fedex_10kg_box');
		$this->data['text_fedex_25kg_box'] = $this->language->get('text_fedex_25kg_box');
		$this->data['text_your_packaging'] = $this->language->get('text_your_packaging');
		$this->data['text_list_rate'] = $this->language->get('text_list_rate');
		$this->data['text_account_rate'] = $this->language->get('text_account_rate');
						
		$this->data['entry_key'] = $this->language->get('entry_key');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_meter'] = $this->language->get('entry_meter');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_service'] = $this->language->get('entry_service');
		$this->data['entry_dropoff_type'] = $this->language->get('entry_dropoff_type');
		$this->data['entry_packaging_type'] = $this->language->get('entry_packaging_type');
		$this->data['entry_rate_type'] = $this->language->get('entry_rate_type');
		$this->data['entry_display_time'] = $this->language->get('entry_display_time');
		$this->data['entry_display_weight'] = $this->language->get('entry_display_weight');		
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
		
 		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = '';
		}
		
 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
				
 		if (isset($this->error['account'])) {
			$this->data['error_account'] = $this->error['account'];
		} else {
			$this->data['error_account'] = '';
		}
 		
		if (isset($this->error['meter'])) {
			$this->data['error_meter'] = $this->error['meter'];
		} else {
			$this->data['error_meter'] = '';
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
			'href'      => $this->url->link('shipping/fedex', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/fedex', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->get('token'), 'SSL');
		
		if ($this->request->hasPost('fedex_key')) {
			$this->data['fedex_key'] = $this->request->getPostE('fedex_key');
		} else {
			$this->data['fedex_key'] = $this->config->get('fedex_key');
		}
		
		if ($this->request->hasPost('fedex_password')) {
			$this->data['fedex_password'] = $this->request->getPostE('fedex_password');
		} else {
			$this->data['fedex_password'] = $this->config->get('fedex_password');
		}
					
		if ($this->request->hasPost('fedex_account')) {
			$this->data['fedex_account'] = $this->request->getPostE('fedex_account');
		} else {
			$this->data['fedex_account'] = $this->config->get('fedex_account');
		}

		if ($this->request->hasPost('fedex_meter')) {
			$this->data['fedex_meter'] = $this->request->getPostE('fedex_meter');
		} else {
			$this->data['fedex_meter'] = $this->config->get('fedex_meter');
		}
		
		if ($this->request->hasPost('fedex_postcode')) {
			$this->data['fedex_postcode'] = $this->request->getPostE('fedex_postcode');
		} else {
			$this->data['fedex_postcode'] = $this->config->get('fedex_postcode');
		}
		
		if ($this->request->hasPost('fedex_test')) {
			$this->data['fedex_test'] = $this->request->getPostE('fedex_test');
		} else {
			$this->data['fedex_test'] = $this->config->get('fedex_test');
		}
										
		if ($this->request->hasPost('fedex_service')) {
			$this->data['fedex_service'] = $this->request->getPostE('fedex_service');
		} elseif ($this->config->has('fedex_service')) {
			$this->data['fedex_service'] = $this->config->get('fedex_service');
		} else {
			$this->data['fedex_service'] = array();	
		}
		
		$this->data['services'] = array();
		
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_europe_first_international_priority'),
			'value' => 'EUROPE_FIRST_INTERNATIONAL_PRIORITY'
		);
			
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_1_day_freight'),
			'value' => 'FEDEX_1_DAY_FREIGHT'
		);
		
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_2_day'),
			'value' => 'FEDEX_2_DAY'
		);
		
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_2_day_am'),
			'value' => 'FEDEX_2_DAY_AM'
		);
				
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_2_day_freight'),
			'value' => 'FEDEX_2_DAY_FREIGHT'
		);
						
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_3_day_freight'),
			'value' => 'FEDEX_3_DAY_FREIGHT'
		);		
				
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_express_saver'),
			'value' => 'FEDEX_EXPRESS_SAVER'
		);
		   
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_first_freight'),
			'value' => 'FEDEX_FIRST_FREIGHT'
		);	

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_freight_economy'),
			'value' => 'FEDEX_FREIGHT_ECONOMY'
		);	

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_freight_priority'),
			'value' => 'FEDEX_FREIGHT_PRIORITY'
		);	

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_fedex_ground'),
			'value' => 'FEDEX_GROUND'
		);	

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_first_overnight'),
			'value' => 'FIRST_OVERNIGHT'
		);
		
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_ground_home_delivery'),
			'value' => 'GROUND_HOME_DELIVERY'
		);	

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_international_economy'),
			'value' => 'INTERNATIONAL_ECONOMY'
		);
		
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_international_economy_freight'),
			'value' => 'INTERNATIONAL_ECONOMY_FREIGHT'
		);			
	
		$this->data['services'][] = array(
			'text'  => $this->language->get('text_international_first'),
			'value' => 'INTERNATIONAL_FIRST'
		);		

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_international_priority'),
			'value' => 'INTERNATIONAL_PRIORITY'
		);

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_international_priority_freight'),
			'value' => 'INTERNATIONAL_PRIORITY_FREIGHT'
		);	

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_priority_overnight'),
			'value' => 'PRIORITY_OVERNIGHT'
		);

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_smart_post'),
			'value' => 'SMART_POST'
		);

		$this->data['services'][] = array(
			'text'  => $this->language->get('text_standard_overnight'),
			'value' => 'STANDARD_OVERNIGHT'
		);	
		
		if ($this->request->hasPost('fedex_dropoff_type')) {
			$this->data['fedex_dropoff_type'] = $this->request->getPostE('fedex_dropoff_type');
		} else {
			$this->data['fedex_dropoff_type'] = $this->config->get('fedex_dropoff_type');
		}		
		
		if ($this->request->hasPost('fedex_packaging_type')) {
			$this->data['fedex_packaging_type'] = $this->request->getPostE('fedex_packaging_type');
		} else {
			$this->data['fedex_packaging_type'] = $this->config->get('fedex_packaging_type');
		}
		
		if ($this->request->hasPost('fedex_rate_type')) {
			$this->data['fedex_rate_type'] = $this->request->getPostE('fedex_rate_type');
		} else {
			$this->data['fedex_rate_type'] = $this->config->get('fedex_rate_type');
		}
			
		if ($this->request->hasPost('fedex_destination_type')) {
			$this->data['fedex_destination_type'] = $this->request->getPostE('fedex_destination_type');
		} else {
			$this->data['fedex_destination_type'] = $this->config->get('fedex_destination_type');
		}
		
		if ($this->request->hasPost('fedex_display_time')) {
			$this->data['fedex_display_time'] = $this->request->getPostE('fedex_display_time');
		} else {
			$this->data['fedex_display_time'] = $this->config->get('fedex_display_time');
		}

		if ($this->request->hasPost('fedex_display_weight')) {
			$this->data['fedex_display_weight'] = $this->request->getPostE('fedex_display_weight');
		} else {
			$this->data['fedex_display_weight'] = $this->config->get('fedex_display_weight');
		}
				
		if ($this->request->hasPost('fedex_weight_class_id')) {
			$this->data['fedex_weight_class_id'] = $this->request->getPostE('fedex_weight_class_id');
		} else {
			$this->data['fedex_weight_class_id'] = $this->config->get('fedex_weight_class_id');
		}
		
		$this->model_localisation_weight_class = new \Stupycart\Common\Models\Admin\Localisation\WeightClass();
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
																																						
		if ($this->request->hasPost('fedex_tax_class_id')) {
			$this->data['fedex_tax_class_id'] = $this->request->getPostE('fedex_tax_class_id');
		} else {
			$this->data['fedex_tax_class_id'] = $this->config->get('fedex_tax_class_id');
		}
		
		$this->model_localisation_tax_class = new \Stupycart\Common\Models\Admin\Localisation\TaxClass();
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		if ($this->request->hasPost('fedex_geo_zone_id')) {
			$this->data['fedex_geo_zone_id'] = $this->request->getPostE('fedex_geo_zone_id');
		} else {
			$this->data['fedex_geo_zone_id'] = $this->config->get('fedex_geo_zone_id');
		}
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('fedex_status')) {
			$this->data['fedex_status'] = $this->request->getPostE('fedex_status');
		} else {
			$this->data['fedex_status'] = $this->config->get('fedex_status');
		}	
		
		if ($this->request->hasPost('fedex_sort_order')) {
			$this->data['fedex_sort_order'] = $this->request->getPostE('fedex_sort_order');
		} else {
			$this->data['fedex_sort_order'] = $this->config->get('fedex_sort_order');
		}				
								
		$this->view->pick('shipping/fedex');
		$this->_commonAction();
				
 		$this->view->setVars($this->data);
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/fedex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->getPostE('fedex_key')) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		if (!$this->request->getPostE('fedex_password')) {
			$this->error['password'] = $this->language->get('error_password');
		}
						
		if (!$this->request->getPostE('fedex_account')) {
			$this->error['account'] = $this->language->get('error_account');
		}
		
		if (!$this->request->getPostE('fedex_meter')) {
			$this->error['meter'] = $this->language->get('error_meter');
		}
		
		if (!$this->request->getPostE('fedex_postcode')) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
				
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>
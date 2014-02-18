<?php 

namespace Stupycart\Admin\Controllers\Payment;

class BankTransferController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {
		$this->language->load('payment/bank_transfer');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('bank_transfer', $this->request->getPostE());				
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_bank'] = $this->language->get('entry_bank');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
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
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (isset($this->error['bank_' . $language['language_id']])) {
				$this->data['error_bank_' . $language['language_id']] = $this->error['bank_' . $language['language_id']];
			} else {
				$this->data['error_bank_' . $language['language_id']] = '';
			}
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/bank_transfer', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/bank_transfer', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');

		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		foreach ($languages as $language) {
			if (isset($this->request->getPostE('bank_transfer_bank_' . $language['language_id')])) {
				$this->data['bank_transfer_bank_' . $language['language_id']] = $this->request->getPostE('bank_transfer_bank_' . $language['language_id')];
			} else {
				$this->data['bank_transfer_bank_' . $language['language_id']] = $this->config->get('bank_transfer_bank_' . $language['language_id']);
			}
		}
		
		$this->data['languages'] = $languages;
		
		if ($this->request->hasPost('bank_transfer_total')) {
			$this->data['bank_transfer_total'] = $this->request->getPostE('bank_transfer_total');
		} else {
			$this->data['bank_transfer_total'] = $this->config->get('bank_transfer_total'); 
		} 
				
		if ($this->request->hasPost('bank_transfer_order_status_id')) {
			$this->data['bank_transfer_order_status_id'] = $this->request->getPostE('bank_transfer_order_status_id');
		} else {
			$this->data['bank_transfer_order_status_id'] = $this->config->get('bank_transfer_order_status_id'); 
		} 
		
		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if ($this->request->hasPost('bank_transfer_geo_zone_id')) {
			$this->data['bank_transfer_geo_zone_id'] = $this->request->getPostE('bank_transfer_geo_zone_id');
		} else {
			$this->data['bank_transfer_geo_zone_id'] = $this->config->get('bank_transfer_geo_zone_id'); 
		} 
		
		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if ($this->request->hasPost('bank_transfer_status')) {
			$this->data['bank_transfer_status'] = $this->request->getPostE('bank_transfer_status');
		} else {
			$this->data['bank_transfer_status'] = $this->config->get('bank_transfer_status');
		}
		
		if ($this->request->hasPost('bank_transfer_sort_order')) {
			$this->data['bank_transfer_sort_order'] = $this->request->getPostE('bank_transfer_sort_order');
		} else {
			$this->data['bank_transfer_sort_order'] = $this->config->get('bank_transfer_sort_order');
		}
		

		$this->view->pick('payment/bank_transfer');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bank_transfer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();

		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (!$this->request->getPostE('bank_transfer_bank_' . $language['language_id')]) {
				$this->error['bank_' .  $language['language_id']] = $this->language->get('error_bank');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
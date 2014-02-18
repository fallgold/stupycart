<?php

namespace Stupycart\Admin\Controllers\Payment;

class PayPointController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();

	public function indexAction() {
		$this->language->load('payment/paypoint');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();

		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paypoint', $this->request->getPostE());

			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_successful'] = $this->language->get('text_successful');
		$this->data['text_fail'] = $this->language->get('text_fail');

		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_test'] = $this->language->get('entry_test');
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

		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
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
			'href'      => $this->url->link('payment/paypoint', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/paypoint', 'token=' . $this->session->get('token'), 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->get('token'), 'SSL');

		if ($this->request->hasPost('paypoint_merchant')) {
			$this->data['paypoint_merchant'] = $this->request->getPostE('paypoint_merchant');
		} else {
			$this->data['paypoint_merchant'] = $this->config->get('paypoint_merchant');
		}

		if ($this->request->hasPost('paypoint_password')) {
			$this->data['paypoint_password'] = $this->request->getPostE('paypoint_password');
		} else {
			$this->data['paypoint_password'] = $this->config->get('paypoint_password');
		}

		if ($this->request->hasPost('paypoint_test')) {
			$this->data['paypoint_test'] = $this->request->getPostE('paypoint_test');
		} else {
			$this->data['paypoint_test'] = $this->config->get('paypoint_test');
		}

		if ($this->request->hasPost('paypoint_total')) {
			$this->data['paypoint_total'] = $this->request->getPostE('paypoint_total');
		} else {
			$this->data['paypoint_total'] = $this->config->get('paypoint_total');
		}

		if ($this->request->hasPost('paypoint_order_status_id')) {
			$this->data['paypoint_order_status_id'] = $this->request->getPostE('paypoint_order_status_id');
		} else {
			$this->data['paypoint_order_status_id'] = $this->config->get('paypoint_order_status_id');
		}

		$this->model_localisation_order_status = new \Stupycart\Common\Models\Admin\Localisation\OrderStatus();

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if ($this->request->hasPost('paypoint_geo_zone_id')) {
			$this->data['paypoint_geo_zone_id'] = $this->request->getPostE('paypoint_geo_zone_id');
		} else {
			$this->data['paypoint_geo_zone_id'] = $this->config->get('paypoint_geo_zone_id');
		}

		$this->model_localisation_geo_zone = new \Stupycart\Common\Models\Admin\Localisation\GeoZone();

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if ($this->request->hasPost('paypoint_status')) {
			$this->data['paypoint_status'] = $this->request->getPostE('paypoint_status');
		} else {
			$this->data['paypoint_status'] = $this->config->get('paypoint_status');
		}

		if ($this->request->hasPost('paypoint_sort_order')) {
			$this->data['paypoint_sort_order'] = $this->request->getPostE('paypoint_sort_order');
		} else {
			$this->data['paypoint_sort_order'] = $this->config->get('paypoint_sort_order');
		}

		$this->view->pick('payment/paypoint');
		$this->_commonAction();

		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paypoint')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->getPostE('paypoint_merchant')) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
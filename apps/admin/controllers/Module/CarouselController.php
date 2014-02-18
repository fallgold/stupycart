<?php

namespace Stupycart\Admin\Controllers\Module;

class CarouselController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 

	public function indexAction() {   
		$this->language->load('module/carousel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();

		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('carousel', $this->request->getPostE());		

			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');

		$this->data['entry_banner'] = $this->language->get('entry_banner');
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_scroll'] = $this->language->get('entry_scroll');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = array();
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/carousel', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('module/carousel', 'token=' . $this->session->get('token'), 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->get('token'), 'SSL');

		$this->data['modules'] = array();

		if ($this->request->hasPost('carousel_module')) {
			$this->data['modules'] = $this->request->getPostE('carousel_module');
		} elseif ($this->config->get('carousel_module')) { 
			$this->data['modules'] = $this->config->get('carousel_module');
		}

		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->model_design_banner = new \Stupycart\Common\Models\Admin\Design\Banner();

		$this->data['banners'] = $this->model_design_banner->getBanners();

		$this->view->pick('module/carousel');
		$this->_commonAction();

		$this->view->setVars($this->data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/carousel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->hasPost('carousel_module')) {
			foreach ($this->request->getPostE('carousel_module') as $key => $value) {				
				if (!$value['width'] || !$value['height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
				}
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
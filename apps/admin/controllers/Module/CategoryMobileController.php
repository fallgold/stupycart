<?php

namespace Stupycart\Admin\Controllers\Module;

class CategoryMobileController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	
	public function indexAction() {   
		$this->language->load('module/category_mobile');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
				
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('category_mobile', $this->request->getPostE());		
					
			$this->session->set('success', $this->language->get('text_success'));
						
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->get('token'), 'SSL'), true);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_count'] = $this->language->get('entry_count');
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
			'href'      => $this->url->link('module/category_mobile', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/category_mobile', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['modules'] = array();
		
		if ($this->request->hasPost('category_mobile_module')) {
			$this->data['modules'] = $this->request->getPostE('category_mobile_module');
		} elseif ($this->config->get('category_mobile_module')) { 
			$this->data['modules'] = $this->config->get('category_mobile_module');
		}	
				
		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->view->pick('module/category_mobile');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/category_mobile')) {
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
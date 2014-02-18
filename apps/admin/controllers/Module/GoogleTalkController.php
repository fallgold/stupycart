<?php

namespace Stupycart\Admin\Controllers\Module;

class GoogleTalkController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	
	public function indexAction() {   
		$this->language->load('module/google_talk');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
				
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_talk', $this->request->getPostE());		
					
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
		
		$this->data['entry_code'] = $this->language->get('entry_code');
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
		
 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
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
			'href'      => $this->url->link('module/google_talk', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/google_talk', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->get('token'), 'SSL');

		if ($this->request->hasPost('google_talk_code')) {
			$this->data['google_talk_code'] = $this->request->getPostE('google_talk_code');
		} else {
			$this->data['google_talk_code'] = $this->config->get('google_talk_code');
		}	
		
		$this->data['modules'] = array();
		
		if ($this->request->hasPost('google_talk_module')) {
			$this->data['modules'] = $this->request->getPostE('google_talk_module');
		} elseif ($this->config->get('google_talk_module')) { 
			$this->data['modules'] = $this->config->get('google_talk_module');
		}			
				
		$this->model_design_layout = new \Stupycart\Common\Models\Admin\Design\Layout();
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->view->pick('module/google_talk');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/google_talk')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->getPostE('google_talk_code')) {
			$this->error['code'] = $this->language->get('error_code');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
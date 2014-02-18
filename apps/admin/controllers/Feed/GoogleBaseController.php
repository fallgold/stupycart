<?php 

namespace Stupycart\Admin\Controllers\Feed;

class GoogleBaseController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array(); 
	
	public function indexAction() {
		$this->language->load('feed/google_base');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_base', $this->request->getPostE());				
			
			$this->session->set('success', $this->language->get('text_success'));

			$this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_data_feed'] = $this->language->get('entry_data_feed');
		
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
       		'text'      => $this->language->get('text_feed'),
			'href'      => $this->url->link('extension/feed', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('feed/google_base', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('feed/google_base', 'token=' . $this->session->get('token'), 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->get('token'), 'SSL');
		
		if ($this->request->hasPost('google_base_status')) {
			$this->data['google_base_status'] = $this->request->getPostE('google_base_status');
		} else {
			$this->data['google_base_status'] = $this->config->get('google_base_status');
		}
		
		$this->data['data_feed'] = HTTP_CATALOG . 'feed/google_base';

		$this->view->pick('feed/google_base');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	} 
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'feed/google_base')) {
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
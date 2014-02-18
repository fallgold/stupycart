<?php 

namespace Stupycart\Admin\Controllers\Tool;

class ErrorLogController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
	
	public function indexAction() {		
		$this->language->load('tool/error_log');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['button_clear'] = $this->language->get('button_clear');

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/error_log', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['clear'] = $this->url->link('tool/error_log/clear', 'token=' . $this->session->get('token'), 'SSL');
		
		$file = DIR_LOGS . $this->config->get('config_error_filename');
		
		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$this->data['log'] = '';
		}

		$this->view->pick('tool/error_log');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	public function clearAction() {
		$this->language->load('tool/error_log');
		
		$file = DIR_LOGS . $this->config->get('config_error_filename');
		
		$handle = fopen($file, 'w+'); 
				
		fclose($handle); 			
		
		$this->session->set('success', $this->language->get('text_success'));
		
		$this->response->redirect($this->url->link('tool/error_log', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;		
	}
}
?>
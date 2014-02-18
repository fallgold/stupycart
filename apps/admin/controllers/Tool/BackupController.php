<?php 

namespace Stupycart\Admin\Controllers\Tool;

class BackupController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
	
	public function indexAction() {		
		$this->language->load('tool/backup');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_tool_backup = new \Stupycart\Common\Models\Admin\Tool\Backup();
				
		if ($this->request->getServer('REQUEST_METHOD') == 'POST' && $this->user->hasPermission('modify', 'tool/backup')) {
			if (is_uploaded_file($_FILES['import']['tmp_name'])) {
				$content = file_get_contents($_FILES['import']['tmp_name']);
			} else {
				$content = false;
			}
			
			if ($content) {
				$this->model_tool_backup->restore($content);
				
				$this->session->set('success', $this->language->get('text_success'));
				
				$this->response->redirect($this->url->link('tool/backup', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
			} else {
				$this->error['warning'] = $this->language->get('error_empty');
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		
		$this->data['entry_restore'] = $this->language->get('entry_restore');
		$this->data['entry_backup'] = $this->language->get('entry_backup');
		 
		$this->data['button_backup'] = $this->language->get('button_backup');
		$this->data['button_restore'] = $this->language->get('button_restore');
		
		if ($this->session->has('error')) {
    		$this->data['error_warning'] = $this->session->get('error');
    
			$this->session->remove('error');
 		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
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
			'href'      => $this->url->link('tool/backup', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['restore'] = $this->url->link('tool/backup', 'token=' . $this->session->get('token'), 'SSL');

		$this->data['backup'] = $this->url->link('tool/backup/backup', 'token=' . $this->session->get('token'), 'SSL');

		$this->data['tables'] = $this->model_tool_backup->getTables();

		$this->view->pick('tool/backup');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	public function backupAction() {
		$this->language->load('tool/backup');
		
		if (!$this->request->hasPost('backup')) {
			$this->session->set('error', $this->language->get('error_backup'));
			
			$this->response->redirect($this->url->link('tool/backup', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		} elseif ($this->user->hasPermission('modify', 'tool/backup')) {
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . date('Y-m-d_H-i-s', time()).'_backup.sql');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			
			$this->model_tool_backup = new \Stupycart\Common\Models\Admin\Tool\Backup();
			
			$this->response->setContent($this->model_tool_backup->backup($this->request->getPostE('backup')));
		return $this->response;
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
			
			$this->response->redirect($this->url->link('tool/backup', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;			
		}
	}
}
?>
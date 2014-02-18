<?php

namespace Stupycart\Admin\Controllers\Extension;

class TotalController extends \Stupycart\Admin\Controllers\ControllerBase {
	public function indexAction() {
		$this->language->load('extension/total');
		 
		$this->document->setTitle($this->language->get('heading_title')); 

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
			
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');
		
			$this->session->remove('success');
		} else {
			$this->data['success'] = '';
		}

		if ($this->session->has('error')) {
			$this->data['error'] = $this->session->get('error');
		
			$this->session->remove('error');
		} else {
			$this->data['error'] = '';
		}

		$this->model_setting_extension = new \Stupycart\Common\Models\Admin\Setting\Extension();

		$extensions = $this->model_setting_extension->getInstalled('total');
		
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/total/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('total', $value);
				
				unset($extensions[$key]);
			}
		}
		
		$this->data['extensions'] = array();
				
		$files = glob(DIR_APPLICATION . 'controller/total/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->language->load('total/' . $extension);
	
				$action = array();
				
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => $this->url->link('extension/total/install', 'token=' . $this->session->get('token') . '&extension=' . $extension, 'SSL')
					);
				} else {
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('total/' . $extension . '', 'token=' . $this->session->get('token'), 'SSL')
					);
								
					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => $this->url->link('extension/total/uninstall', 'token=' . $this->session->get('token') . '&extension=' . $extension, 'SSL')
					);
				}
										
				$this->data['extensions'][] = array(
					'name'       => $this->language->get('heading_title'),
					'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get($extension . '_sort_order'),
					'action'     => $action
				);
			}
		}

		$this->view->pick('extension/total');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	public function installAction() {
		$this->language->load('extension/total');
			
		if (!$this->user->hasPermission('modify', 'extension/total')) {
			$this->session->set('error', $this->language->get('error_permission')); 
			
			$this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		} else {				
			$this->model_setting_extension = new \Stupycart\Common\Models\Admin\Setting\Extension();
		
			$this->model_setting_extension->install('total', $this->request->getQueryE('extension'));

			$this->model_user_user_group = new \Stupycart\Common\Models\Admin\User\UserGroup();
		
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'total/' . $this->request->getQueryE('extension'));
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'total/' . $this->request->getQueryE('extension'));

			require_once(DIR_APPLICATION . 'controller/total/' . $this->request->getQueryE('extension') . '.php');
			
			$class = 'ControllerTotal' . str_replace('_', '', $this->request->getQueryE('extension'));
			$class = new $class($this->registry);
			
			if (method_exists($class, 'install')) {
				$class->install();
			}
			
			$this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
	}
	
	public function uninstallAction() {
		$this->language->load('extension/total');
		
		if (!$this->user->hasPermission('modify', 'extension/total')) {
			$this->session->set('error', $this->language->get('error_permission')); 
			
			$this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		} else {			
			$this->model_setting_extension = new \Stupycart\Common\Models\Admin\Setting\Extension();
			$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
		
			$this->model_setting_extension->uninstall('total', $this->request->getQueryE('extension'));
		
			$this->model_setting_setting->deleteSetting($this->request->getQueryE('extension'));
		
			require_once(DIR_APPLICATION . 'controller/total/' . $this->request->getQueryE('extension') . '.php');
			
			$class = 'ControllerTotal' . str_replace('_', '', $this->request->getQueryE('extension'));
			$class = new $class($this->registry);
			
			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}
		
			$this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->get('token'), 'SSL'), true);
		return;
		}
	}	
}
?>
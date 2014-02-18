<?php 

namespace Stupycart\Admin\Controllers\Design;

class BannerController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
 
	public function indexAction() {
		$this->language->load('design/banner');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_banner = new \Stupycart\Common\Models\Admin\Design\Banner();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('design/banner');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_banner = new \Stupycart\Common\Models\Admin\Design\Banner();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_design_banner->addBanner($this->request->getPostE());
			
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
			
			$this->response->redirect($this->url->link('design/banner', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('design/banner');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_banner = new \Stupycart\Common\Models\Admin\Design\Banner();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_design_banner->editBanner($this->request->getQueryE('banner_id'), $this->request->getPostE());

			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}
					
			$this->response->redirect($this->url->link('design/banner', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}
 
	public function deleteAction() {
		$this->language->load('design/banner');
 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_design_banner = new \Stupycart\Common\Models\Admin\Design\Banner();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $banner_id) {
				$this->model_design_banner->deleteBanner($banner_id);
			}
			
			$this->session->set('success', $this->language->get('text_success'));

			$url = '';
			
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}

			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}

			if ($this->request->hasQuery('page')) {
				$url .= '&page=' . $this->request->getQueryE('page');
			}

			$this->response->redirect($this->url->link('design/banner', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'name';
		}
		
		if ($this->request->hasQuery('order')) {
			$order = $this->request->getQueryE('order');
		} else {
			$order = 'ASC';
		}
		
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}
			
		$url = '';
			
		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}

		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
		
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('design/banner', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('design/banner/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('design/banner/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		 
		$this->data['banners'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$banner_total = $this->model_design_banner->getTotalBanners();
		
		$results = $this->model_design_banner->getBanners($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('design/banner/update', 'token=' . $this->session->get('token') . '&banner_id=' . $result['banner_id'] . $url, 'SSL')
			);

			$this->data['banners'][] = array(
				'banner_id' => $result['banner_id'],
				'name'      => $result['name'],	
				'status'    => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),				
				'selected'  => $this->request->hasPost('selected') && in_array($result['banner_id'], $this->request->getPostE('selected')),				
				'action'    => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');	

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}
		
		$this->data['sort_name'] = $this->url->link('design/banner', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('design/banner', 'token=' . $this->session->get('token') . '&sort=status' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $banner_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('design/banner', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('design/banner_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');			
				
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_link'] = $this->language->get('entry_link');
		$this->data['entry_image'] = $this->language->get('entry_image');		
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_banner'] = $this->language->get('button_add_banner');
		$this->data['button_remove'] = $this->language->get('button_remove');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

 		if (isset($this->error['banner_image'])) {
			$this->data['error_banner_image'] = $this->error['banner_image'];
		} else {
			$this->data['error_banner_image'] = array();
		}	
						
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}

		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
		
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('design/banner', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		if (!$this->request->hasQuery('banner_id')) { 
			$this->data['action'] = $this->url->link('design/banner/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('design/banner/update', 'token=' . $this->session->get('token') . '&banner_id=' . $this->request->getQueryE('banner_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('design/banner', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		if ($this->request->hasQuery('banner_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$banner_info = $this->model_design_banner->getBanner($this->request->getQueryE('banner_id'));
		}

		$this->data['token'] = $this->session->get('token');

		if ($this->request->hasPost('name')) {
			$this->data['name'] = $this->request->getPostE('name');
		} elseif (!empty($banner_info)) {
			$this->data['name'] = $banner_info['name'];
		} else {
			$this->data['name'] = '';
		}
		
		if ($this->request->hasPost('status')) {
			$this->data['status'] = $this->request->getPostE('status');
		} elseif (!empty($banner_info)) {
			$this->data['status'] = $banner_info['status'];
		} else {
			$this->data['status'] = true;
		}

		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->model_tool_image = new \Stupycart\Common\Models\Admin\Tool\Image();
	
		if ($this->request->hasPost('banner_image')) {
			$banner_images = $this->request->getPostE('banner_image');
		} elseif ($this->request->hasQuery('banner_id')) {
			$banner_images = $this->model_design_banner->getBannerImages($this->request->getQueryE('banner_id'));	
		} else {
			$banner_images = array();
		}
		
		$this->data['banner_images'] = array();
		
		foreach ($banner_images as $banner_image) {
			if ($banner_image['image'] && file_exists(DIR_IMAGE . $banner_image['image'])) {
				$image = $banner_image['image'];
			} else {
				$image = 'no_image.jpg';
			}			
			
			$this->data['banner_images'][] = array(
				'banner_image_description' => $banner_image['banner_image_description'],
				'link'                     => $banner_image['link'],
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($image, 100, 100)
			);	
		} 
	
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);		

		$this->view->pick('design/banner_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->getPostE('name')) < 3) || (utf8_strlen($this->request->getPostE('name')) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if ($this->request->hasPost('banner_image')) {
			foreach ($this->request->getPostE('banner_image') as $banner_image_id => $banner_image) {
				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
					if ((utf8_strlen($banner_image_description['title']) < 2) || (utf8_strlen($banner_image_description['title']) > 64)) {
						$this->error['banner_image'][$banner_image_id][$language_id] = $this->language->get('error_title'); 
					}					
				}
			}	
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/banner')) {
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
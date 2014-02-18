<?php 

namespace Stupycart\Admin\Controllers\Sale;

class VoucherThemeController extends \Stupycart\Admin\Controllers\ControllerBase { 
	private $error = array();
   
  	public function indexAction() {
		$this->language->load('sale/voucher_theme');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_voucher_theme = new \Stupycart\Common\Models\Admin\Sale\VoucherTheme();
		
    	$this->getList();
  	}
              
  	public function insertAction() {
		$this->language->load('sale/voucher_theme');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_voucher_theme = new \Stupycart\Common\Models\Admin\Sale\VoucherTheme();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
      		$this->model_sale_voucher_theme->addVoucherTheme($this->request->getPostE());
		  	
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
						
      		$this->response->redirect($this->url->link('sale/voucher_theme', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
	
    	$this->getForm();
  	}

  	public function updateAction() {
		$this->language->load('sale/voucher_theme');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_voucher_theme = new \Stupycart\Common\Models\Admin\Sale\VoucherTheme();
		
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
	  		$this->model_sale_voucher_theme->editVoucherTheme($this->request->getQueryE('voucher_theme_id'), $this->request->getPostE());
			
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
			
			$this->response->redirect($this->url->link('sale/voucher_theme', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}
	
    	$this->getForm();
  	}

  	public function deleteAction() {
		$this->language->load('sale/voucher_theme');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_sale_voucher_theme = new \Stupycart\Common\Models\Admin\Sale\VoucherTheme();
		
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $voucher_theme_id) {
				$this->model_sale_voucher_theme->deleteVoucherTheme($voucher_theme_id);
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
			
			$this->response->redirect($this->url->link('sale/voucher_theme', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
   		}
	
    	$this->getList();
  	}
    
  	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'vtd.name';
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
			'href'      => $this->url->link('sale/voucher_theme', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('sale/voucher_theme/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/voucher_theme/delete', 'token=' . $this->session->get('token') . $url, 'SSL');	

		$this->data['voucher_themes'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$voucher_theme_total = $this->model_sale_voucher_theme->getTotalVoucherThemes();
	
		$results = $this->model_sale_voucher_theme->getVoucherThemes($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/voucher_theme/update', 'token=' . $this->session->get('token') . '&voucher_theme_id=' . $result['voucher_theme_id'] . $url, 'SSL')
			);
						
			$this->data['voucher_themes'][] = array(
				'voucher_theme_id' => $result['voucher_theme_id'],
				'name'             => $result['name'],
				'selected'         => $this->request->hasPost('selected') && in_array($result['voucher_theme_id'], $this->request->getPostE('selected')),
				'action'           => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
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
		
		$this->data['sort_name'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->get('token') . '&sort=name' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $voucher_theme_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/voucher_theme', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('sale/voucher_theme_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
  
  	protected function getForm() {
     	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');			
   	
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_image'] = $this->language->get('entry_image');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}
		
 		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = '';
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
			'href'      => $this->url->link('sale/voucher_theme', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('voucher_theme_id')) {
			$this->data['action'] = $this->url->link('sale/voucher_theme/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/voucher_theme/update', 'token=' . $this->session->get('token') . '&voucher_theme_id=' . $this->request->getQueryE('voucher_theme_id') . $url, 'SSL');
		}
					
		$this->data['cancel'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		if ($this->request->hasQuery('voucher_theme_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
      		$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($this->request->getQueryE('voucher_theme_id'));
    	}
		
		$this->data['token'] = $this->session->get('token');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if ($this->request->hasPost('voucher_theme_description')) {
			$this->data['voucher_theme_description'] = $this->request->getPostE('voucher_theme_description');
		} elseif ($this->request->hasQuery('voucher_theme_id')) {
			$this->data['voucher_theme_description'] = $this->model_sale_voucher_theme->getVoucherThemeDescriptions($this->request->getQueryE('voucher_theme_id'));
		} else {
			$this->data['voucher_theme_description'] = array();
		}
		
		if ($this->request->hasPost('image')) {
			$this->data['image'] = $this->request->getPostE('image');
		} elseif (!empty($voucher_theme_info)) {
			$this->data['image'] = $voucher_theme_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->model_tool_image = new \Stupycart\Common\Models\Admin\Tool\Image();

		if (isset($voucher_theme_info) && $voucher_theme_info['image'] && file_exists(DIR_IMAGE . $voucher_theme_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($voucher_theme_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
				
		$this->view->pick('sale/voucher_theme_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);	
  	}
  	
	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/voucher_theme')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->getPostE('voucher_theme_description') as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}
		
		if (!$this->request->getPostE('image')) {
			$this->error['image'] = $this->language->get('error_image');
		}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/voucher_theme')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->model_sale_voucher = new \Stupycart\Common\Models\Admin\Sale\Voucher();
		
		foreach ($this->request->getPostE('selected') as $voucher_theme_id) {
			$voucher_total = $this->model_sale_voucher->getTotalVouchersByVoucherThemeId($voucher_theme_id);
		
			if ($voucher_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_voucher'), $voucher_total);	
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
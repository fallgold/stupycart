<?php  

namespace Stupycart\Admin\Controllers\Catalog;

class DownloadController extends \Stupycart\Admin\Controllers\ControllerBase {  
	private $error = array();
   
  	public function indexAction() {
		$this->language->load('catalog/download');

    	$this->document->setTitle($this->language->get('heading_title'));
	
		$this->model_catalog_download = new \Stupycart\Common\Models\Admin\Catalog\Download();
		
    	$this->getList();
  	}
  	        
  	public function insertAction() {
		$this->language->load('catalog/download');
    
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_download = new \Stupycart\Common\Models\Admin\Catalog\Download();
			
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_download->addDownload($this->request->getPostE());
   	  		
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
			
			$this->response->redirect($this->url->link('catalog/download', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
	
    	$this->getForm();
  	}

  	public function updateAction() {
		$this->language->load('catalog/download');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_download = new \Stupycart\Common\Models\Admin\Catalog\Download();
			
    	if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_download->editDownload($this->request->getQueryE('download_id'), $this->request->getPostE());
	  		
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
			
			$this->response->redirect($this->url->link('catalog/download', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}
		
    	$this->getForm();
  	}

  	public function deleteAction() {
		$this->language->load('catalog/download');
 
    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_download = new \Stupycart\Common\Models\Admin\Catalog\Download();
			
    	if ($this->request->hasPost('selected') && $this->validateDelete()) {	  
			foreach ($this->request->getPostE('selected') as $download_id) {
				$this->model_catalog_download->deleteDownload($download_id);
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
			
			$this->response->redirect($this->url->link('catalog/download', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
    	}

    	$this->getList();
  	}
    
  	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'dd.name';
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
			'href'      => $this->url->link('catalog/download', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('catalog/download/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/download/delete', 'token=' . $this->session->get('token') . $url, 'SSL');	

		$this->data['downloads'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$download_total = $this->model_catalog_download->getTotalDownloads();
	
		$results = $this->model_catalog_download->getDownloads($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/download/update', 'token=' . $this->session->get('token') . '&download_id=' . $result['download_id'] . $url, 'SSL')
			);
						
			$this->data['downloads'][] = array(
				'download_id' => $result['download_id'],
				'name'        => $result['name'],
				'remaining'   => $result['remaining'],
				'selected'    => $this->request->hasPost('selected') && in_array($result['download_id'], $this->request->getPostE('selected')),
				'action'      => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_remaining'] = $this->language->get('column_remaining');
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
		
		$this->data['sort_name'] = $this->url->link('catalog/download', 'token=' . $this->session->get('token') . '&sort=dd.name' . $url, 'SSL');
		$this->data['sort_remaining'] = $this->url->link('catalog/download', 'token=' . $this->session->get('token') . '&sort=d.remaining' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $download_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/download', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('catalog/download_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
  
  	protected function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
   
    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_filename'] = $this->language->get('entry_filename');
		$this->data['entry_mask'] = $this->language->get('entry_mask');
    	$this->data['entry_remaining'] = $this->language->get('entry_remaining');
    	$this->data['entry_update'] = $this->language->get('entry_update');
  
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
  		$this->data['button_upload'] = $this->language->get('button_upload');
		
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
		
  		if (isset($this->error['filename'])) {
			$this->data['error_filename'] = $this->error['filename'];
		} else {
			$this->data['error_filename'] = '';
		}
		
  		if (isset($this->error['mask'])) {
			$this->data['error_mask'] = $this->error['mask'];
		} else {
			$this->data['error_mask'] = '';
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
			'href'      => $this->url->link('catalog/download', 'token=' . $this->session->get('token') . $url, 'SSL'),      		
      		'separator' => ' :: '
   		);
							
		if (!$this->request->hasQuery('download_id')) {
			$this->data['action'] = $this->url->link('catalog/download/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/download/update', 'token=' . $this->session->get('token') . '&download_id=' . $this->request->getQueryE('download_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/download', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Admin\Localisation\Language();
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

    	if ($this->request->hasQuery('download_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$download_info = $this->model_catalog_download->getDownload($this->request->getQueryE('download_id'));
    	}

  		$this->data['token'] = $this->session->get('token');
  
  		if ($this->request->hasQuery('download_id')) {
			$this->data['download_id'] = $this->request->getQueryE('download_id');
		} else {
			$this->data['download_id'] = 0;
		}
		
		if ($this->request->hasPost('download_description')) {
			$this->data['download_description'] = $this->request->getPostE('download_description');
		} elseif ($this->request->hasQuery('download_id')) {
			$this->data['download_description'] = $this->model_catalog_download->getDownloadDescriptions($this->request->getQueryE('download_id'));
		} else {
			$this->data['download_description'] = array();
		}   
		
    	if ($this->request->hasPost('filename')) {
    		$this->data['filename'] = $this->request->getPostE('filename');
    	} elseif (!empty($download_info)) {
      		$this->data['filename'] = $download_info['filename'];
		} else {
			$this->data['filename'] = '';
		}
		
    	if ($this->request->hasPost('mask')) {
    		$this->data['mask'] = $this->request->getPostE('mask');
    	} elseif (!empty($download_info)) {
      		$this->data['mask'] = $download_info['mask'];		
		} else {
			$this->data['mask'] = '';
		}
		
		if ($this->request->hasPost('remaining')) {
      		$this->data['remaining'] = $this->request->getPostE('remaining');
    	} elseif (!empty($download_info)) {
      		$this->data['remaining'] = $download_info['remaining'];
    	} else {
      		$this->data['remaining'] = 1;
    	}
				 	  
    	if ($this->request->hasPost('update')) {
      		$this->data['update'] = $this->request->getPostE('update');
    	} else {
      		$this->data['update'] = false;
    	}

		$this->view->pick('catalog/download_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);	
  	}

  	protected function validateForm() { 
    	if (!$this->user->hasPermission('modify', 'catalog/download')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
	
    	foreach ($this->request->getPostE('download_description') as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}	

		if ((utf8_strlen($this->request->getPostE('filename')) < 3) || (utf8_strlen($this->request->getPostE('filename')) > 128)) {
			$this->error['filename'] = $this->language->get('error_filename');
		}	
		
		if (!file_exists(DIR_DOWNLOAD . $this->request->getPostE('filename')) && !is_file(DIR_DOWNLOAD . $this->request->getPostE('filename'))) {
			$this->error['filename'] = $this->language->get('error_exists');
		}
				
		if ((utf8_strlen($this->request->getPostE('mask')) < 3) || (utf8_strlen($this->request->getPostE('mask')) > 128)) {
			$this->error['mask'] = $this->language->get('error_mask');
		}	
			
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/download')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();

		foreach ($this->request->getPostE('selected') as $download_id) {
  			$product_total = $this->model_catalog_product->getTotalProductsByDownloadId($download_id);
    
			if ($product_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);	
			}	
		}	
			  	  	 
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		} 
  	}

	public function uploadAction() {
		$this->language->load('sale/order');
		
		$json = array();
    	
		if (!$this->user->hasPermission('modify', 'catalog/download')) {
      		$json['error'] = $this->language->get('error_permission');
    	}	
		
		if (!isset($json['error'])) {	
			if (!empty($_FILES['file']['name'])) {
				$filename = basename(html_entity_decode($_FILES['file']['name'], ENT_QUOTES, 'UTF-8'));
				
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}	  	
				
				// Allowed file extension types
				$allowed = array();
				
				$filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));
				
				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}
				
				if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}	
				
				// Allowed file mime types		
				$allowed = array();
				
				$filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));
				
				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}
								
				if (!in_array($_FILES['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
							
				if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $_FILES['file']['error']);
				}
									
				if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $_FILES['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}
		
		if (!isset($json['error'])) {
			if (is_uploaded_file($_FILES['file']['tmp_name']) && file_exists($_FILES['file']['tmp_name'])) {
				$ext = md5(mt_rand());
				 
				$json['filename'] = $filename . '.' . $ext;
				$json['mask'] = $filename;
				
				move_uploaded_file($_FILES['file']['tmp_name'], DIR_DOWNLOAD . $filename . '.' . $ext);
			}
						
			$json['success'] = $this->language->get('text_upload');
		}	
	
		$this->response->setContent(json_encode($json));
		return $this->response;
	}

	public function autocompleteAction() {
		$json = array();
		
		if ($this->request->hasQuery('filter_name')) {
			$this->model_catalog_download = new \Stupycart\Common\Models\Admin\Catalog\Download();
			
			$data = array(
				'filter_name' => $this->request->getQueryE('filter_name'),
				'start'       => 0,
				'limit'       => 20
			);
			
			$results = $this->model_catalog_download->getDownloads($data);
				
			foreach ($results as $result) {
				$json[] = array(
					'download_id' => $result['download_id'], 
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}		
		}

		$sort_order = array();
	  
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setContent(json_encode($json));
		return $this->response;
	}	
}
?>
<?php

namespace Stupycart\Admin\Controllers\Catalog;

class ReviewController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
 
	public function indexAction() {
		$this->language->load('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_review = new \Stupycart\Common\Models\Admin\Catalog\Review();
		
		$this->getList();
	} 

	public function insertAction() {
		$this->language->load('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_review = new \Stupycart\Common\Models\Admin\Catalog\Review();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->addReview($this->request->getPostE());
			
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
						
			$this->response->redirect($this->url->link('catalog/review', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_review = new \Stupycart\Common\Models\Admin\Catalog\Review();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->editReview($this->request->getQueryE('review_id'), $this->request->getPostE());
			
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
						
			$this->response->redirect($this->url->link('catalog/review', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() { 
		$this->language->load('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_catalog_review = new \Stupycart\Common\Models\Admin\Catalog\Review();

		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $review_id) {
				$this->model_catalog_review->deleteReview($review_id);
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
						
			$this->response->redirect($this->url->link('catalog/review', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'r.date_added';
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
			'href'      => $this->url->link('catalog/review', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('catalog/review/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/review/delete', 'token=' . $this->session->get('token') . $url, 'SSL');	

		$this->data['reviews'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$review_total = $this->model_catalog_review->getTotalReviews();
	
		$results = $this->model_catalog_review->getReviews($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/review/update', 'token=' . $this->session->get('token') . '&review_id=' . $result['review_id'] . $url, 'SSL')
			);
						
			$this->data['reviews'][] = array(
				'review_id'  => $result['review_id'],
				'name'       => $result['name'],
				'author'     => $result['author'],
				'rating'     => $result['rating'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => $this->request->hasPost('selected') && in_array($result['review_id'], $this->request->getPostE('selected')),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_author'] = $this->language->get('column_author');
		$this->data['column_rating'] = $this->language->get('column_rating');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
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
		
		$this->data['sort_product'] = $this->url->link('catalog/review', 'token=' . $this->session->get('token') . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_author'] = $this->url->link('catalog/review', 'token=' . $this->session->get('token') . '&sort=r.author' . $url, 'SSL');
		$this->data['sort_rating'] = $this->url->link('catalog/review', 'token=' . $this->session->get('token') . '&sort=r.rating' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/review', 'token=' . $this->session->get('token') . '&sort=r.status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('catalog/review', 'token=' . $this->session->get('token') . '&sort=r.date_added' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/review', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('catalog/review_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_rating'] = $this->language->get('entry_rating');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_good'] = $this->language->get('entry_good');
		$this->data['entry_bad'] = $this->language->get('entry_bad');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
 		
		if (isset($this->error['product'])) {
			$this->data['error_product'] = $this->error['product'];
		} else {
			$this->data['error_product'] = '';
		}
		
 		if (isset($this->error['author'])) {
			$this->data['error_author'] = $this->error['author'];
		} else {
			$this->data['error_author'] = '';
		}
		
 		if (isset($this->error['text'])) {
			$this->data['error_text'] = $this->error['text'];
		} else {
			$this->data['error_text'] = '';
		}
		
 		if (isset($this->error['rating'])) {
			$this->data['error_rating'] = $this->error['rating'];
		} else {
			$this->data['error_rating'] = '';
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
			'href'      => $this->url->link('catalog/review', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
										
		if (!$this->request->hasQuery('review_id')) { 
			$this->data['action'] = $this->url->link('catalog/review/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/review/update', 'token=' . $this->session->get('token') . '&review_id=' . $this->request->getQueryE('review_id') . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/review', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('review_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$review_info = $this->model_catalog_review->getReview($this->request->getQueryE('review_id'));
		}
		
		$this->data['token'] = $this->session->get('token');
			
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		
		if ($this->request->hasPost('product_id')) {
			$this->data['product_id'] = $this->request->getPostE('product_id');
		} elseif (!empty($review_info)) {
			$this->data['product_id'] = $review_info['product_id'];
		} else {
			$this->data['product_id'] = '';
		}

		if ($this->request->hasPost('product')) {
			$this->data['product'] = $this->request->getPostE('product');
		} elseif (!empty($review_info)) {
			$this->data['product'] = $review_info['product'];
		} else {
			$this->data['product'] = '';
		}
				
		if ($this->request->hasPost('author')) {
			$this->data['author'] = $this->request->getPostE('author');
		} elseif (!empty($review_info)) {
			$this->data['author'] = $review_info['author'];
		} else {
			$this->data['author'] = '';
		}

		if ($this->request->hasPost('text')) {
			$this->data['text'] = $this->request->getPostE('text');
		} elseif (!empty($review_info)) {
			$this->data['text'] = $review_info['text'];
		} else {
			$this->data['text'] = '';
		}

		if ($this->request->hasPost('rating')) {
			$this->data['rating'] = $this->request->getPostE('rating');
		} elseif (!empty($review_info)) {
			$this->data['rating'] = $review_info['rating'];
		} else {
			$this->data['rating'] = '';
		}

		if ($this->request->hasPost('status')) {
			$this->data['status'] = $this->request->getPostE('status');
		} elseif (!empty($review_info)) {
			$this->data['status'] = $review_info['status'];
		} else {
			$this->data['status'] = '';
		}

		$this->view->pick('catalog/review_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->getPostE('product_id')) {
			$this->error['product'] = $this->language->get('error_product');
		}
		
		if ((utf8_strlen($this->request->getPostE('author')) < 3) || (utf8_strlen($this->request->getPostE('author')) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if (utf8_strlen($this->request->getPostE('text')) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}
				
		if (!$this->request->hasPost('rating')) {
			$this->error['rating'] = $this->language->get('error_rating');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/review')) {
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
<?php 

namespace Stupycart\Admin\Controllers\Localisation;

class CurrencyController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
 
	public function indexAction() {
		$this->language->load('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_currency = new \Stupycart\Common\Models\Admin\Localisation\Currency();
		
		$this->getList();
	}

	public function insertAction() {
		$this->language->load('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_currency = new \Stupycart\Common\Models\Admin\Localisation\Currency();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_currency->addCurrency($this->request->getPostE());
			
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
						
			$this->response->redirect($this->url->link('localisation/currency', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function updateAction() {
		$this->language->load('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_currency = new \Stupycart\Common\Models\Admin\Localisation\Currency();
		
		if (($this->request->getServer('REQUEST_METHOD') == 'POST') && $this->validateForm()) {
			$this->model_localisation_currency->editCurrency($this->request->getQueryE('currency_id'), $this->request->getPostE());
			
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
						
			$this->response->redirect($this->url->link('localisation/currency', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getForm();
	}

	public function deleteAction() {
		$this->language->load('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->model_localisation_currency = new \Stupycart\Common\Models\Admin\Localisation\Currency();
		
		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->getPostE('selected') as $currency_id) {
				$this->model_localisation_currency->deleteCurrency($currency_id);
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

			$this->response->redirect($this->url->link('localisation/currency', 'token=' . $this->session->get('token') . $url, 'SSL'), true);
		return;
		}

		$this->getList();
	}

	protected function getList() {
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'title';
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
			'href'      => $this->url->link('localisation/currency', 'token=' . $this->session->get('token') . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('localisation/currency/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/currency/delete', 'token=' . $this->session->get('token') . $url, 'SSL');
		
		$this->data['currencies'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$currency_total = $this->model_localisation_currency->getTotalCurrencies();

		$results = $this->model_localisation_currency->getCurrencies($data);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/currency/update', 'token=' . $this->session->get('token') . '&currency_id=' . $result['currency_id'] . $url, 'SSL')
			);
						
			$this->data['currencies'][] = array(
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'] . (($result['code'] == $this->config->get('config_currency')) ? $this->language->get('text_default') : null),
				'code'          => $result['code'],
				'value'         => $result['value'],
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => $this->request->hasPost('selected') && in_array($result['currency_id'], $this->request->getPostE('selected')),
				'action'        => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
    	$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_value'] = $this->language->get('column_value');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
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
		
		$this->data['sort_title'] = $this->url->link('localisation/currency', 'token=' . $this->session->get('token') . '&sort=title' . $url, 'SSL');
		$this->data['sort_code'] = $this->url->link('localisation/currency', 'token=' . $this->session->get('token') . '&sort=code' . $url, 'SSL');
		$this->data['sort_value'] = $this->url->link('localisation/currency', 'token=' . $this->session->get('token') . '&sort=value' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('localisation/currency', 'token=' . $this->session->get('token') . '&sort=date_modified' . $url, 'SSL');
		
		$url = '';

		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}
												
		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}

		$pagination = new \Libs\Opencart\Pagination();
		$pagination->total = $currency_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/currency', 'token=' . $this->session->get('token') . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->view->pick('localisation/currency_list');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_value'] = $this->language->get('entry_value');
		$this->data['entry_symbol_left'] = $this->language->get('entry_symbol_left');
		$this->data['entry_symbol_right'] = $this->language->get('entry_symbol_right');
		$this->data['entry_decimal_place'] = $this->language->get('entry_decimal_place');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
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
			'href'      => $this->url->link('localisation/currency', 'token=' . $this->session->get('token') . $url, 'SSL'),      		
      		'separator' => ' :: '
   		);
		
		if (!$this->request->hasQuery('currency_id')) {
			$this->data['action'] = $this->url->link('localisation/currency/insert', 'token=' . $this->session->get('token') . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/currency/update', 'token=' . $this->session->get('token') . '&currency_id=' . $this->request->getQueryE('currency_id') . $url, 'SSL');
		}
				
		$this->data['cancel'] = $this->url->link('localisation/currency', 'token=' . $this->session->get('token') . $url, 'SSL');

		if ($this->request->hasQuery('currency_id') && ($this->request->getServer('REQUEST_METHOD') != 'POST')) {
			$currency_info = $this->model_localisation_currency->getCurrency($this->request->getQueryE('currency_id'));
		}

		if ($this->request->hasPost('title')) {
			$this->data['title'] = $this->request->getPostE('title');
		} elseif (!empty($currency_info)) {
			$this->data['title'] = $currency_info['title'];
		} else {
			$this->data['title'] = '';
		}

		if ($this->request->hasPost('code')) {
			$this->data['code'] = $this->request->getPostE('code');
		} elseif (!empty($currency_info)) {
			$this->data['code'] = $currency_info['code'];
		} else {
			$this->data['code'] = '';
		}

		if ($this->request->hasPost('symbol_left')) {
			$this->data['symbol_left'] = $this->request->getPostE('symbol_left');
		} elseif (!empty($currency_info)) {
			$this->data['symbol_left'] = $currency_info['symbol_left'];
		} else {
			$this->data['symbol_left'] = '';
		}

		if ($this->request->hasPost('symbol_right')) {
			$this->data['symbol_right'] = $this->request->getPostE('symbol_right');
		} elseif (!empty($currency_info)) {
			$this->data['symbol_right'] = $currency_info['symbol_right'];
		} else {
			$this->data['symbol_right'] = '';
		}

		if ($this->request->hasPost('decimal_place')) {
			$this->data['decimal_place'] = $this->request->getPostE('decimal_place');
		} elseif (!empty($currency_info)) {
			$this->data['decimal_place'] = $currency_info['decimal_place'];
		} else {
			$this->data['decimal_place'] = '';
		}

		if ($this->request->hasPost('value')) {
			$this->data['value'] = $this->request->getPostE('value');
		} elseif (!empty($currency_info)) {
			$this->data['value'] = $currency_info['value'];
		} else {
			$this->data['value'] = '';
		}

    	if ($this->request->hasPost('status')) {
      		$this->data['status'] = $this->request->getPostE('status');
    	} elseif (!empty($currency_info)) {
			$this->data['status'] = $currency_info['status'];
		} else {
      		$this->data['status'] = '';
    	}

		$this->view->pick('localisation/currency_form');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}
	
	protected function validateForm() { 
		if (!$this->user->hasPermission('modify', 'localisation/currency')) { 
			$this->error['warning'] = $this->language->get('error_permission');
		} 

		if ((utf8_strlen($this->request->getPostE('title')) < 3) || (utf8_strlen($this->request->getPostE('title')) > 32)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if (utf8_strlen($this->request->getPostE('code')) != 3) {
			$this->error['code'] = $this->language->get('error_code');
		}

		if (!$this->error) { 
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->model_setting_store = new \Stupycart\Common\Models\Admin\Setting\Store();
		$this->model_sale_order = new \Stupycart\Common\Models\Admin\Sale\Order();
		
		foreach ($this->request->getPostE('selected') as $currency_id) {
			$currency_info = $this->model_localisation_currency->getCurrency($currency_id);

			if ($currency_info) {
				if ($this->config->get('config_currency') == $currency_info['code']) {
					$this->error['warning'] = $this->language->get('error_default');
				}
				
				$store_total = $this->model_setting_store->getTotalStoresByCurrency($currency_info['code']);
	
				if ($store_total) {
					$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
				}					
			}
			
			$order_total = $this->model_sale_order->getTotalOrdersByCurrencyId($currency_id);

			if ($order_total) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
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
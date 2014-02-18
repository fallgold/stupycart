<?php  

namespace Stupycart\Frontend\Controllers\Module;

class CurrencyController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		if ($this->request->hasPost('currency_code')) {
      		$this->currency->set($this->request->getPostE('currency_code'));
			
			$this->session->remove('shipping_method');
			$this->session->remove('shipping_methods');
			
			if ($this->request->hasPost('redirect')) {
				$this->response->redirect($this->request->getPostE('redirect'), true);
		return;
			} else {
				$this->response->redirect($this->url->link('common/home'), true);
		return;
			}
   		}
		
		$this->language->load('module/currency');
		
    	$this->data['text_currency'] = $this->language->get('text_currency');

		if ($this->request->hasServer('HTTPS') && (($this->request->getServer('HTTPS') == 'on') || ($this->request->getServer('HTTPS') == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}
		
		$this->data['action'] = $this->url->link('module/currency', '', $connection);
		
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->model_localisation_currency = new \Stupycart\Common\Models\Localisation\Currency();
		 
		 $this->data['currencies'] = array();
		 
		$results = $this->model_localisation_currency->getCurrencies();	
		
		foreach ($results as $result) {
			if ($result['status']) {
   				$this->data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']				
				);
			}
		}
		
		if (!$this->request->hasQuery('route')) {
			$this->data['redirect'] = $this->url->link('common/home');
		} else {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}	
						
			$this->data['redirect'] = $this->url->link($route, $url, $connection);
		}	

		$this->view->pick('module/currency');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
}
?>
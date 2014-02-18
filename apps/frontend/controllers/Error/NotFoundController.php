<?php   

namespace Stupycart\Frontend\Controllers\Error;

class NotFoundController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {		
		$this->language->load('error/not_found');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['breadcrumbs'] = array();
 
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);		
		
		if ($this->request->hasQuery('route')) {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}	
			
			if ($this->request->hasServer('HTTPS') && (($this->request->getServer('HTTPS') == 'on') || ($this->request->getServer('HTTPS') == '1'))) {
				$connection = 'SSL';
			} else {
				$connection = 'NONSSL';
			}
											
       		$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link($route, $url, $connection),
        		'separator' => $this->language->get('text_separator')
      		);	   	
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_error'] = $this->language->get('text_error');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->response->addHeader($this->request->getServer('SERVER_PROTOCOL') . '/1.1 404 Not Found');
		
		$this->data['continue'] = $this->url->link('common/home');

		$this->view->pick('error/not_found');
		
		$this->_commonAction();
		
		$this->view->setVars($this->data);
  	}
}
?>
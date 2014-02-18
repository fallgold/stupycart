<?php  

namespace Stupycart\Frontend\Controllers\Module;

class LanguageController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
    	if ($this->request->hasPost('language_code')) {
			$this->session->set('language', $this->request->getPostE('language_code'));
		
			if ($this->request->hasPost('redirect')) {
				$this->response->redirect($this->request->getPostE('redirect'), true);
		return;
			} else {
				$this->response->redirect($this->url->link('common/home'), true);
		return;
			}
    	}		
		
		$this->language->load('module/language');
		
		$this->data['text_language'] = $this->language->get('text_language');
		
		if ($this->request->hasServer('HTTPS') && (($this->request->getServer('HTTPS') == 'on') || ($this->request->getServer('HTTPS') == '1'))) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}
			
		$this->data['action'] = $this->url->link('module/language', '', $connection);

		$this->data['language_code'] = $this->session->get('language');
		
		$this->model_localisation_language = new \Stupycart\Common\Models\Localisation\Language();
		
		$this->data['languages'] = array();
		
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
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
		
		$this->view->pick('module/language');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
}
?>

<?php   

namespace Stupycart\Frontend\Controllers\Module;

class StoreController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$status = true;
		
		if ($this->config->get('store_admin')) {
		
			$this->user = new \Libs\Opencart\User($this->registry);
			
			$status = $this->user->isLogged();
		}
		
		if ($status) {
			$this->language->load('module/store');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_store'] = $this->language->get('text_store');
			
			$this->data['store_id'] = $this->config->get('config_store_id');
			
			$this->data['stores'] = array();
			
			$this->data['stores'][] = array(
				'store_id' => 0,
				'name'     => $this->language->get('text_default'),
				'url'      => HTTP_SERVER . 'common/home?session_id=' . $this->session->getId()
			);
			
			$this->model_setting_store = new \Stupycart\Common\Models\Setting\Store();
			
			$results = $this->model_setting_store->getStores();
			
			foreach ($results as $result) {
				$this->data['stores'][] = array(
					'store_id' => $result['store_id'],
					'name'     => $result['name'],
					'url'      => $result['url'] . 'common/home?session_id=' . $this->session->getId()
				);
			}
	
			$this->view->pick('module/store');
			
			$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
		}
	}
}
?>
<?php  

namespace Stupycart\Frontend\Controllers\Module;

class GoogleTalkController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		$this->language->load('module/google_talk');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if ($this->request->hasServer('HTTPS') && (($this->request->getServer('HTTPS') == 'on') || ($this->request->getServer('HTTPS') == '1'))) {
			$this->data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('google_talk_code')));
		} else {
			$this->data['code'] = html_entity_decode($this->config->get('google_talk_code'));
		}
		
		$this->view->pick('module/google_talk');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
}
?>
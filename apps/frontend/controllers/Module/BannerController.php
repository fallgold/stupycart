<?php  

namespace Stupycart\Frontend\Controllers\Module;

class BannerController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction($setting) {
		static $module = 0;
		
		$this->model_design_banner = new \Stupycart\Common\Models\Design\Banner();
		$this->model_tool_image = new \Stupycart\Common\Models\Tool\Image();
		
		$this->document->addScript('js/jquery/jquery.cycle.js');
				
		$this->data['banners'] = array();
		
		$results = $this->model_design_banner->getBanner($setting['banner_id']);
		  
		foreach ($results as $result) {
			if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}
		
		$this->data['module'] = $module++;
				
		$this->view->pick('module/banner');
		
		$this->view->setVars($this->data);
		$this->view->render('defined_by_pick', 'defined_by_pick');
		return $this->view->getContent();
	}
}
?>
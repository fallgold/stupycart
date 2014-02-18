<?php

namespace Stupycart\Admin\Controllers\Index;

class IndexController extends \Stupycart\Admin\Controllers\ControllerBase
{
	protected $data = array();

	public function indexAction() {
		$this->view->content1 = "indexindex";
		$this->dispatcher->forward('demo');
	}

    public function demoAction() {
		$this->view->content2 = "demodemo";
		//$this->view->disable();
	}
}


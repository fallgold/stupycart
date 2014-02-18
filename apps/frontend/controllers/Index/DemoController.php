<?php

namespace Stupycart\Frontend\Controllers\Index;

class DemoController extends \Stupycart\Frontend\Controllers\ControllerBase
{
	protected $data = array();

	public function initialize() {
		//var_dump('ns:'. $this->dispatcher->getNamespaceName(), 'controller:'. $this->dispatcher->getControllerName(), 
			//'action:'. $this->dispatcher->getActionName(), 'subaction:'. $this->dispatcher->getSubActionName(), $this->dispatcher->getParams());
		parent::initialize();
	}

	public function indexAction() {

	}

    public function demoAction() {
		//$this->view->setViewsDir('/tmp/views');
		var_dump($this->view->getViewsDir());
		//$this->view->disable();
	}
}


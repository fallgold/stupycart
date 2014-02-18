<?php

namespace Libs\Stupy;

class Dispatcher extends \Phalcon\Mvc\Dispatcher {
	protected $_controller;

	public function getControllerName() {
		$ns = parent::getNamespaceName();
		if (($pos = strrpos($ns, "\\")) !== false) {
			$ns = substr($ns, $pos + 1);
		}
		return $ns;
	}

	public function getActionName() {
		// setControllerName(xxx, true) 的时候， controller为aa_demo时正常，为demo时php segment fault
		// 原因未查
		// 暂时改为赋值时本地存储
		//return lcfirst(parent::getControllerName());
		return $this->_controller;
	}

	public function getSubActionName() {
		return lcfirst(parent::getActionName());
	}

	//
	// namespace alter, controller独立文件夹, action独立文件, 即： 
	//	controller	=> ns
	//	action		=> controller
	//	subaction	=> action
	//
	//	此转换由StupyDispatcher内部完成，对外接口不变
	//
	//	@forward: array/null
	//		dispatch时，@forward 为null
	//			ns为初始化值，append controller即可，
	//			其他值直接set
	//		forward时，@forward 为array
	//			ns需要replace
	//			其他值alter后引用返回
	//
	protected function _nsAlter(&$forward = null, $controller = null, $action = null, $subaction = null) {
		if (is_null($forward)) {
			if (!is_null($controller)) {
				$ns = parent::getNamespaceName();
				$this->setNamespaceName($ns. "\\". ucfirst(strtolower($controller)));
			}
			if (!is_null($action)) {
				$action = strtolower($action);
				$this->_controller = $action;
				$this->setControllerName(\Phalcon\Text::camelize($action), true);
			}
			if (!is_null($subaction)) {
				$this->setActionName(\Phalcon\Text::camelize(strtolower($subaction)));
			}
		} else {
			unset($forward['controller']);
			unset($forward['action']);
			unset($forward['subaction']);
			if (!is_null($controller)) {
				$ns = parent::getNamespaceName();
				$pos = strrpos($ns, "\\");
				$ns = substr($ns, 0, $pos);
				$this->setNamespaceName($ns. "\\". ucfirst(strtolower($controller)));
			}
			if (!is_null($action))
				$forward['controller'] = $action;
			if (!is_null($subaction))
				$forward['action'] = $subaction;
		}
		//var_dump($this->getHandlerClass(), $this->getNamespaceName(), $this->getControllerName(), $this->getActionName());
	}

	public function dispatch() {
		$params = $this->getParams();
		$forward = null;
		$this->_nsAlter($forward, parent::getControllerName(), parent::getActionName(), (!empty($params[0]) ? $params[0] : 'index'));

		return parent::dispatch();
	}

	// 
	// usage:
	//	ie. current route is: /aaa/bbb/index
	//	dispatcher->forward('ccc')					<=> /aaa/bbb/ccc
	//	dispatcher->forward('eee', 'fff')			<=> /aaa/eee/fff
	//	dispatcher->forward('eee', 'fff', 'ggg')	<=> /eee/fff/ggg
	//
	public function forward($controller, $action = null, $subaction = null, $params = null) {
		$forward = array();
		if (is_array($controller)) { // phalcon forward
			$forward = $controller;
			$controller = isset($forward['controller']) ? $forward['controller'] : null;
			$action = isset($forward['action']) ? $forward['action'] : null;
			$subaction = isset($forward['subaction']) ? $forward['subaction'] : null;
		} elseif (empty($subaction)) {
			if (empty($action)) {
				$subaction = $controller;
				$controller = null;
			} else {
				$subaction = $action;
				$action = $controller;
				$controller = null;
			}
		}

		$this->_nsAlter($forward, $controller, $action, $subaction);

		return parent::forward($forward);
	}
}

<?php

namespace Libs\Stupy;

class StupyTplDIProxy extends \Phalcon\DI\Injectable {
}

//
// emulate:
//		StupyTplDI extends StupyTpl, Phalcon\DI\Injectable
//
class StupyTplDI extends \StupyTpl implements \Phalcon\Events\EventsAwareInterface, \Phalcon\DI\InjectionAwareInterface {
	protected $_di = NULL;

	public function setDI($di) {
		if (is_null($this->_di))
			$this->_di = new StupyTplDIProxy;
		$this->_di->setDI($di);
	}

	public function getDI() {
		if (!is_null($this->_di))
			return $this->_di->getDI();
	}
	
	public function setEventsManager ($eventsManager) {
		if (!is_null($this->_di))
			return $this->_di->setEventsManager($eventsManager);
	}

	public function getEventsManager () {
		if (!is_null($this->_di))
			return $this->_di->getEventsManager();
	}

	// main function
	public function __get($propName = NULL) {
		if (!is_null($this->_di))
			return $this->_di->$propName; 
	}
}

/**
 * Phalcon\Mvc\View\Engine\Stupy
 * Adapter to use Stupy library as templating engine
 */
class StupyTplAdapter extends \Phalcon\Mvc\View\Engine implements \Phalcon\Mvc\View\EngineInterface
{
	protected $_stupy;
	protected $_view;

	/**
	 * Phalcon\Mvc\View\Engine\Stupy constructor
	 *
	 * @param \Phalcon\Mvc\ViewInterface $view
	 * @param \Phalcon\DiInterface       $di
	 */
	public function __construct($view, $di = null)
	{
		parent::__construct($view, $di);

		$this->_stupy = new StupyTplDI();
		$this->_stupy->setDI($di);
		$this->_view = $view;

		\StupyTpl::setStaticOption('tpl_tag_symbol', 0); // default is 1, since we cannot use '$this'.
		\StupyTpl::setTag('css', '{$this->tag->stylesheetLink($p[0])}');
		\StupyTpl::setTag('js', '{$this->tag->javascriptInclude($p[0])}');
		\StupyTpl::setTag('link', '{$this->tag->linkTo($p)}');
	}
	
	/**
	 * Renders a view
	 *
	 * @param string $path
	 * @param array  $params
	 */
	public function render($path, $params, $mustClean = null)
	{
		// $path为absolute path的时候，StupyTpl不会自动管理include_dir.
		\StupyTpl::setStaticOption('tpl_include_dir', dirname($path));

		if (!isset($params['content'])) {
			$params['content'] = $this->_view->getContent();
		}
		$this->_stupy->assign($params);
		$this->_view->setContent($this->_stupy->render($path));
	}

	/**
	 * Set Stupy's options
	 *
	 * @param array $options
	 */
	public function setOptions(array $options)
	{
		//
		// TODO
		// StupyTpl::setXXX();
		//
		foreach ($options as $k => $v) {
		}
	}
}

class View extends \Phalcon\Mvc\View {
	protected $_pick;
	protected $_store_pick;
	public function pick($pick) {
		$this->_pick = $pick;
		return parent::pick($pick);
	}
	public function storePick() {
		if ($this->_pick)
			$this->_store_pick = $this->_pick;
	}
	public function restorePick() {
		if ($this->_store_pick) {
			$this->_pick = $this->_store_pick;
			parent::pick($this->_store_pick);
		}
	}
	public function start() {}
	public function finish() {}
}

<?php

namespace Stupycart\Frontend\Controllers\Error;

class IndexController extends \Stupycart\Frontend\Controllers\ControllerBase
{
    public function indexAction()
    {
		die('error index');
    }

    public function show404Action()
    {
		$this->response->setStatusCode(404, "Not Found");
		return $this->response;
	}
}

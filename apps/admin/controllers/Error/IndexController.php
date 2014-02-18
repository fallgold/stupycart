<?php

namespace Stupycart\Admin\Controllers\Error;

class IndexController extends \Stupycart\Admin\Controllers\ControllerBase
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

<?php

namespace Libs\Opencart;

class Url01 extends \Phalcon\DI\Injectable {
	private $url;
	private $ssl;
	private $rewrite = array();
	
	public function __construct($url, $ssl = '', $moduleName = '') {
		$this->url = $url;
		$this->ssl = $ssl;
		if ($moduleName) {
			$this->url .= ($moduleName. '/');
			$this->ssl .= ($moduleName. '/');
		}
	}
		
	public function getBaseUrl() {
		return $this->url;
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}
		
	public function link($route, $args = '', $connection = 'NONSSL') {
		if ($connection ==  'NONSSL') {
			$url = $this->url;
		} else {
			$url = $this->ssl;	
		}
		
		$url .= $route;
			
		if ($args) {
			$url .= str_replace('&', '&amp;', '?' . ltrim($args, '&?')); 
		}
		
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
				
		return $url;
	}
}
?>

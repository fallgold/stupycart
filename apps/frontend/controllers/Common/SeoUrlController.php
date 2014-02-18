<?php

namespace Stupycart\Frontend\Controllers\Common;

class SeoUrlController extends \Stupycart\Frontend\Controllers\ControllerBase {
	public function indexAction() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}
		
		// Decode URL
		if ($this->request->hasQuery('_route_')) {
			$parts = explode('/', $this->request->getQueryE('_route_'));
			
			foreach ($parts as $part) {
				$query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->ocdb->db_escape($part) . "'");
				
				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);
					
					if ($url[0] == 'product_id') {
						$this->request->getQueryE('product_id') = $url[1];
					}
					
					if ($url[0] == 'category_id') {
						if (!$this->request->hasQuery('path')) {
							$this->request->getQueryE('path') = $url[1];
						} else {
							$this->request->getQueryE('path') .= '_' . $url[1];
						}
					}	
					
					if ($url[0] == 'manufacturer_id') {
						$this->request->getQueryE('manufacturer_id') = $url[1];
					}
					
					if ($url[0] == 'information_id') {
						$this->request->getQueryE('information_id') = $url[1];
					}	
				} else {
					$this->request->getQueryE('route') = 'error/not_found';	
				}
			}
			
			if ($this->request->hasQuery('product_id')) {
				$this->request->getQueryE('route') = 'product/product';
			} elseif ($this->request->hasQuery('path')) {
				$this->request->getQueryE('route') = 'product/category';
			} elseif ($this->request->hasQuery('manufacturer_id')) {
				$this->request->getQueryE('route') = 'product/manufacturer/info';
			} elseif ($this->request->hasQuery('information_id')) {
				$this->request->getQueryE('route') = 'information/information';
			}
			
			if ($this->request->hasQuery('route')) {
				return $this->forward($this->request->getQueryE('route'));
			}
		}
	}
	
	public function rewriteAction($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));
	
		$url = ''; 
		
		$data = array();
		
		parse_str($url_info['query'], $data);
		
		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
					$query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->ocdb->db_escape($key . '=' . (int)$value) . "'");
				
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						
						unset($data[$key]);
					}					
				} elseif ($key == 'path') {
					$categories = explode('_', $value);
					
					foreach ($categories as $category) {
						$query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
				
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
						}							
					}
					
					unset($data[$key]);
				}
			}
		}
	
		if ($url) {
			unset($data['route']);
		
			$query = '';
		
			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . $key . '=' . $value;
				}
				
				if ($query) {
					$query = '?' . trim($query, '&');
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}	
}
?>
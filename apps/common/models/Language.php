<?php

namespace Stupycart\Common\Models;

class Language extends \Libs\Stupy\Model {
    public function getLanguages() {
		$languages = array();
		$query = $this->db_query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'"); 
		foreach ($query->rows as $result) {
			$languages[$result['code']] = $result;
		}
		return $languages;
	}

	// Language Detection
    public function detect($languages, &$config) {
		$detect = '';
		if (($http_accept_language = $this->request->getServer('HTTP_ACCEPT_LANGUAGE'))) {
			$browser_languages = explode(',', $http_accept_language);
			
			foreach ($browser_languages as $browser_language) {
				foreach ($languages as $key => $value) {
					if ($value['status']) {
						$locale = explode(',', $value['locale']);

						if (in_array($browser_language, $locale)) {
							$detect = $key;
						}
					}
				}
			}
		}

		$cookie_language = $this->cookies->has('language') ? $this->cookies->get('language')->getValue() : '';
		$session_language = $this->session->has('language') ? $this->session->get('language') : '';
		// phalcon bug, string length error: 
		// string(32) "zh-CN" 
		// string(5) "zh-CN"
		//var_dump($cookie_language, 'zh-CN');exit; 
		if ($session_language != '' && array_key_exists($session_language, $languages) && $languages[$session_language]['status']) {
			$code = $session_language;
		} elseif ($cookie_language != '' && array_key_exists($cookie_language, $languages) && $languages[$cookie_language]['status']) {
			$code = $cookie_language;
		} elseif ($detect) {
			$code = $detect;
		} else {
			$code = $config->get('config_language');
		}

		if ($session_language == '' || $session_language != $code) {	  
			$this->session->set('language', $code);
		}

		if ($cookie_language == '' || $cookie_language != $code) {	  
			$this->cookies->set('language', $code, time() + 60 * 60 * 24 * 30, '/');
		}

		$config->set('config_language_id', $languages[$code]['language_id']);
		$config->set('config_language', $languages[$code]['code']);

		return $code;
	}
}

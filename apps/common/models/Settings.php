<?php

namespace Stupycart\Common\Models;

class Settings extends \Libs\Stupy\Model {
    public function initStoreConfigs(&$config, $store_id) {
		$config->set('config_store_id', $store_id);

		$settings = $this->db_query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$store_id . "' ORDER BY store_id ASC");
		foreach ($settings->rows as $setting) {
			if (!$setting['serialized']) {
				$config->set($setting['key'], $setting['value']);
			} else {
				$config->set($setting['key'], unserialize($setting['value']));
			}
		}

		//if (!$config->get('config_url') && defined('HTTP_SERVER'))
			//$config->set('config_url', HTTP_SERVER);
		//if (!$config->get('config_ssl') && defined('HTTPS_SERVER'))
			//$config->set('config_ssl', HTTPS_SERVER);	
		if (defined('HTTP_SERVER'))
			$config->set('config_url', HTTP_SERVER);
		if (defined('HTTPS_SERVER'))
			$config->set('config_ssl', HTTPS_SERVER);	
    }
}

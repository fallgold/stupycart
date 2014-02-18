<?php 

namespace Stupycart\Common\Models\Admin\Setting;

class Setting  extends \Libs\Stupy\Model {
	public function getSetting($group, $store_id = 0) {
		$data = array(); 
		
		$query = $this->db_query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db_escape($group) . "'");
		
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}
	
	public function editSetting($group, $data, $store_id = 0) {
		$this->db_query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db_escape($group) . "'");

		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$this->db_query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db_escape($group) . "', `key` = '" . $this->db_escape($key) . "', `value` = '" . $this->db_escape($value) . "'");
			} else {
				$this->db_query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db_escape($group) . "', `key` = '" . $this->db_escape($key) . "', `value` = '" . $this->db_escape(serialize($value)) . "', serialized = '1'");
			}
		}
	}
	
	public function deleteSetting($group, $store_id = 0) {
		$this->db_query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db_escape($group) . "'");
	}
	
	public function editSettingValue($group = '', $key = '', $value = '', $store_id = 0) {
		if (!is_array($value)) {
			$this->db_query("UDPATE " . DB_PREFIX . "setting SET `value` = '" . $this->db_escape($value) . " WHERE `group` = '" . $this->db_escape($group) . "' AND `key` = '" . $this->db_escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		} else {
			$this->db_query("UDPATE " . DB_PREFIX . "setting SET `value` = '" . $this->db_escape(serialize($value)) . "' WHERE `group` = '" . $this->db_escape($group) . "' AND `key` = '" . $this->db_escape($key) . "' AND store_id = '" . (int)$store_id . "', serialized = '1'");
		}
	}	
}
?>
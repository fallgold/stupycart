<?php 

namespace Stupycart\Common\Models\Setting;

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
}
?>
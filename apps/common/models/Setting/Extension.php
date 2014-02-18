<?php

namespace Stupycart\Common\Models\Setting;

class Extension  extends \Libs\Stupy\Model {
	function getExtensions($type) {
		$query = $this->db_query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db_escape($type) . "'");

		return $query->rows;
	}
}
?>
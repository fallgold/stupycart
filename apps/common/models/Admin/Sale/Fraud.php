<?php

namespace Stupycart\Common\Models\Admin\Sale;

class Fraud  extends \Libs\Stupy\Model {
	public function getFraud($order_id) {
		$query = $this->db_query("SELECT * FROM `" . DB_PREFIX . "order_fraud` WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->row;
	}
}
?>
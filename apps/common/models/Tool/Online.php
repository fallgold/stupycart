<?php

namespace Stupycart\Common\Models\Tool;

class Online  extends \Libs\Stupy\Model {	
	public function whosonline($ip, $customer_id, $url, $referer) {
		$this->db_query("DELETE FROM `" . DB_PREFIX . "customer_online` WHERE (UNIX_TIMESTAMP(`date_added`) + 3600) < UNIX_TIMESTAMP(NOW())");
		 
		$this->db_query("REPLACE INTO `" . DB_PREFIX . "customer_online` SET `ip` = '" . $this->db_escape($ip) . "', `customer_id` = '" . (int)$customer_id . "', `url` = '" . $this->db_escape($url) . "', `referer` = '" . $this->db_escape($referer) . "', `date_added` = NOW()");
	}
}
?>
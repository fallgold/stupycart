<?php

namespace Stupycart\Common\Models\Admin\Localisation;

class LengthClass  extends \Libs\Stupy\Model {
	public function addLengthClass($data) {
		$this->db_query("INSERT INTO " . DB_PREFIX . "length_class SET value = '" . (float)$data['value'] . "'");

		$length_class_id = $this->db_getLastId();
		
		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->db_query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db_escape($value['title']) . "', unit = '" . $this->db_escape($value['unit']) . "'");
		}
		
		$this->cache->delete('length_class');
	}
	
	public function editLengthClass($length_class_id, $data) {
		$this->db_query("UPDATE " . DB_PREFIX . "length_class SET value = '" . (float)$data['value'] . "' WHERE length_class_id = '" . (int)$length_class_id . "'");
		
		$this->db_query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE length_class_id = '" . (int)$length_class_id . "'");

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->db_query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db_escape($value['title']) . "', unit = '" . $this->db_escape($value['unit']) . "'");
		}
		
		$this->cache->delete('length_class');	
	}
	
	public function deleteLengthClass($length_class_id) {
		$this->db_query("DELETE FROM " . DB_PREFIX . "length_class WHERE length_class_id = '" . (int)$length_class_id . "'");
		$this->db_query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE length_class_id = '" . (int)$length_class_id . "'");	
		
		$this->cache->delete('length_class');
	}
	
	public function getLengthClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'title',
				'unit',
				'value'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY title";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db_query($sql);
	
			return $query->rows;			
		} else {
			$length_class_data = $this->cache->get('length_class.' . (int)$this->config->get('config_language_id'));

			if (!$length_class_data) {
				$query = $this->db_query("SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				$length_class_data = $query->rows;
			
				$this->cache->set('length_class.' . (int)$this->config->get('config_language_id'), $length_class_data);
			}
			
			return $length_class_data;
		}
	}
		
	public function getLengthClass($length_class_id) {
		$query = $this->db_query("SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lc.length_class_id = '" . (int)$length_class_id . "' AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}

	public function getLengthClassDescriptionByUnit($unit) {
		$query = $this->db_query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE unit = '" . $this->db_escape($unit) . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getLengthClassDescriptions($length_class_id) {
		$length_class_data = array();
		
		$query = $this->db_query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE length_class_id = '" . (int)$length_class_id . "'");
				
		foreach ($query->rows as $result) {
			$length_class_data[$result['language_id']] = array(
				'title' => $result['title'],
				'unit'  => $result['unit']
			);
		}
		
		return $length_class_data;
	}
			
	public function getTotalLengthClasses() {
      	$query = $this->db_query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "length_class");
		
		return $query->row['total'];
	}		
}
?>
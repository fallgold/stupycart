<?php

namespace Stupycart\Common\Models\Admin\Design;

class Banner  extends \Libs\Stupy\Model {
	public function addBanner($data) {
		$this->db_query("INSERT INTO " . DB_PREFIX . "banner SET name = '" . $this->db_escape($data['name']) . "', status = '" . (int)$data['status'] . "'");
	
		$banner_id = $this->db_getLastId();
	
		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				$this->db_query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" .  $this->db_escape($banner_image['link']) . "', image = '" .  $this->db_escape($banner_image['image']) . "'");
				
				$banner_image_id = $this->db_getLastId();
				
				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {				
					$this->db_query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '" . (int)$banner_id . "', title = '" .  $this->db_escape($banner_image_description['title']) . "'");
				}
			}
		}		
	}
	
	public function editBanner($banner_id, $data) {
		$this->db_query("UPDATE " . DB_PREFIX . "banner SET name = '" . $this->db_escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE banner_id = '" . (int)$banner_id . "'");

		$this->db_query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db_query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");
			
		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				$this->db_query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" .  $this->db_escape($banner_image['link']) . "', image = '" .  $this->db_escape($banner_image['image']) . "'");
				
				$banner_image_id = $this->db_getLastId();
				
				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {				
					$this->db_query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '" . (int)$banner_id . "', title = '" .  $this->db_escape($banner_image_description['title']) . "'");
				}
			}
		}			
	}
	
	public function deleteBanner($banner_id) {
		$this->db_query("DELETE FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db_query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db_query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");
	}
	
	public function getBanner($banner_id) {
		$query = $this->db_query("SELECT DISTINCT * FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
		
		return $query->row;
	}
		
	public function getBanners($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "banner";
		
		$sort_data = array(
			'name',
			'status'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
	}
		
	public function getBannerImages($banner_id) {
		$banner_image_data = array();
		
		$banner_image_query = $this->db_query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		
		foreach ($banner_image_query->rows as $banner_image) {
			$banner_image_description_data = array();
			 
			$banner_image_description_query = $this->db_query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "' AND banner_id = '" . (int)$banner_id . "'");
			
			foreach ($banner_image_description_query->rows as $banner_image_description) {			
				$banner_image_description_data[$banner_image_description['language_id']] = array('title' => $banner_image_description['title']);
			}
		
			$banner_image_data[] = array(
				'banner_image_description' => $banner_image_description_data,
				'link'                     => $banner_image['link'],
				'image'                    => $banner_image['image']	
			);
		}
		
		return $banner_image_data;
	}
		
	public function getTotalBanners() {
      	$query = $this->db_query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "banner");
		
		return $query->row['total'];
	}	
}
?>
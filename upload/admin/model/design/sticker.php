<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelDesignSticker extends Model {
	public function addSticker($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sticker SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "'");

		$sticker_id = $this->db->getLastId();

	if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sticker SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE sticker_id = '" . (int)$sticker_id . "'");
		}
		
	}

	public function editSticker($sticker_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sticker SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE sticker_id = '" . (int)$sticker_id . "'");	
	
	if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sticker SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE sticker_id = '" . (int)$sticker_id . "'");
		}
	
	}

	public function deleteSticker($sticker_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sticker WHERE sticker_id = '" . (int)$sticker_id . "'");
	}

	public function getSticker($sticker_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "sticker WHERE sticker_id = '" . (int)$sticker_id . "'");

		return $query->row;
	}

	public function getStickers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "sticker";

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

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getStickersProduct($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "sticker";
			
		$query = $this->db->query($sql);
	
		return $query->rows;
			
	}

	public function getProductSticker($product_id) {
		$stickers = array();

		$query = $this->db->query("SELECT sticker_id, position FROM " . DB_PREFIX . "product_to_sticker WHERE product_id = '" . (int)$product_id . "' GROUP BY position");
		
		foreach ($query->rows as $sticker) {
			$stickers[$sticker['position']] = $sticker['sticker_id'];
		}

		return $stickers;
	}

	public function getTotalStickers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sticker");

		return $query->row['total'];
	}	
	
	public function validateDelete($selected) {
	
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_sticker WHERE sticker_id IN (". $selected .")");

		return $query->row['total'];
	}
}
?>
<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelToolSeoManager extends Model {

	public function deleteUrlAlias($url_alias_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `url_alias_id` = '" . (int)$url_alias_id . "'");
		$this->cache->delete('seo_pro');
		$this->cache->delete('seo_url');
	}

	public function updateUrlAlias($data) {
		if($data['query'] == '') return false;
		if($data['url_alias_id'] != 0 ) {
			$this->db->query("UPDATE `" . DB_PREFIX . "url_alias` SET `query` = '" . $this->db->escape($data['query']) . "', `keyword` = '" . $data['keyword'] . "' WHERE `url_alias_id` = '" . (int)$data['url_alias_id'] . "'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET 
				`query` = '" .  $this->db->escape($data['query']) . "', 
				`keyword` = '" . $this->db->escape($data['keyword']) . "',
				`seomanager` = 1");
		}
		$this->cache->delete('seo_pro');
		$this->cache->delete('seo_url');
		return true;
	}
	
	// Get List URL Alias
	public function getUrlAaliases($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "url_alias` ua WHERE ua.seomanager = '1'";

			$sort_data = array('ua.query', 'ua.keyword');

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY ua.query";
			}

			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
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

			return  $query->rows;
		} else {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` ua WHERE ua.seomanager = '1' ORDER BY ua.query");
			return $query->rows;
		}
	}

	// Total Aliases
	public function getTotalUrlAalias() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "url_alias` WHERE `seomanager` = '1';");
		return $query->row['total'];
	}

}
?>
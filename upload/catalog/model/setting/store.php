<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelSettingStore extends Model {
	public function getStores($data = array()) {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

			$store_data = $query->rows;

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}
}
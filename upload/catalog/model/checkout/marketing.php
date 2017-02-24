<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelCheckoutMarketing extends Model {
	public function getMarketingByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "marketing WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
}
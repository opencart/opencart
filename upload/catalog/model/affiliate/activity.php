<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelAffiliateActivity extends Model {
	public function addActivity($key, $data) {
		if (isset($data['affiliate_id'])) {
			$affiliate_id = $data['affiliate_id'];
		} else {
			$affiliate_id = 0;
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_activity SET `affiliate_id` = '" . (int)$affiliate_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
}
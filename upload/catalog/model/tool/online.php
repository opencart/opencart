<?php
class ModelToolOnline extends Model {
	public function whosonline($ip, $customer_id, $url, $referer) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_online` WHERE date_added < '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "'");

		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_online` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		if (!$query->row['total']) {
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_online` SET `ip` = '" . $this->db->escape($ip) . "', `customer_id` = '" . (int)$customer_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `date_added` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		}
	}
}
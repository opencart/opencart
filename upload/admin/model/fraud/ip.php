<?php
class ModelFraudIp extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ip` (
			  `ip` varchar(40) NOT NULL,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`ip`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ip`");
	}

    public function addIp($ip) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "fraud_ip` SET `ip` = '" . $this->db->escape($ip) . "', date_added = NOW()");
    }

    public function removeIp($ip) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "fraud_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
    }

	public function getCustomerBanIps($data = array()) {
		$sql = "SELECT *, (SELECT COUNT(DISTINCT customer_id) FROM `" . DB_PREFIX . "customer_ip` ci WHERE ci.ip = cbi.ip) AS total FROM `" . DB_PREFIX . "customer_ban_ip` cbi";

		$sql .= " ORDER BY `ip`";

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

	public function getTotalCustomerBanIps($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_ban_ip`");

		return $query->row['total'];
	}

	public function getTotalBanIpsByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}
}

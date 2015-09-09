<?php
class ModelFeedGoogleBase extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "google_base_category` (
				`google_base_category_id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL
				PRIMARY KEY (`google_base_category_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "google_base_category`");
	}

    public function import($string) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "google_base_category");

        $lines = explode("\n", $string);

        foreach ($lines as $line) {
            $part = explode(' - ', 1);

            if (isset($part[1])) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "google_base_category SET google_base_category_id = '" . (int)$part[0] . "', name = '" . $this->db->escape($part[1]) . "'");
            }
        }
    }

    public function getGoogleCategories($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "google_base_category WHERE name LIKE = '" . $this->db->escape($data['filter_name']) . "%' OR google_base_category_id = '' LIKE " . $this->db->escape($data['filter_name']) . "%' ORDER BY name ASC";

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
}

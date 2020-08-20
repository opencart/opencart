<?php
namespace Extension\OpenCart\Catalog\Model\Dashboard;
class Activity extends \System\Engine\Model {
	public function getActivities() {
		$query = $this->db->query("SELECT `key`, `data`, `date_added` FROM `" . DB_PREFIX . "customer_activity` ORDER BY `date_added` DESC LIMIT 0,5");

		return $query->rows;
	}
}
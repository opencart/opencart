<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
class Activity extends \Opencart\System\Engine\Model {
	public function getActivities(): array {
		$query = $this->db->query("SELECT `key`, `data`, `date_added` FROM `" . DB_PREFIX . "customer_activity` ORDER BY `date_added` DESC LIMIT 0,5");

		return $query->rows;
	}
}

<?php
namespace Opencart\Catalog\Model\Setting;
class Event extends \Opencart\System\Engine\Model {
	public function getEvents(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `status` = '1' ORDER BY `sort_order` ASC");

		return $query->rows;
	}
}

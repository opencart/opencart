<?php
namespace Opencart\Application\Model\Setting;
class Event extends \Opencart\System\Engine\Model {
	function getEvents() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'catalog/%' AND `status` = '1' ORDER BY `sort_order` ASC");

		return $query->rows;
	}
}
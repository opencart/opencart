<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Event
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Event extends \Opencart\System\Engine\Model {
	/**
	 * @return array
	 */
	public function getEvents(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `status` = '1' ORDER BY `sort_order` ASC");

		return $query->rows;
	}
}

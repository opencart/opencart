<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Event
 *
 * @example $event_model = $this->model_setting_event;
 *
 * Can be called from $this->load->model('setting/event');
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Event extends \Opencart\System\Engine\Model {
	/**
	 * Get Events
	 *
	 * @return array<int, array<string, mixed>> event records
	 */
	public function getEvents(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `status` = '1' ORDER BY `sort_order` ASC");

		return $query->rows;
	}
}

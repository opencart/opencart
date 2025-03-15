<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Event
 *
 * Can be loaded using $this->load->model('setting/event');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Event extends \Opencart\System\Engine\Model {
	/**
	 * Add Event
	 *
	 * Create a new event record in the database.
	 *
	 * @param array<string, mixed> $data array of of data
	 *
	 * @return int returns the primary key of the new event record
	 *
	 * @example
	 *
	 * $event_data = [
	 *     'code'        => 'Event Code',
	 *     'description' => 'Event Description',
	 *     'trigger'     => 'Event Trigger',
	 *     'action'      => 'Event Action',
	 *     'status'      => 0,
	 *     'sort_order'  => 0
	 * ];
	 *
	 * $this->load->model('setting/event');
	 *
	 * $event_id = $this->model_setting_event->addEvent($event_data);
	 */
	public function addEvent(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($data['code']) . "', `description` = '" . $this->db->escape($data['description']) . "', `trigger` = '" . $this->db->escape($data['trigger']) . "', `action` = '" . $this->db->escape($data['action']) . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		return $this->db->getLastId();
	}

	/**
	 * Delete Event
	 *
	 * Delete event record in the database.
	 *
	 * @param int $event_id primary key of the event record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/event');
	 *
	 * $this->model_setting_event->deleteEvent($event_id);
	 */
	public function deleteEvent(int $event_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . (int)$event_id . "'");
	}

	/**
	 * Delete Event By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/event');
	 *
	 * $this->model_setting_event->deleteEventByCode($code);
	 */
	public function deleteEventByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Edit Status
	 *
	 * Edit event status record in the database.
	 *
	 * @param int  $event_id primary key of the event record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/event');
	 *
	 * $this->model_setting_event->editStatus($event_id, $status);
	 */
	public function editStatus(int $event_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `status` = '" . (bool)$status . "' WHERE `event_id` = '" . (int)$event_id . "'");
	}

	/**
	 * Edit Status By Code
	 *
	 * @param string $code
	 * @param bool   $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/event');
	 *
	 * $this->model_setting_event->editStatusByCode($code, $status);
	 */
	public function editStatusByCode(string $code, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `status` = '" . (bool)$status . "' WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Get Event
	 *
	 * Get the record of the event record in the database.
	 *
	 * @param int $event_id primary key of the event record
	 *
	 * @return array<string, mixed> event record that has event ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/event');
	 *
	 * $event_info = $this->model_setting_event->getEvent($event_id);
	 */
	public function getEvent(int $event_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . (int)$event_id . "'");

		return $query->row;
	}

	/**
	 * Get Event By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/event');
	 *
	 * $event_info = $this->model_setting_event->getEventByCode($code);
	 */
	public function getEventByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * Get Events
	 *
	 * Get the record of the event records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> event records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'code',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('setting/event');
	 *
	 * $results = $this->model_setting_event->getEvents($filter_data);
	 */
	public function getEvents(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "event`";

		$sort_data = [
			'code',
			'trigger',
			'action',
			'sort_order',
			'status',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `sort_order`";
		}

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

	/**
	 * Get Total Events
	 *
	 * Get the total number of total event records in the database.
	 *
	 * @return int total number of event records
	 *
	 * @example
	 *
	 * $this->load->model('setting/event');
	 *
	 * $event_total = $this->model_setting_event->getTotalEvents();
	 */
	public function getTotalEvents(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "event`");

		return (int)$query->row['total'];
	}
}

<?php
class ModelExtensionEvent extends Model {
	public function addEvent($code, $trigger, $action, $status = 1) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($code) . "', `trigger` = '" . $this->db->escape($trigger) . "', `action` = '" . $this->db->escape($action) . "', `status` = '" . (int)$status . "', `date_added` = now()");
	
		return $this->db->getLastId();
	}

	public function deleteEvent($code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "'");
	}
	
	public function getEvent($code, $trigger, $action) {
		$event = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "' AND `trigger` = '" . $this->db->escape($trigger) . "' AND `action` = '" . $this->db->escape($action) . "'");
		
		return $event->rows;
	}

	public function enableEvent($event_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "event SET `status` = '1' WHERE event_id = '" . (int)$event_id . "'");
	}
	
	public function disableEvent($event_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "event SET `status` = '0' WHERE event_id = '" . (int)$event_id . "'");
	}
	
	public function uninstall($type, $code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "'");
	}
		
	public function getEvents($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "event`";

		$sort_data = array(
			'code',
			'trigger',
			'action',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `code`";
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

	public function getTotalEvents() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "event");

		return $query->row['total'];
	}
	
}

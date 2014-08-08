<?php
class ModelToolEvent extends Model {
	public function getHandlers($event, $store_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event WHERE store_id = '" . (int)$store_id . "' AND event = '" . $this->db->escape($event) . "'");

		return !empty($query->row['handlers']) ? unserialize($query->row['handlers']) : array();
	}

	public function setHandler($event, $handler, $store_id = 0) {
		if (empty($handler['type']) || empty($handler['code']) || empty($handler['method'])) {
			return false;
		}

		$handler = $handler['type'] . '/' . $handler['code'] . '/' . $handler['method'];

		$handlers = $this->getHandlers($event, $store_id);
		$handlers[] = $handler;

		$this->db->query("DELETE FROM " . DB_PREFIX . "event WHERE store_id = '" . (int)$store_id . "' AND event = '" . $this->db->escape($event) . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "event SET store_id = '" . (int)$store_id . "', event = '" . $this->db->escape($event) . "', handlers = '" . $this->db->escape(serialize($handlers)) . "'");

		return true;
	}

	public function removeHandler($event, $handler, $store_id = 0) {
		if (empty($handler['type']) || empty($handler['code']) || empty($handler['method'])) {
			return false;
		}

		$handler = $handler['type'] . '/' . $handler['code'] . '/' . $handler['method'];

		$handlers = $this->getHandlers($event, $store_id);
		$key = array_search($handler, $handlers);

		if ($key === false) {
			return true;
		}

		unset($handlers[$key]);

		$this->db->query("DELETE FROM " . DB_PREFIX . "event WHERE store_id = '" . (int)$store_id . "' AND event = '" . $this->db->escape($event) . "'");

		if (!empty($handlers)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "event SET store_id = '" . (int)$store_id . "', event = '" . $this->db->escape($event) . "', handlers = '" . $this->db->escape(serialize($handlers)) . "'");
		}

		return true;
	}
}

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

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event WHERE store_id = '" . (int)$store_id . "' AND event = '" . $this->db->escape($event) . "'");

		$handlers = !empty($query->row['handlers']) ? unserialize($query->row['handlers']) : array();
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

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event WHERE store_id = '" . (int)$store_id . "' AND event = '" . $this->db->escape($event) . "'");

		$handlers = !empty($query->row['handlers']) ? unserialize($query->row['handlers']) : array();
		if (!in_array($handler, $handlers)) {
			return true;
		}

		$key = array_search($handler, $handlers);
		unset($handlers[$key]);

		$this->db->query("DELETE FROM " . DB_PREFIX . "event WHERE store_id = '" . (int)$store_id . "' AND event = '" . $this->db->escape($event) . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "event SET store_id = '" . (int)$store_id . "', event = '" . $this->db->escape($event) . "', handlers = '" . $this->db->escape(serialize($handlers)) . "'");

		return true;
	}
}
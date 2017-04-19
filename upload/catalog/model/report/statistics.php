<?php
class ModelSettingStatistics extends Model {
	public function getStatistics() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "statistics");

		return $query->rows;
	}
	
	public function getStatisticValue($code) {
		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "'");

		if ($query->num_rows) {
			return $query->row['value'];
		} else {
			return null;	
		}
	}
	
	public function addStatisticValue($code, $value) {
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = (`value` + '" . $this->db->escape($value) . "') WHERE `code` = '" . $this->db->escape($code) . "'");
	}
	
	public function removeStatisticValue($code, $value) {
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = (`value` - '" . $this->db->escape($value) . "') WHERE `code` = '" . $this->db->escape($code) . "'");
	}	
	
	public function editStatisticValue($code, $value) {
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "' WHERE `code` = '" . $this->db->escape($code) . "'");
	}	
}
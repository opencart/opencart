<?php
class ModelExtensionExtension extends Model {
	public function addPath($extension_download_id, $path) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "extension_download SET `extension_download_id` = '" . $this->db->escape($extension_download_id) . "', `path` = '" . $this->db->escape($path) . "', date_added = NOW()");
	}
		
	public function deletePath($extension_download_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension_download WHERE `extension_download_id` = '" . $this->db->escape($extension_download_id) . "'");
	}
	
	public function getPaths($extension_download_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension_log WHERE `extension_download_id` = '" . $this->db->escape($extension_download_id) . "' ORDER BY date_added ASC");

		return $query->rows;
	}
			
	public function getPathsByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension_log WHERE `extension_download_id` = '" . $this->db->escape($extension_download_id) . "' ORDER BY date_added ASC");

		return $query->rows;
	}	
	
	public function getInstalled($type) {
		$extension_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY code");

		foreach ($query->rows as $result) {
			$extension_data[] = $result['code'];
		}

		return $extension_data;
	}

	public function install($type, $code) {
		$extensions = $this->getInstalled($type);

		if (!in_array($code, $extensions)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
		}
	}

	public function uninstall($type, $code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "'");
	}
}

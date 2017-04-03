<?php
class ModelSettingExtension extends Model {	
	public function getInstalled($type) {
		$extension_data = array();

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY `code`");

		foreach ($query->rows as $result) {
			$extension_data[] = $result['code'];
		}

		return $extension_data;
	}

	public function install($type, $code) {
		$extensions = $this->getInstalled($type);

		if (!in_array($code, $extensions)) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
		}
	}

	public function uninstall($type, $code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $this->db->escape($type . '_' . $code) . "'");
	}	

	public function addUpload($filename) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "extension_upload` SET `filename` = '" . $this->db->escape($filename) . "', `date_added` = NOW()");
	
		return $this->db->getLastId();
	}
	
	public function deleteUpload($extension_upload_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension_upload` WHERE `extension_upload_id` = '" . $this->db->escape($extension_upload_id) . "'");
	}

	public function getUploads() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_upload` ORDER BY date_added ASC");
	
		return $query->rows;
	}
	
	public function addPath($code, $path) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "extension_install` SET `code` = '" . $this->db->escape($code) . "', `path` = '" . $this->db->escape($path) . "', `date_added` = NOW()");
	}
		
	public function deletePath($extension_install_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension_install` WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");
	}
	
	public function getPathsByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `date_added` ASC");

		return $query->rows;
	}
	
	public function getTotalPathsByCode($code) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "extension_install` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row['total'];
	}
	
	public function deletePathsByCode($code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension_install` WHERE `code` = '" . $this->db->escape($code) . "'");
	}	
}
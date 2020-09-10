<?php
namespace Opencart\Application\Model\Setting;
class Extension extends \Opencart\System\Engine\Model {
	public function getExtensionsByType($type) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY `code` ASC");

		return $query->rows;
	}

	public function install($type, $extension, $code) {
		$extensions = $this->getExtensionsByType($type);

		$codes = array_column($extensions, 'code');

		if (!in_array($code, $codes)) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = '" . $this->db->escape($extension) . "', `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
		}
	}

	public function uninstall($type, $code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $this->db->escape($type . '_' . $code) . "'");
	}

	public function addInstall($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "extension_install` SET `extension_id` = '" . (int)$data['extension_id'] . "', `extension_download_id` = '" . (int)$data['extension_download_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($data['code']) . "', `version` = '" . $this->db->escape($data['version']) . "', `image` = '" . $this->db->escape($data['image']) . "', `author` = '" . $this->db->escape($data['author']) . "', `link` = '" . $this->db->escape($data['link']) . "', `status` = '0', `date_added` = NOW()");
	
		return $this->db->getLastId();
	}

	public function deleteInstall($extension_install_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension_install` WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");
	}

	public function editStatus($extension_install_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "extension_install` SET `status` = '" . (int)$status . "' WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");
	}

	public function getInstall($extension_install_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");

		return $query->row;
	}

	public function getInstallByExtensionDownloadId($extension_download_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `extension_download_id` = '" . (int)$extension_download_id . "'");

		return $query->row;
	}

	public function getInstallByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getInstalls($data = []) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "extension_install`";

		if (!empty($data['filter_extension_download_id'])) {
			$sql .= " WHERE `extension_download_id` = '" . (int)$data['filter_extension_download_id'] . "'";
		}

		$sort_data = [
			'name',
			'version',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `date_added`";
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

	public function getTotalInstalls($data = []) {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "extension_install`";

		if (!empty($data['filter_extension_download_id'])) {
			$sql .= " WHERE `extension_download_id` = '" . (int)$data['filter_extension_download_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function addPath($extension_install_id, $path) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "extension_path` SET `extension_install_id` = '" . (int)$extension_install_id . "', `path` = '" . $this->db->escape($path) . "'");
	}
		
	public function deletePath($extension_path_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension_path` WHERE `extension_path_id` = '" . (int)$extension_path_id . "'");
	}
	
	public function getPathsByExtensionInstallId($extension_install_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_path` WHERE `extension_install_id` = '" . (int)$extension_install_id . "' ORDER BY `extension_path_id` ASC");

		return $query->rows;
	}

	public function getPaths($path) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_path` WHERE `path` LIKE '" . $this->db->escape($path) . "' ORDER BY `path` ASC");

		return $query->rows;
	}

	public function getTotalPaths($path) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_path` WHERE `path` LIKE '" . $this->db->escape($path) . "'");

		return $query->rows;
	}
}
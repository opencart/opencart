<?php
namespace Opencart\Admin\Model\Setting;
class Extension extends \Opencart\System\Engine\Model {
	public function getExtensions(): array {
		$query = $this->db->query("SELECT DISTINCT `extension` FROM `" . DB_PREFIX . "extension`");

		return $query->rows;
	}

	public function getExtensionsByType(string $type): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY `code` ASC");

		return $query->rows;
	}

	public function getExtensionByCode(string $type, string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getTotalExtensionsByExtension(string $extension): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "extension` WHERE `extension` = '" . $this->db->escape($extension) . "'");

		return (int)$query->row['total'];
	}

	public function install(string $type, string $extension, string $code): void {
		$extensions = $this->getExtensionsByType($type);

		$codes = array_column($extensions, 'code');

		if (!in_array($code, $codes)) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = '" . $this->db->escape($extension) . "', `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
		}
	}

	public function uninstall(string $type, string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $this->db->escape($type . '_' . $code) . "'");
	}

	public function addInstall(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "extension_install` SET `extension_id` = '" . (int)$data['extension_id'] . "', `extension_download_id` = '" . (int)$data['extension_download_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($data['code']) . "', `version` = '" . $this->db->escape($data['version']) . "', `author` = '" . $this->db->escape($data['author']) . "', `link` = '" . $this->db->escape($data['link']) . "', `status` = '0', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	public function deleteInstall(int $extension_install_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension_install` WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");
	}

	public function editStatus(int $extension_install_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "extension_install` SET `status` = '" . (bool)$status . "' WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");
	}

	public function getInstall(int $extension_install_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");

		return $query->row;
	}

	public function getInstallByExtensionDownloadId(int $extension_download_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `extension_download_id` = '" . (int)$extension_download_id . "'");

		return $query->row;
	}

	public function getInstallByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_install` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getInstalls(array $data = []): array {
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
			$sql .= " ORDER BY `" . $data['sort'] . "`";
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

	public function getTotalInstalls(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "extension_install`";

		if (!empty($data['filter_extension_download_id'])) {
			$sql .= " WHERE `extension_download_id` = '" . (int)$data['filter_extension_download_id'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	public function addPath(int $extension_install_id, string $path): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "extension_path` SET `extension_install_id` = '" . (int)$extension_install_id . "', `path` = '" . $this->db->escape($path) . "'");
	}

	public function deletePath(int $extension_path_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension_path` WHERE `extension_path_id` = '" . (int)$extension_path_id . "'");
	}

	public function getPathsByExtensionInstallId(int $extension_install_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_path` WHERE `extension_install_id` = '" . (int)$extension_install_id . "' ORDER BY `extension_path_id` ASC");

		return $query->rows;
	}

	public function getPaths(string $path): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension_path` WHERE `path` LIKE '" . $this->db->escape($path) . "' ORDER BY `path` ASC");

		return $query->rows;
	}

	public function getTotalPaths(string $path): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "extension_path` WHERE `path` LIKE '" . $this->db->escape($path) . "'");

		return (int)$query->row['total'];
	}
}

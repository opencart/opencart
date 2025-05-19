<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Extension
 *
 * Can be called using $this->load->model('setting/extension');
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Extension extends \Opencart\System\Engine\Model {
	/**
	 * Get Extensions
	 *
	 * Get the record of the extension records in the database.
	 *
	 * @return array<int, array<string, mixed>> extension records
	 *
	 * @example
	 *
	 * $this->load->model('setting/extension');
	 *
	 * $extensions = $this->model_setting_extension->getExtensions();
	 */
	public function getExtensions(): array {
		$query = $this->db->query("SELECT DISTINCT `extension` FROM `" . DB_PREFIX . "extension`");

		return $query->rows;
	}

	/**
	 * Get Extensions By Type
	 *
	 * @param string $type
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('setting/extension');
	 *
	 * $extensions = $this->model_setting_extension->getExtensionsByType($type);
	 */
	public function getExtensionsByType(string $type): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}

	/**
	 * Get Extension By Code
	 *
	 * @param string $type
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/extension');
	 *
	 * $extension_info = $this->model_setting_extension->getExtensionByCode($type, $code);
	 */
	public function getExtensionByCode(string $type, string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
}

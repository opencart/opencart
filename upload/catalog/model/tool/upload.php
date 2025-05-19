<?php
namespace Opencart\Catalog\Model\Tool;
/**
 * Class Upload
 *
 * Can be called using $this->load->model('tool/upload');
 *
 * @package Opencart\Catalog\Model\Tool
 */
class Upload extends \Opencart\System\Engine\Model {
	/**
	 * Add Upload
	 *
	 * Create a new upload record in the database.
	 *
	 * @param string $name
	 * @param string $filename
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $this->load->model('tool/upload');
	 *
	 * $this->model_tool_upload->addUpload($name, $filename);
	 */
	public function addUpload(string $name, string $filename): string {
		$code = oc_token(32);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', `date_added` = NOW()");

		return $code;
	}

	/**
	 * Get Upload By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('tool/upload');
	 *
	 * $upload_info = $this->model_tool_upload->getUploadByCode($code);
	 */
	public function getUploadByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
}

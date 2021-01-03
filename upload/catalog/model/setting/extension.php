<?php
namespace Opencart\Application\Model\Setting;
class Extension extends \Opencart\System\Engine\Model {
	protected $cacheKey = 'extensions';

	public function getExtensions() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension`");

		return $query->rows;
	}

	public function getExtensionsDistinctCached() {
		if (($res = $this->registry->get('cache')->get($this->cacheKey))) {
			return $res;
		} else {
			$query = $this->db->query("SELECT DISTINCT extension FROM `" . DB_PREFIX . "extension`");
			$this->registry->get('cache')->set($this->cacheKey, $results = $query->rows);

			return $results;
		}
	}

	public function getExtensionsByType($type) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}

	public function getExtensionByCode($type, $code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
}
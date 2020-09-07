<?php
namespace Opencart\Application\Model\Design;
class SeoUrl extends \Opencart\System\Engine\Model {
	public $keyword = [];

	public function getSeoUrlByKeyword($keyword) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `keyword` = '" . $this->db->escape($keyword) . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getKeywordByKeyValue($key, $value) {
		if (!isset($this->keyword[$key][$value])) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = '" . $this->db->escape($key) . "' AND `value` = '" . $this->db->escape($value) . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

			if ($query->num_rows) {
				$this->keyword[$key][$value] = $query->row['keyword'];
			} else {
				$this->keyword[$key][$value] = '';
			}
		}

		return $this->keyword[$key][$value];
	}
}
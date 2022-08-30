<?php
namespace Opencart\Catalog\Model\Design;
class SeoUrl extends \Opencart\System\Engine\Model {
	public function getSeoUrlByKeyword(string $keyword): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE (`keyword` = '" . $this->db->escape($keyword) . "' OR `keyword` LIKE '%/" . $this->db->escape($keyword) . "') AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getSeoUrlByKeyValue(string $key, string $value): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = '" . $this->db->escape($key) . "' AND `value` = '" . $this->db->escape($value) . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function listUniqueKeys(): array
	{
		$unique_keys = $this->config->get('seouniquekeys');

		if (!$unique_keys) {
			$query = $this->db->query("SELECT DISTINCT (`key`) FROM `" . DB_PREFIX . "seo_url`");
			$unique_keys = array();
			foreach ($query->rows as $value) {
				$unique_keys[] = $value['key'];
			}
			$this->config->set('seouniquekeys', $unique_keys);
		
		}
		return $unique_keys;
	}
}

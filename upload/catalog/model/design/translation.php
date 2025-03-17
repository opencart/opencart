<?php
namespace Opencart\Catalog\Model\Design;
/**
 * Class Translation
 *
 * Can be called using $this->load->model('design/translation');
 *
 * @package Opencart\Catalog\Model\Design
 */
class Translation extends \Opencart\System\Engine\Model {
	/**
	 * Get Translations
	 *
	 * Get the record of the translation records in the database.
	 *
	 * @param string $route
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('design/translation');
	 *
	 * $results = $this->model_design_translation->getTranslations($route);
	 */
	public function getTranslations(string $route): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "translation` WHERE `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `route` = '" . $this->db->escape($route) . "'");

		return $query->rows;
	}
}

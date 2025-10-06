<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Translation
 *
 * Can be loaded using $this->load->model('design/translation');
 *
 * @package Opencart\Admin\Model\Design
 */
class Translation extends \Opencart\System\Engine\Model {
	/**
	 * Add Translation
	 *
	 * Create a new translation record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $translation_data = [
	 *     'store_id'    => 1,
	 *     'language_id' => 1,
	 *     'route'       => '',
	 *     'key'         => '',
	 *     'value'       => ''
	 * ];
	 *
	 * $this->load->model('design/translation');
	 *
	 * $this->model_design_translation->addTranslation($translation_data);
	 */
	public function addTranslation(array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "translation` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `route` = '" . $this->db->escape((string)$data['route']) . "', `key` = '" . $this->db->escape((string)$data['key']) . "', `value` = '" . $this->db->escape((string)$data['value']) . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW()");
	}

	/**
	 * Edit Translation
	 *
	 * Edit translation record in the database.
	 *
	 * @param int                  $translation_id primary key of the translation record
	 * @param array<string, mixed> $data           array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $translation_data = [
	 *     'store_id'    => 1,
	 *     'language_id' => 1,
	 *     'route'       => '',
	 *     'key'         => '',
	 *     'value'       => ''
	 * ];
	 *
	 * $this->load->model('design/translation');
	 *
	 * $this->model_design_translation->editTranslation($translation_id, $translation_data);
	 */
	public function editTranslation(int $translation_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "translation` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `route` = '" . $this->db->escape((string)$data['route']) . "', `key` = '" . $this->db->escape((string)$data['key']) . "', `value` = '" . $this->db->escape((string)$data['value']) . "', `status` = '" . (bool)$data['status'] . "' WHERE `translation_id` = '" . (int)$translation_id . "'");
	}

	/**
	 * Edit Status
	 *
	 * Edit category status record in the database.
	 *
	 * @param int  $category_id primary key of the category record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/category');
	 *
	 * $this->model_catalog_category->editStatus($category_id, $status);
	 */
	public function editStatus(int $translation_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "translation` SET `status` = '" . (bool)$status . "' WHERE `translation_id` = '" . (int)$translation_id . "'");
	}

	/**
	 * Delete Translation
	 *
	 * Delete translation record in the database.
	 *
	 * @param int $translation_id primary key of the translation record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/translation');
	 *
	 * $this->model_design_translation->deleteTranslation($translation_id);
	 */
	public function deleteTranslation(int $translation_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "translation` WHERE `translation_id` = '" . (int)$translation_id . "'");
	}

	/**
	 * Delete Translations By Store ID
	 *
	 * Delete translations by store record in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/translation');
	 *
	 * $this->model_design_translation->deleteTranslationsByStoreId($store_id);
	 */
	public function deleteTranslationsByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "translation` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Translations By Language ID
	 *
	 * Delete translations by language record in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/translation');
	 *
	 * $this->model_design_translation->deleteTranslationsByLanguageId($language_id);
	 */
	public function deleteTranslationsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "translation` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Translation
	 *
	 * Get the record of the translation record in the database.
	 *
	 * @param int $translation_id primary key of the translation record
	 *
	 * @return array<string, mixed> translation record that has translation ID
	 *
	 * @example
	 *
	 * $this->load->model('design/translation');
	 *
	 * $translation_info = $this->model_design_translation->getTranslation($translation_id);
	 */
	public function getTranslation(int $translation_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "translation` WHERE `translation_id` = '" . (int)$translation_id . "'");

		return $query->row;
	}

	/**
	 * Get Translations
	 *
	 * Get the record of the translation records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> translation records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'store',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('design/translation');
	 *
	 * $results = $this->model_design_translation->getTranslations($filter_data);
	 */
	public function getTranslations(array $data = []): array {
		$sql = "SELECT *, (SELECT `s`.`name` FROM `" . DB_PREFIX . "store` `s` WHERE `s`.`store_id` = `t`.`store_id`) AS `store`, (SELECT `l`.`name` FROM `" . DB_PREFIX . "language` `l` WHERE `l`.`language_id` = `t`.`language_id`) AS `language` FROM `" . DB_PREFIX . "translation` `t`";

		$implode = [];

		if (!empty($data['filter_store_id'])) {
			$implode[] = "`t`.`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_language_id'])) {
			$implode[] = "`t`.`language_id` = '" . (int)$data['filter_language_id'] . "'";
		}

		if (!empty($data['filter_route'])) {
			$implode[] = "`t`.`route` = '" .  $this->db->escape($data['filter_route'])  . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY store ASC";

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

	/**
	 * Get Total Translations
	 *
	 * Get the total number of translation records in the database.
	 *
	 * @return int total number of translation records
	 *
	 * @example
	 *
	 * $this->load->model('design/translation');
	 *
	 * $translation_total = $this->model_design_translation->getTotalTranslations();
	 */
	public function getTotalTranslations(): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "translation`";

		$implode = [];

		if (!empty($data['filter_store_id'])) {
			$implode[] = "`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_language_id'])) {
			$implode[] = "`language_id` = '" . (int)$data['filter_language_id'] . "'";
		}

		if (!empty($data['filter_route'])) {
			$implode[] = "`route` = '" .  $this->db->escape($data['filter_route'])  . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}

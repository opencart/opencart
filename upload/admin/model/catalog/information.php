<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Information
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Information extends \Opencart\System\Engine\Model {
	/**
	 * Add Information
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addInformation(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "information` SET `sort_order` = '" . (int)$data['sort_order'] . "', `bottom` = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$information_id = $this->db->getLastId();

		foreach ($data['information_description'] as $language_id => $value) {
			$this->addDescription($information_id, $language_id, $value);
		}

		if (isset($data['information_store'])) {
			foreach ($data['information_store'] as $store_id) {
				$this->addStore($information_id, $store_id);
			}
		}

		// SEO URL
		$this->load->model('design/seo_url');

		foreach ($data['information_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->model_design_seo_url->addSeoUrl($store_id, $language_id, 'information_id', $information_id, $keyword);
			}
		}

		if (isset($data['information_layout'])) {
			foreach ($data['information_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->addLayout($information_id, $store_id, $layout_id);
				}
			}
		}

		$this->cache->delete('information');

		return $information_id;
	}

	/**
	 * Edit Information
	 *
	 * @param int                  $information_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editInformation(int $information_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "information` SET `sort_order` = '" . (int)$data['sort_order'] . "', `bottom` = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `information_id` = '" . (int)$information_id . "'");

		$this->deleteDescription($information_id);

		foreach ($data['information_description'] as $language_id => $value) {
			$this->addDescription($information_id, $language_id, $value);
		}

		$this->deleteStore($information_id);

		if (isset($data['information_store'])) {
			foreach ($data['information_store'] as $store_id) {
				$this->addStore($information_id, $store_id);
			}
		}

		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlByKeyValue('information_id', $information_id);

		foreach ($data['information_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->model_design_seo_url->addSeoUrl($store_id, $language_id, 'information_id', $information_id, $keyword);
			}
		}

		$this->deleteLayout($information_id);

		if (isset($data['information_layout'])) {
			foreach ($data['information_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->addLayout($information_id, $store_id, $layout_id);
				}
			}
		}

		$this->cache->delete('information');
	}

	/**
	 * Delete Information
	 *
	 * @param int $information_id
	 *
	 * @return void
	 */
	public function deleteInformation(int $information_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "information` WHERE `information_id` = '" . (int)$information_id . "'");

		$this->deleteDescription($information_id);
		$this->deleteStore($information_id);
		$this->deleteLayout($information_id);

		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlByKeyValue('information_id', $information_id);

		$this->cache->delete('information');
	}

	/**
	 * Get Information
	 *
	 * @param int $information_id
	 *
	 * @return array<string, mixed>
	 */
	public function getInformation(int $information_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "information` WHERE `information_id` = '" . (int)$information_id . "'");

		return $query->row;
	}

	/**
	 * Get Information(s)
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getInformations(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "information` `i` LEFT JOIN `" . DB_PREFIX . "information_description` `id` ON (`i`.`information_id` = `id`.`information_id`) WHERE `id`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = [
			'id.title',
			'i.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `id`.`title`";
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

		$key = md5($sql);

		$information_data = $this->cache->get('information.' . $key);

		if (!$information_data) {
			$query = $this->db->query($sql);

			$information_data = $query->rows;

			$this->cache->set('information.' . $key, $information_data);
		}

		return $information_data;
	}

	/**
	 *	Add Description
	 *
	 *
	 * @param int $attribute_id primary key of the attribute record to be fetched
	 *
	 * @return array<int, array<string, string>> Descriptions sorted by language_id
	 */
	public function addDescription(int $information_id, int $language_id, $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "information_description` SET `information_id` = '" . (int)$information_id . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($data['title']) . "', `description` = '" . $this->db->escape($data['description']) . "', `meta_title` = '" . $this->db->escape($data['meta_title']) . "', `meta_description` = '" . $this->db->escape($data['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($data['meta_keyword']) . "'");
	}

	/**
	 *	Delete Description
	 *
	 *
	 * @param int $attribute_id primary key of the attribute record to be fetched
	 *
	 * @return array<int, array<string, string>> Descriptions sorted by language_id
	 */
	public function deleteDescription(int $information_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "information_description` WHERE `information_id` = '" . (int)$information_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $information_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $information_id): array {
		$information_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "information_description` WHERE `information_id` = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_description_data[$result['language_id']] = [
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			];
		}

		return $information_description_data;
	}

	/**
	 * Add Store
	 *
	 * @param int $information_id
	 * @param int $store_id
	 *
	 * @return void
	 */
	public function addStore(int $information_id, $store_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "information_to_store` SET `information_id` = '" . (int)$information_id . "', `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Store
	 *
	 * @param int $information_id
	 *
	 * @return void
	 */
	public function deleteStore(int $information_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "information_to_store` WHERE `information_id` = '" . (int)$information_id . "'");
	}

	/**
	 * Get Stores
	 *
	 * @param int $information_id
	 *
	 * @return array<int, int>
	 */
	public function getStores(int $information_id): array {
		$information_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "information_to_store` WHERE `information_id` = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_store_data[] = $result['store_id'];
		}

		return $information_store_data;
	}

	/**
	 * Add Layout
	 *
	 * @param int $information_id
	 * @param int $store_id
	 * @param int $layout_id
	 *
	 * @return void
	 */
	public function addLayout(int $information_id, int $store_id, int $layout_id): array {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "information_to_layout` SET `information_id` = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Store
	 *
	 * @param int $information_id
	 *
	 * @return void
	 */
	public function deleteLayout(int $information_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "information_to_layout` WHERE `information_id` = '" . (int)$information_id . "'");
	}

	/**
	 * Get Layouts
	 *
	 * @param int $information_id
	 *
	 * @return array<int, int>
	 */
	public function getLayouts(int $information_id): array {
		$information_layout_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "information_to_layout` WHERE `information_id` = '" . (int)$information_id . "'");

		foreach ($query->rows as $result) {
			$information_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $information_layout_data;
	}

	public function deleteLayoutByLayoutId(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "information_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Get Total Information(s)
	 *
	 * @return int
	 */
	public function getTotalInformations(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "information`");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Information(s) By Layout ID
	 *
	 * @param int $layout_id
	 *
	 * @return int
	 */
	public function getTotalInformationsByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "information_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}
}

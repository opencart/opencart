<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Blog Category
 *
 * @package Opencart\Admin\Model\Cms
 */
class BlogCategory extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int $blog_category_id
	 */
	public function addBlogCategory(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category` SET `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "'");

		$blog_category_id = $this->db->getLastId();

		foreach ($data['blog_category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_description` SET `blog_category_id` = '" . (int)$blog_category_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['blog_store'])) {
			foreach ($data['blog_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_to_store` SET `blog_category_id` = '" . (int)$blog_category_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		foreach ($data['blog_category_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'blog_category_id', `value`= '" . (int)$blog_category_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
			}
		}

		return $blog_category_id;
	}

	/**
	 * @param int   $blog_category_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editBlogCategory(int $blog_category_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "blog_category` SET `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "' WHERE `blog_category_id` = '" . (int)$blog_category_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_category_description` WHERE `blog_category_id` = '" . (int)$blog_category_id . "'");

		foreach ($data['blog_category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_description` SET `blog_category_id` = '" . (int)$blog_category_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape((string)$value['image']) . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_category_to_store` WHERE `blog_category_id` = '" . (int)$blog_category_id . "'");

		if (isset($data['blog_store'])) {
			foreach ($data['blog_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_category_to_store` SET `blog_category_id` = '" . (int)$blog_category_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'blog_category_id' AND `value` = '" . (int)$blog_category_id . "'");

		foreach ($data['blog_category_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'blog_category_id', `value` = '" . (int)$blog_category_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
			}
		}
	}

	/**
	 * @param int $blog_id
	 *
	 * @return void
	 */
	public function deleteBlogCategory(int $blog_category_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_category` WHERE `blog_category_id` = '" . (int)$blog_category_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_category_description` WHERE `blog_category_id` = '" . (int)$blog_category_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_category_to_store` WHERE `blog_category_id` = '" . (int)$blog_category_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'blog_category_id' AND `value` = '" . (int)$blog_category_id . "'");
	}

	/**
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getBlogCategory(int $blog_category_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "blog_category` `bc` LEFT JOIN `" . DB_PREFIX . "blog_category_description` `bcd` ON (`bc`.`blog_category_id` = `bcd`.`blog_category_id`) WHERE `bc`.`blog_category_id` = '" . (int)$blog_category_id . "' AND `bcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getBlogCategories(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "blog_category` `bc` LEFT JOIN `" . DB_PREFIX . "blog_category_description` `bcd` ON (`bc`.`blog_category_id` = `bcd`.`blog_category_id`) WHERE `bcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = [
			'bcd.name',
			'bc.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `bc`.`sort_order`";
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

	/**
	 * @param int $blog_category_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $blog_category_id): array {
		$blog_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category_description` WHERE `blog_category_id` = '" . (int)$blog_category_id . "'");

		foreach ($query->rows as $result) {
			$blog_description_data[$result['language_id']] = [
				'image'            => $result['image'],
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			];
		}

		return $blog_description_data;
	}

	/**
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getSeoUrls(int $blog_category_id): array {
		$blog_category_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'blog_category_id' AND `value` = '" . (int)$blog_category_id . "'");

		foreach ($query->rows as $result) {
			$blog_category_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $blog_category_seo_url_data;
	}

	/**
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getStores(int $blog_category_id): array {
		$blog_category_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_category_to_store` WHERE `blog_category_id` = '" . (int)$blog_category_id . "'");

		foreach ($query->rows as $result) {
			$blog_category_store_data[] = $result['store_id'];
		}

		return $blog_category_store_data;
	}

	/**
	 * @return int
	 */
	public function getTotalBlogCategories(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "blog_category`");

		return (int)$query->row['total'];
	}
}

<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Blog
 *
 * @package Opencart\Admin\Model\Cms
 */
class Blog extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addBlog(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "blog` SET `blog_category_id` = '" . (int)$data['blog_category_id'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `date_added` = NOW(), `date_modified` = NOW()");

		$blog_id = $this->db->getLastId();

		foreach ($data['blog_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_description` SET `blog_id` = '" . (int)$blog_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape($value['image']) . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['blog_store'])) {
			foreach ($data['blog_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_to_store` SET `blog_id` = '" . (int)$blog_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		// Seo urls on categories need to be done differently to they include the full keyword path
		foreach ($data['blog_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'blog_id', `value`= '" . (int)$blog_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
			}
		}

		// Set which layout to use with this blog
		if (isset($data['blog_layout'])) {
			foreach ($data['blog_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_to_layout` SET `blog_id` = '" . (int)$blog_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
			}
		}

		return $blog_id;
	}

	/**
	 * @param int   $blog_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editBlog(int $blog_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "blog` SET `blog_category_id` = '" . (int)$data['blog_category_id'] . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `date_modified` = NOW() WHERE `blog_id` = '" . (int)$blog_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_description` WHERE `blog_id` = '" . (int)$blog_id . "'");

		foreach ($data['blog_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_description` SET `blog_id` = '" . (int)$blog_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape($value['image']) . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_to_store` WHERE `blog_id` = '" . (int)$blog_id . "'");

		if (isset($data['blog_store'])) {
			foreach ($data['blog_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_to_store` SET `blog_id` = '" . (int)$blog_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'blog_id' AND `value` = '" . (int)$blog_id . "'");

		foreach ($data['blog_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'blog_id', `value` = '" . (int)$blog_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
			}
		}

		// Layouts
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_to_layout` WHERE `blog_id` = '" . (int)$blog_id . "'");

		if (isset($data['blog_layout'])) {
			foreach ($data['blog_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blog_to_layout` SET `blog_id` = '" . (int)$blog_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
			}
		}
	}

	/**
	 * @param int $blog_id
	 *
	 * @return void
	 */
	public function deleteBlog(int $blog_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog` WHERE `blog_id` = '" . (int)$blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_description` WHERE `blog_id` = '" . (int)$blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_to_store` WHERE `blog_id` = '" . (int)$blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_to_layout` WHERE `blog_id` = '" . (int)$blog_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'blog_id' AND `value` = '" . (int)$blog_id . "'");
	}

	/**
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getBlog(int $blog_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "blog` `b` LEFT JOIN `" . DB_PREFIX . "blog_description` `bd` ON (`b`.`blog_id` = `bd`.`blog_id`) WHERE `b`.`blog_id` = '" . (int)$blog_id . "' AND `bd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getBlogs(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "blog` `b` LEFT JOIN `" . DB_PREFIX . "blog_description` `bd` ON (`b`.`blog_id` = `bd`.`blog_id`) WHERE `bd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND `bd`.`name` LIKE '" . $this->db->escape((string)$data['filter_name']) . "'";
		}

		$sort_data = [
			'bd.name',
			'b.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `b`.`date_added`";
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
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $blog_id): array {
		$blog_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_description` WHERE `blog_id` = '" . (int)$blog_id . "'");

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
	public function getSeoUrls(int $blog_id): array {
		$blog_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'blog_id' AND `value` = '" . (int)$blog_id . "'");

		foreach ($query->rows as $result) {
			$blog_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $blog_seo_url_data;
	}

	/**
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getStores(int $blog_id): array {
		$blog_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_to_store` WHERE `blog_id` = '" . (int)$blog_id . "'");

		foreach ($query->rows as $result) {
			$blog_store_data[] = $result['store_id'];
		}

		return $blog_store_data;
	}

	/**
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getLayouts(int $blog_id): array {
		$blog_layout_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_to_layout` WHERE `blog_id` = '" . (int)$blog_id . "'");

		foreach ($query->rows as $result) {
			$blog_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $blog_layout_data;
	}

	/**
	 * @return int
	 */
	public function getTotalBlogs(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "blog`");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $layout_id
	 *
	 * @return int
	 */
	public function getTotalBlogsByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "blog_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}
}

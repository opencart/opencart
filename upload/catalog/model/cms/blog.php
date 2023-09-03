<?php
namespace Opencart\Catalog\Model\Cms;
/**
 * Class Blog
 *
 * @package Opencart\Admin\Model\Cms
 */
class Blog extends \Opencart\System\Engine\Model {
	/**
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getBlog(int $blog_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "blog` `b` LEFT JOIN `" . DB_PREFIX . "blog_description` `bd` ON (`b`.`blog_id` = `bd`.`blog_id`) LEFT JOIN `" . DB_PREFIX . "blog_to_store` `b2s` ON (`b`.`blog_id` = `b2s`.`blog_id`) WHERE `b`.`blog_id` = '" . (int)$blog_id . "' AND `bd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `b2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getBlogs(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "blog` `b` LEFT JOIN `" . DB_PREFIX . "blog_description` `bd` ON (`b`.`blog_id` = `bd`.`blog_id`) LEFT JOIN `" . DB_PREFIX . "blog_to_store` `b2s` ON (`b`.`blog_id` = `b2s`.`blog_id`) WHERE `bd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `b2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_search'])) {
			$sql .= " AND (";

			$implode = [];

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_search'])));

			foreach ($words as $word) {
				$implode[] = "`bd`.`name` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
			}

			if ($implode) {
				$sql .= " (" . implode(" OR ", $implode) . ")";
			}

			$sql .= " OR `bd`.`description` LIKE '" . $this->db->escape('%' . (string)$data['filter_search'] . '%') . "' OR";

			$implode = [];

			foreach ($words as $word) {
				$implode[] = "`bd`.`tag` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
			}

			if ($implode) {
				$sql .= " (" . implode(" OR ", $implode) . ")";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_blog_category_id'])) {
			$sql .= " AND `b`.`blog_category_id` = '" . (int)$data['filter_blog_category_id'] . "'";
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
	 * @return int
	 */
	public function getTotalBlogs(): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "blog` `b` LEFT JOIN `" . DB_PREFIX . "blog_description` `bd` ON (`b`.`blog_id` = `bd`.`blog_id`) LEFT JOIN `" . DB_PREFIX . "blog_to_store` `b2s` ON (`b`.`blog_id` = `b2s`.`blog_id`) WHERE `bd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `b2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_search'])) {
			$sql .= " AND (";

			$implode = [];

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_search'])));

			foreach ($words as $word) {
				$implode[] = "`bd`.`name` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
			}

			if ($implode) {
				$sql .= " (" . implode(" OR ", $implode) . ")";
			}

			$sql .= " OR `bd`.`description` LIKE '" . $this->db->escape('%' . (string)$data['filter_search'] . '%') . "' OR";

			$implode = [];

			foreach ($words as $word) {
				$implode[] = "`bd`.`tag` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
			}

			if ($implode) {
				$sql .= " (" . implode(" OR ", $implode) . ")";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_blog_category_id'])) {
			$sql .= " AND `b`.`blog_category_id` = '" . (int)$data['filter_blog_category_id'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * @param int $blog_id
	 *
	 * @return array
	 */
	public function getLayoutId(int $blog_id): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blog_to_layout` WHERE `blog_id` = '" . (int)$blog_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}

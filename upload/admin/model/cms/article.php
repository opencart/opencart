<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Article
 *
 * @package Opencart\Admin\Model\Cms
 */
class Article extends \Opencart\System\Engine\Model {
	/**
	 * Add Article
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addArticle(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article` SET `topic_id` = '" . (int)$data['topic_id'] . "', `author` = '" . $this->db->escape($data['author']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_added` = NOW(), `date_modified` = NOW()");

		$article_id = $this->db->getLastId();

		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "article_description` SET `article_id` = '" . (int)$article_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape($value['image']) . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `tag` = '" . $this->db->escape($value['tag']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_store` SET `article_id` = '" . (int)$article_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		foreach ($data['article_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'article_id', `value`= '" . (int)$article_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
			}
		}

		// Set which layout to use with this article
		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_layout` SET `article_id` = '" . (int)$article_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
			}
		}

		$this->cache->delete('article');

		return $article_id;
	}

	/**
	 * Edit Article
	 *
	 * @param int                  $article_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editArticle(int $article_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article` SET `topic_id` = '" . (int)$data['topic_id'] . "', `author` = '" . $this->db->escape($data['author']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_modified` = NOW() WHERE `article_id` = '" . (int)$article_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_description` WHERE `article_id` = '" . (int)$article_id . "'");

		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "article_description` SET `article_id` = '" . (int)$article_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape($value['image']) . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `tag` = '" . $this->db->escape($value['tag']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_store` WHERE `article_id` = '" . (int)$article_id . "'");

		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_store` SET `article_id` = '" . (int)$article_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'article_id' AND `value` = '" . (int)$article_id . "'");

		foreach ($data['article_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'article_id', `value` = '" . (int)$article_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
			}
		}

		// Layouts
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_layout` WHERE `article_id` = '" . (int)$article_id . "'");

		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_layout` SET `article_id` = '" . (int)$article_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
			}
		}

		$this->cache->delete('article');
	}

	/**
	 * Edit Rating
	 *
	 * @param int $article_id
	 * @param int $rating
	 */
	public function editRating(int $article_id, int $rating): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article` SET `rating` = '" . (int)$rating . "' WHERE `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Delete Article
	 *
	 * @param int $article_id
	 *
	 * @return void
	 */
	public function deleteArticle(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article` WHERE `article_id` = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_comment` WHERE `article_id` = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_description` WHERE `article_id` = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_store` WHERE `article_id` = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_layout` WHERE `article_id` = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'article_id' AND `value` = '" . (int)$article_id . "'");

		$this->cache->delete('article');
	}

	/**
	 * Get Article
	 *
	 * @param int $article_id
	 *
	 * @return array<string, mixed>
	 */
	public function getArticle(int $article_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "article` `a` LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`a`.`article_id` = `ad`.`article_id`) WHERE `a`.`article_id` = '" . (int)$article_id . "' AND `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Articles
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getArticles(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "article` `a` LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`a`.`article_id` = `ad`.`article_id`) WHERE `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`ad`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		$sort_data = [
			'ad.name',
			'a.author',
			'a.rating',
			'a.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `a`.`date_added`";
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

		$article_data = $this->cache->get('article.' . $key);

		if (!$article_data) {
			$query = $this->db->query($sql);

			$article_data = $query->rows;

			$this->cache->set('article.' . $key, $article_data);
		}

		return $article_data;
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $article_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getDescriptions(int $article_id): array {
		$article_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "article_description` WHERE `article_id` = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_description_data[$result['language_id']] = [
				'image'            => $result['image'],
				'name'             => $result['name'],
				'description'      => $result['description'],
				'tag'              => $result['tag'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			];
		}

		return $article_description_data;
	}

	/**
	 * Get Seo Urls
	 *
	 * @param int $article_id
	 *
	 * @return array<int, array<int, string>>
	 */
	public function getSeoUrls(int $article_id): array {
		$article_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'article_id' AND `value` = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $article_seo_url_data;
	}

	/**
	 * Get Stores
	 *
	 * @param int $article_id
	 *
	 * @return array<int, int>
	 */
	public function getStores(int $article_id): array {
		$article_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "article_to_store` WHERE `article_id` = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_store_data[] = $result['store_id'];
		}

		return $article_store_data;
	}

	/**
	 * Get Layouts
	 *
	 * @param int $article_id
	 *
	 * @return array<int, int>
	 */
	public function getLayouts(int $article_id): array {
		$article_layout_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "article_to_layout` WHERE `article_id` = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $article_layout_data;
	}

	/**
	 * Get Total Articles
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function getTotalArticles(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "article`";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Articles By Layout ID
	 *
	 * @param int $layout_id
	 *
	 * @return int
	 */
	public function getTotalArticlesByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "article_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Edit Comment Status
	 *
	 * @param int  $article_comment_id
	 * @param bool $status
	 *
	 * @return void
	 */
	public function editCommentStatus(int $article_comment_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article_comment` SET `status` = '" . (bool)$status . "' WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		$this->cache->delete('topic');
	}

	/**
	 * Edit Comment Rating
	 *
	 * @param int $article_id
	 * @param int $article_comment_id
	 * @param int $rating
	 */
	public function editCommentRating(int $article_id, int $article_comment_id, int $rating): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article_comment` SET `rating` = '" . (int)$rating . "' WHERE `article_comment_id` = '" . (int)$article_comment_id . "' AND `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Delete Comment
	 *
	 * @param int $article_comment_id
	 *
	 * @return void
	 */
	public function deleteComment(int $article_comment_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_comment` WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		$this->cache->delete('topic');
	}

	/**
	 * Get Comment
	 *
	 * @param int $article_comment_id
	 *
	 * @return array<string, mixed>
	 */
	public function getComment(int $article_comment_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "article_comment` WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		return $query->row;
	}

	/**
	 * Get Comments
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getComments(array $data = []): array {
		$sql = "SELECT *, `ac`.`rating`, `ac`.`status`, `ac`.`date_added` FROM `" . DB_PREFIX . "article_comment` `ac` LEFT JOIN `" . DB_PREFIX . "article` `a` ON (`ac`.`article_id` = `a`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`ac`.`article_id` = `ad`.`article_id`)";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`ac`.`comment`) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_keyword']) . '%') . "'";
		}

		if (!empty($data['filter_article'])) {
			$implode[] = "LCASE(`ad`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_article']) . '%') . "'";
		}

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "`ac`.`customer_id` = '" . (int)$data['filter_customer_id'] . "'";
		}

		if (!empty($data['filter_author'])) {
			$implode[] = "LCASE(`ac`.`author`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_author']) . '%') . "'";
		}

		if (!empty($data['filter_status'])) {
			$implode[] = "`ac`.`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(`ac`.`date_added`) =  DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY `ac`.`date_added` DESC";

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
	 * Get Total Comments
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function getTotalComments(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "article_comment` `ac` LEFT JOIN `" . DB_PREFIX . "article` `a` ON (`ac`.`article_id` = `a`.`article_id`)";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`ac`.`comment`) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_keyword']) . '%') . "'";
		}

		if (!empty($data['filter_article'])) {
			$implode[] = "LCASE(`ad`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_article']) . '%') . "'";
		}

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "`ac`.`customer_id` = '" . (int)$data['filter_customer_id'] . "'";
		}

		if (!empty($data['filter_author'])) {
			$implode[] = "LCASE(`ac`.`author`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_author']) . '%') . "'";
		}

		if (!empty($data['filter_status'])) {
			$implode[] = "`ac`.`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(`ac`.`date_added`) =  DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Ratings
	 *
	 * @param int $article_id
	 * @param int $article_comment_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getRatings(int $article_id, int $article_comment_id = 0): array {
		$sql = "SELECT rating, COUNT(*) AS total FROM `" . DB_PREFIX . "article_rating` WHERE `article_id` = '" . (int)$article_id . "'";

		if ($article_comment_id) {
			$sql .= " AND `article_comment_id` = '" . (int)$article_comment_id . "'";
		}

		$sql .= " GROUP BY rating";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}

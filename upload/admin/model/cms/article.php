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

		// Description
		foreach ($data['article_description'] as $language_id => $value) {
			$this->model_cms_article->addDescription($article_id, $language_id, $value);
		}

		// Store
		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->model_cms_article->addStore($article_id, $store_id);
			}
		}

		// SEO URL
		$this->load->model('design/seo_url');

		foreach ($data['article_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->model_design_seo_url->addSeoUrl('article_id', $article_id, $keyword, $store_id, $language_id);
			}
		}

		// Layouts
		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->model_cms_article->addLayout($article_id, $store_id, $layout_id);
				}
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

		// Description
		$this->model_cms_article->deleteDescriptions($article_id);

		foreach ($data['article_description'] as $language_id => $value) {
			$this->model_cms_article->addDescription($article_id, $language_id, $value);
		}

		// Store
		$this->model_cms_article->deleteStores($article_id);

		if (isset($data['article_store'])) {
			foreach ($data['article_store'] as $store_id) {
				$this->model_cms_article->addStore($article_id, $store_id);
			}
		}

		// SEO URL
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('article_id', $article_id);

		foreach ($data['article_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->model_design_seo_url->addSeoUrl('article_id', $article_id, $keyword, $store_id, $language_id);
			}
		}

		// Layouts
		$this->model_cms_article->deleteLayouts($article_id);

		if (isset($data['article_layout'])) {
			foreach ($data['article_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->model_cms_article->addLayout($article_id, $store_id, $layout_id);
				}
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

		$this->model_cms_article->deleteDescriptions($article_id);
		$this->model_cms_article->deleteStores($article_id);
		$this->model_cms_article->deleteLayouts($article_id);
		$this->model_cms_article->deleteCommentsByArticleId($article_id);

		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('article_id', $article_id);

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
	 *	Add Description
	 *
	 * @param int                  $article_id
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addDescription(int $article_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article_description` SET `article_id` = '" . (int)$article_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape($data['image']) . "', `name` = '" . $this->db->escape($data['name']) . "', `description` = '" . $this->db->escape($data['description']) . "', `tag` = '" . $this->db->escape($data['tag']) . "', `meta_title` = '" . $this->db->escape($data['meta_title']) . "', `meta_description` = '" . $this->db->escape($data['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($data['meta_keyword']) . "'");
	}

	/**
	 *	Delete Descriptions
	 *
	 * @param int $article_id
	 *
	 * @return void
	 */
	public function deleteDescriptions(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_description` WHERE `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_description` WHERE `language_id` = '" . (int)$language_id . "'");
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
	 * Get Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "article_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Store
	 *
	 * @param int $article_id
	 * @param int $store_id
	 *
	 * @return void
	 */
	public function addStore(int $article_id, int $store_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_store` SET `article_id` = '" . (int)$article_id . "', `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Stores
	 *
	 * @param int $article_id
	 *
	 * @return void
	 */
	public function deleteStores(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_store` WHERE `article_id` = '" . (int)$article_id . "'");
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
	 * Add Layout
	 *
	 * @param int $article_id
	 * @param int $store_id
	 * @param int $layout_id
	 *
	 * @return void
	 */
	public function addLayout(int $article_id, int $store_id, int $layout_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_layout` SET `article_id` = '" . (int)$article_id . "', store_id = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts
	 *
	 * @param int $article_id
	 *
	 * @return void
	 */
	public function deleteLayouts(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_layout` WHERE `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Delete Layouts By Layout ID
	 *
	 * @param int $layout_id
	 *
	 * @return void
	 */
	public function deleteLayoutsByLayoutId(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
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
	 * Get Total Layouts By Layout ID
	 *
	 * @param int $layout_id
	 *
	 * @return int
	 */
	public function getTotalLayoutsByLayoutId(int $layout_id): int {
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
	 * Delete Comments by article ID
	 *
	 * @param int $article_id
	 *
	 * @return void
	 */
	public function deleteCommentsByArticleId(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_comment` WHERE `article_id` = '" . (int)$article_id . "'");

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
}

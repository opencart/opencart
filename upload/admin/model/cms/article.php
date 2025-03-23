<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Article
 *
 * Can be loaded using $this->load->model('cms/article');
 *
 * @package Opencart\Admin\Model\Cms
 */
class Article extends \Opencart\System\Engine\Model {
	/**
	 * Add Article
	 *
	 * Create a new article record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new article record
	 *
	 * @example
	 *
	 * $article_data = [
	 *     'article_description' => [],
	 *     'author'              => 'Author Name',
	 *     'status'              => 0,
	 * ];
	 *
	 * $this->load->model('cms/article');
	 *
	 * $article_id = $this->model_cms_article->addArticle($article_data);
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

		// SEO
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
	 * Edit article record in the database.
	 *
	 * @param int                  $article_id primary key of the article record
	 * @param array<string, mixed> $data       array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $article_data = [
	 *     'article_description' => [],
	 *     'author'              => 'Author Name',
	 *     'status'              => 1,
	 * ];
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->editArticle($article_id, $article_data);
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

		// SEO
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
	 * Edit article rating record in the database.
	 *
	 * @param int $article_id primary key of the article record
	 * @param int $rating
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->editRating($article_id, $rating);
	 */
	public function editRating(int $article_id, int $rating): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article` SET `rating` = '" . (int)$rating . "' WHERE `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Delete Article
	 *
	 * Delete article record in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->deleteArticle($article_id);
	 */
	public function deleteArticle(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article` WHERE `article_id` = '" . (int)$article_id . "'");

		$this->model_cms_article->deleteDescriptions($article_id);
		$this->model_cms_article->deleteStores($article_id);
		$this->model_cms_article->deleteCommentsByArticleId($article_id);

		// SEO
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('article_id', $article_id);

		$this->model_cms_article->deleteLayouts($article_id);

		$this->cache->delete('article');
	}

	/**
	 * Get Article
	 *
	 * Get the record of the article record in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return array<string, mixed> article record that has article ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $article_info = $this->model_cms_article->getArticle($article_id);
	 */
	public function getArticle(int $article_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "article` `a` LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`a`.`article_id` = `ad`.`article_id`) WHERE `a`.`article_id` = '" . (int)$article_id . "' AND `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Articles
	 *
	 * Get the record of the article records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> article records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'a.date_added',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('cms/article');
	 *
	 * $results = $this->model_cms_article->getArticles($filter_data);
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
	 * Get the total number of article records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of article records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'a.date_added',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('cms/article');
	 *
	 * $article_total = $this->model_cms_article->getTotalArticles($filter_data);
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
	 * Add Description
	 *
	 * Create a new article description record in the database.
	 *
	 * @param int                  $article_id  primary key of the article record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $article_data['article_description'] = [
	 *     'image'            => 'article_image',
	 *     'name'             => 'Article Name',
	 *     'description'      => 'Article Description',
	 *     'tag'              => 'Article Tag',
	 *     'meta_title'       => 'Meta Title',
	 *     'meta_description' => 'Meta Description',
	 *     'meta_keyword'     => 'Meta Keyword'
	 * ];
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->addDescription($article_id, $language_id, $article_data);
	 */
	public function addDescription(int $article_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article_description` SET `article_id` = '" . (int)$article_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape($data['image']) . "', `name` = '" . $this->db->escape($data['name']) . "', `description` = '" . $this->db->escape($data['description']) . "', `tag` = '" . $this->db->escape($data['tag']) . "', `meta_title` = '" . $this->db->escape($data['meta_title']) . "', `meta_description` = '" . $this->db->escape($data['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($data['meta_keyword']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete article description records in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->deleteDescriptions($article_id);
	 */
	public function deleteDescriptions(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_description` WHERE `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete article descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the article description records in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return array<int, array<string, mixed>> description records that have article ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $results = $this->model_cms_article->getDescriptions($article_id);
	 */
	public function getDescriptions(int $article_id): array {
		$article_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "article_description` WHERE `article_id` = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_description_data[$result['language_id']] = $result;
		}

		return $article_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the article descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $article_description = $this->model_cms_article->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "article_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Store
	 *
	 * Create a new article store record in the database.
	 *
	 * @param int $article_id primary key of the article record
	 * @param int $store_id   primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->addStore($article_id, $store_id);
	 */
	public function addStore(int $article_id, int $store_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_store` SET `article_id` = '" . (int)$article_id . "', `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Stores
	 *
	 * Delete article store records in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->deleteStores($article_id);
	 */
	public function deleteStores(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_store` WHERE `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Get Stores
	 *
	 * Get the record of the article store records in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return array<int, int> store records that have article ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $article_store = $this->model_cms_article->getStores($article_id);
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
	 * Create a new article layout record in the database.
	 *
	 * @param int $article_id primary key of the article record
	 * @param int $store_id   primary key of the store record
	 * @param int $layout_id  primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->addLayout($article_id, $store_id, $layout_id);
	 */
	public function addLayout(int $article_id, int $store_id, int $layout_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article_to_layout` SET `article_id` = '" . (int)$article_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts
	 *
	 * Delete article layout records in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->deleteLayouts($article_id);
	 */
	public function deleteLayouts(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_layout` WHERE `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Delete Layouts By Layout ID
	 *
	 * Delete article layouts by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->deleteLayoutsByLayoutId($layout_id);
	 */
	public function deleteLayoutsByLayoutId(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Get Layouts
	 *
	 * Get the record of the article layout records in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return array<int, int> layout records that have article ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $article_layout = $this->model_cms_article->getLayouts($article_id);
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
	 * Get the total number of article layouts by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return int total number of layout records that have layout ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $layout_total = $this->model_cms_article->getTotalLayoutsByLayoutId($layout_id);
	 */
	public function getTotalLayoutsByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "article_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Edit Comment Status
	 *
	 * Edit article comment status record in the database.
	 *
	 * @param int  $article_comment_id primary key of the article comment record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->editCommentStatus($article_comment_id, $status);
	 */
	public function editCommentStatus(int $article_comment_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article_comment` SET `status` = '" . (bool)$status . "' WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		$this->cache->delete('topic');
	}

	/**
	 * Edit Comment Rating
	 *
	 * Edit article comment rating record in the database.
	 *
	 * @param int $article_id         primary key of the article record
	 * @param int $article_comment_id primary key of the article comment record
	 * @param int $rating
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->editCommentRating($article_id, $article_comment_id, $rating);
	 */
	public function editCommentRating(int $article_id, int $article_comment_id, int $rating): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article_comment` SET `rating` = '" . (int)$rating . "' WHERE `article_comment_id` = '" . (int)$article_comment_id . "' AND `article_id` = '" . (int)$article_id . "'");
	}

	/**
	 * Delete Comment
	 *
	 * Delete article comment record in the database.
	 *
	 * @param int $article_comment_id primary key of the article comment record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->deleteComment($article_comment_id);
	 */
	public function deleteComment(int $article_comment_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_comment` WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		$this->cache->delete('topic');
	}

	/**
	 * Delete Comments by article ID
	 *
	 * Delete article comments by article records in the database.
	 *
	 * @param int $article_id primary key of the article record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $this->model_cms_article->deleteCommentsByArticleId($article_id);
	 */
	public function deleteCommentsByArticleId(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_comment` WHERE `article_id` = '" . (int)$article_id . "'");

		$this->cache->delete('topic');
	}

	/**
	 * Get Comment
	 *
	 * Get the record of the article comment record in the database.
	 *
	 * @param int $article_comment_id primary key of the article comment record
	 *
	 * @return array<string, mixed> comment record that has article comment ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $comment_info = $this->model_cms_article->getComment($article_comment_id);
	 */
	public function getComment(int $article_comment_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "article_comment` WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		return $query->row;
	}

	/**
	 * Get Ratings
	 *
	 * Get the record of the article rating records in the database.
	 *
	 * @param int $article_id         primary key of the article record
	 * @param int $article_comment_id primary key of the article comment record
	 *
	 * @return array<int, array<string, mixed>> rating records that have article ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $results = $this->model_cms_article->getRatings($article_id, $article_comment_id);
	 */
	public function getRatings(int $article_id, int $article_comment_id = 0): array {
		$sql = "SELECT rating, COUNT(*) AS `total` FROM `" . DB_PREFIX . "article_rating` WHERE `article_id` = '" . (int)$article_id . "'";

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
	 * Get the record of the article comment records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> comment records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_keyword'   => 'Keyword',
	 *     'filter_article'   => 'Article Name',
	 *     'filter_customer'  => 'John Doe',
	 *     'filter_status'    => 1,
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('cms/article');
	 *
	 * $results = $this->model_cms_article->getComments($filter_data);
	 */
	public function getComments(array $data = []): array {
		$sql = "SELECT *, `ac`.`rating`, `ac`.`status`, `ac`.`date_added` FROM `" . DB_PREFIX . "article_comment` `ac` LEFT JOIN `" . DB_PREFIX . "article` `a` ON (`ac`.`article_id` = `a`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`ac`.`article_id` = `ad`.`article_id`) WHERE `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$sql .= " AND LCASE(`ac`.`comment`) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_keyword']) . '%') . "'";
		}

		if (!empty($data['filter_article'])) {
			$sql .= " AND LCASE(`ad`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_article']) . '%') . "'";
		}

		if (!empty($data['filter_customer_id'])) {
			$sql .= " AND `ac`.`customer_id` = '" . (int)$data['filter_customer_id'] . "'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND LCASE(`ac`.`author`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_author']) . '%') . "'";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND `ac`.`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(`ac`.`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
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
	 * Get the total number of article comment records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of comment records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_keyword'   => 'Keyword',
	 *     'filter_article'   => 'Article Name',
	 *     'filter_customer'  => 'John Doe',
	 *     'filter_status'    => 1,
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('cms/article');
	 *
	 * $comment_total = $this->model_cms_article->getTotalComments($filter_data);
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
			$implode[] = "DATE(`ac`.`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}

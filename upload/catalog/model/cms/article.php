<?php
namespace Opencart\Catalog\Model\Cms;
/**
 * Class Article
 *
 * @package Opencart\Catalog\Model\Cms
 */
class Article extends \Opencart\System\Engine\Model {
	/**
	 * @param int $article_id
	 *
	 * @return array
	 */
	public function getArticle(int $article_id): array {
		$sql = "SELECT DISTINCT * FROM `" . DB_PREFIX . "article` `a` LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`a`.`article_id` = `ad`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_to_store` `a2s` ON (`a`.`article_id` = `a2s`.`article_id`) WHERE `a`.`article_id` = '" . (int)$article_id . "' AND `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `a2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'";

		$article_data = $this->cache->get('article.'. md5($sql));

		if (!$article_data) {
			$query = $this->db->query($sql);

			$article_data = $query->row;

			$this->cache->set('article.'. md5($sql), $article_data);
		}

		return $article_data;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getArticles(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "article` `a` LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`a`.`article_id` = `ad`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_to_store` `a2s` ON (`a`.`article_id` = `a2s`.`article_id`) WHERE `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `a2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'";

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

			$sql .= " OR `bd`.`description` LIKE '" . $this->db->escape('%' . (string)$data['filter_search'] . '%') . "'";

			$implode = [];

			foreach ($words as $word) {
				$implode[] = "`bd`.`tag` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
			}

			if ($implode) {
				$sql .= " OR (" . implode(" OR ", $implode) . ")";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_topic_id'])) {
			$sql .= " AND `a`.`topic_id` = '" . (int)$data['filter_topic_id'] . "'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND `a`.`author` = '" . (int)$data['filter_author'] . "'";
		}

		$sql .= " ORDER BY `a`.`date_added` DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$article_data = $this->cache->get('article.'. md5($sql));

		if (!$article_data) {
			$query = $this->db->query($sql);

			$article_data = $query->rows;

			$this->cache->set('article.'. md5($sql), $article_data);
		}

		return $article_data;
	}

	/**
	 * @return int
	 */
	public function getTotalArticles(): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "article` `a` LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`a`.`article_id` = `ad`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_to_store` `a2s` ON (`a`.`article_id` = `a2s`.`article_id`) WHERE `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `a2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_search'])) {
			$sql .= " AND (";

			$implode = [];

			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_search'])));

			foreach ($words as $word) {
				$implode[] = "`ad`.`name` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
			}

			if ($implode) {
				$sql .= " (" . implode(" OR ", $implode) . ")";
			}

			$sql .= " OR `ad`.`description` LIKE '" . $this->db->escape('%' . (string)$data['filter_search'] . '%') . "'";

			$implode = [];

			foreach ($words as $word) {
				$implode[] = "`ad`.`tag` LIKE '" . $this->db->escape('%' . $word . '%') . "'";
			}

			if ($implode) {
				$sql .= " OR (" . implode(" OR ", $implode) . ")";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_topic_id'])) {
			$sql .= " AND `a`.`topic_id` = '" . (int)$data['filter_topic_id'] . "'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND `a`.`author` = '" . (int)$data['filter_author'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * @param int $article_id
	 *
	 * @return array
	 */
	public function getLayoutId(int $article_id): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "article_to_layout` WHERE `article_id` = '" . (int)$article_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	/**
	 * @param int   $product_id
	 * @param array $data
	 *
	 * @return int
	 */
	public function addComment(int $article_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article_comment` SET `article_id` = '" . (int)$article_id . "', `customer_id` = '" . (int)$this->customer->getId() . "', `author` = '" . $this->db->escape((string)$data['author']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "', `status` = '" . (bool)!empty($data['status']) . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getComments(int $article_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$sql = "SELECT * FROM `" . DB_PREFIX . "article_comment` WHERE `article_id` = '" . (int)$article_id . "' AND `status` = '1' ORDER BY `date_added` DESC LIMIT " . (int)$start . "," . (int)$limit;

		$comment_data = $this->cache->get('comment.'. md5($sql));

		if (!$comment_data) {
			$query = $this->db->query($sql);

			$comment_data = $query->rows;

			$this->cache->set('comment.'. md5($sql), $comment_data);
		}

		return $comment_data;
	}

	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalComments(int $article_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "article_comment` WHERE `article_id` = '" . (int)$article_id . "' AND `status` = '1'");

		return (int)$query->row['total'];
	}
}

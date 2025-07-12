<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Comment
 *
 * Can be loaded using $this->load->model('cms/comment');
 *
 * @package Opencart\Admin\Model\Cms
 */
class Comment extends \Opencart\System\Engine\Model {
	/**
	 * Add Comment
	 *
	 * Create a new comment record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new comment record
	 *
	 * @example
	 *
	 * $article_data = [
	 *     'article_id' 		=> 0,
	 *     'parent_id'          => 0,
	 *     'customer_id'        => 0,
	 *     'comment'            => 'Hello world',
	 *     'rating'          	=> 3,
	 *     'ip'          		=> '',
	 *     'status'             => 1,
	 * ];
	 *
	 * $this->load->model('cms/comment');
	 * $this->load->model('cms/article');
	 *
	 * $article_id = $this->model_cms_comment->addComment($article_data);
	 */
	public function addComment(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "article_comment` SET `article_id` = '" . (int)$data['article_id'] . "', `parent_id` = '" . (int)$data['parent_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `comment` = '" . $this->db->escape($data['comment']) . "', `rating` = '" . (int)$data['rating'] . "', `ip` = '" . $this->db->escape($data['ip']) . "', `status` = '" . (int)$data['status'] . "', `date_added` = NOW()");

		$article_comment_id = $this->db->getLastId();

		$this->cache->delete('comment');

		return $article_comment_id;
	}

	/**
	 * Edit Comment
	 *
	 * Edit comment record in the database.
	 *
	 * Updates only status, comment and rating.
	 *
	 * @param int   $article_comment_id primary key of the comment record
	 * @param array<string, mixed> $data array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/comment');
	 *
	 * $this->model_cms_comment->editComment($article_comment_id, $data);
	 */
	public function editComment(int $article_comment_id, array $data = []): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article_comment` SET `status` = '" . (int)$data['status'] . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "', `rating` = '" . (int)$data['rating'] . "' WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		$this->cache->delete('comment');
	}

	/**
	 * Edit Comment Status
	 *
	 * Edit comment status record in the database.
	 *
	 * @param int  $article_comment_id primary key of the article comment record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/comment');
	 *
	 * $this->model_cms_comment->editCommentStatus($article_comment_id, $status);
	 */
	public function editCommentStatus(int $article_comment_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article_comment` SET `status` = '" . (int)$status . "' WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		$this->cache->delete('comment');
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
	 * $this->load->model('cms/comment');
	 *
	 * $this->model_cms_comment->editCommentRating($article_id, $article_comment_id, $rating);
	 */
	public function editCommentRating(int $article_id, int $article_comment_id, int $rating): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "article_comment` SET `rating` = '" . (int)$rating . "' WHERE `article_comment_id` = '" . (int)$article_comment_id . "' AND `article_id` = '" . (int)$article_id . "'");

		$this->cache->delete('comment');
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
	 * $this->load->model('cms/comment');
	 *
	 * $this->model_cms_comment->deleteComment($article_comment_id);
	 */
	public function deleteComment(int $article_comment_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_comment` WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		$this->cache->delete('comment');
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
	 * $this->load->model('cms/comment');
	 *
	 * $this->model_cms_comment->deleteCommentsByArticleId($article_id);
	 */
	public function deleteCommentsByArticleId(int $article_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "article_comment` WHERE `article_id` = '" . (int)$article_id . "'");

		$this->cache->delete('comment');
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
	 * $this->load->model('cms/comment');
	 *
	 * $comment_info = $this->model_cms_comment->getComment($article_comment_id);
	 */
	public function getComment(int $article_comment_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "article_comment` WHERE `article_comment_id` = '" . (int)$article_comment_id . "'");

		return $query->row;
	}

	/**
	 * Get Comment Info
	 *
	 * Get the article comment record joined with article, article_description and customer tables.
	 *
	 * @param int $article_comment_id primary key of the article comment record
	 *
	 * @return array<string, mixed> comment record joined with article, article_description and customer tables.
	 *
	 * @example
	 *
	 * $this->load->model('cms/comment');
	 *
	 * $comment_info = $this->model_cms_comment->getCommentInfo($article_comment_id);
	 */
	public function getCommentInfo(int $article_comment_id): array {
		$sql = "SELECT CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `c`.`author`, `c`.`safe`, `c`.`commenter`, `ac`.`article_comment_id`, `ac`.`article_id`, `ac`.`parent_id`, `ac`.`comment`, `ac`.`customer_id`, `ac`.`rating`, `ac`.`status`, `ac`.`date_added`, `ad`.`name` AS `article` FROM `" . DB_PREFIX . "article_comment` `ac` LEFT JOIN `" . DB_PREFIX . "article` `a` ON (`ac`.`article_id` = `a`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`ac`.`article_id` = `ad`.`article_id`) LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ac`.`customer_id` = `c`.`customer_id`) WHERE `ac`.`article_comment_id` = '" . (int)$article_comment_id . "' AND `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	/**
	 * Get Comment Ratings
	 *
	 * Get comment ratings for for a specific article_id.
	 * Returns only comments that are enabled with a parent_id of 0.
	 *
	 * The returned array is used to calculate an Article's rating based off of Comment ratings.
	 * So, an Article's rating is the "average" of all comments posted for that article.
	 * The 'rating' column in the result set is summed then divided by the number of results to create the "average" rating.
	 *
	 * @param int $article_id key in the article_comment table.
	 *
	 * @return array<string, mixed> comment records for the specified article_id.
	 *
	 * @example
	 *
	 * $this->load->model('cms/comment');
	 *
	 * $ratings = $this->model_cms_comment->getCommentRatings($article_id);
	 */
	public function getCommentRatings(int $article_id): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "article_comment` WHERE `article_id` = '" . (int)$article_id . "' AND `parent_id` = '0' AND `status` = '1'";

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
	 *     'filter_article'   		=> 'Article Name',
	 *     'filter_author'    		=> 'John Doe',
	 *     'filter_comment'   		=> 'I love OpenCart',
	 *     'filter_customer'  		=> 'John Doe',
	 *     'filter_admin'  			=> 1,
	 *     'filter_comment_type'	=> 1,
	 *     'filter_status'    		=> 1,
	 *     'filter_rating_from' 	=> 1,
	 *     'filter_rating_to'   	=> 5,
	 *     'filter_date_from' 		=> '2021-01-01',
	 *     'filter_date_to'   		=> '2021-01-31',
	 *     'start'            		=> 0,
	 *     'limit'            		=> 10
	 * ];
	 *
	 * $this->load->model('cms/comment');
	 *
	 * $results = $this->model_cms_comment->getComments($filter_data);
	 */
	public function getComments(array $data = []): array {
		if (isset($data['filter_admin']) && $data['filter_admin'] == '0') {
			// Admin Comments (customer_id = 0) cannot join on customer table
			$sql = "SELECT `ac`.`article_comment_id`, `ac`.`article_id`, `ac`.`parent_id`, `ac`.`comment`, `ac`.`customer_id`, `ac`.`rating`, `ac`.`status`, `ac`.`date_added`, `ad`.`name` FROM `" . DB_PREFIX . "article_comment` `ac` LEFT JOIN `" . DB_PREFIX . "article` `a` ON (`ac`.`article_id` = `a`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`ac`.`article_id` = `ad`.`article_id`) WHERE `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " AND `ac`.`customer_id` = '0'";
		} else {
			$sql = "SELECT CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `c`.`author`, `c`.`safe`, `c`.`commenter`, `ac`.`article_comment_id`, `ac`.`article_id`, `ac`.`parent_id`, `ac`.`comment`, `ac`.`customer_id`, `ac`.`rating`, `ac`.`status`, `ac`.`date_added`, `ad`.`name` FROM `" . DB_PREFIX . "article_comment` `ac` LEFT JOIN `" . DB_PREFIX . "article` `a` ON (`ac`.`article_id` = `a`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`ac`.`article_id` = `ad`.`article_id`) LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ac`.`customer_id` = `c`.`customer_id`) WHERE `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

			if (isset($data['filter_admin']) && $data['filter_admin'] == '1') {
				$sql .= " AND `ac`.`customer_id` > '0'";
			}

			if (!empty($data['filter_customer'])) {
				$sql .= " AND LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_customer']) . '%') . "'";
			}

			if (!empty($data['filter_author'])) {
				$sql .= " AND LCASE(`c`.`author`) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_author']) . '%') . "'";
			}
		}

		if (!empty($data['filter_article'])) {
			$sql .= " AND LCASE(`ad`.`name`) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_article']) . '%') . "'";
		}

		if (!empty($data['filter_comment'])) {
			$sql .= " AND LCASE(`ac`.`comment`) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_comment']) . '%') . "'";
		}

		if (isset($data['filter_comment_type']) && $data['filter_comment_type'] != '') {
			if ($data['filter_comment_type']) {
				$sql .= " AND `ac`.`parent_id` > '0'";
			} else {
				$sql .= " AND `ac`.`parent_id` = '0'";
			}
		}

		if (isset($data['filter_status']) && $data['filter_status'] != '') {
			$sql .= " AND `ac`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_rating_from'])) {
			$sql .= " AND `ac`.`rating` >= '" . (int)$data['filter_rating_from'] . "'";
		}

		if (!empty($data['filter_rating_to'])) {
			$sql .= " AND `ac`.`rating` <= '" . (int)$data['filter_rating_to'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(`ac`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(`ac`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$sort_data = [
			'rating',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `date_added`";
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
				$data['limit'] = $this->config->get('config_pagination_admin');
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
	 *     'filter_article'   		=> 'Article Name',
	 *     'filter_author'    		=> 'John Doe',
	 *     'filter_comment'   		=> 'I love OpenCart',
	 *     'filter_customer'  		=> 'John Doe',
	 *     'filter_admin'  			=> 1,
	 *     'filter_comment_type'	=> 1,
	 *     'filter_status'    		=> 1,
	 *     'filter_rating_from' 	=> 1,
	 *     'filter_rating_to'   	=> 5,
	 *     'filter_date_from' 		=> '2021-01-01',
	 *     'filter_date_to'   		=> '2021-01-31',
	 *     'start'            		=> 0,
	 *     'limit'            		=> 10
	 * ];
	 *
	 * $this->load->model('cms/comment');
	 *
	 * $comment_total = $this->model_cms_comment->getTotalComments($filter_data);
	 */
	public function getTotalComments(array $data = []): int {
		$implode = [];

		if (isset($data['filter_admin']) && $data['filter_admin'] == '0') {
			$sql = "SELECT COUNT(*) AS `total`, `ac`.`article_comment_id`, `ac`.`article_id`, `ac`.`parent_id`, `ac`.`comment`, `ac`.`customer_id`, `ac`.`rating`, `ac`.`status`, `ac`.`date_added`, `ad`.`name` FROM `" . DB_PREFIX . "article_comment` `ac` LEFT JOIN `" . DB_PREFIX . "article` `a` ON (`ac`.`article_id` = `a`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`ac`.`article_id` = `ad`.`article_id`)";

			$implode[] = "`ac`.`customer_id` = '0'";
		} else {
			$sql = "SELECT COUNT(*) AS `total`, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `c`.`author`, `c`.`safe`, `c`.`commenter`, `ac`.`article_comment_id`, `ac`.`article_id`, `ac`.`parent_id`, `ac`.`comment`, `ac`.`customer_id`, `ac`.`rating`, `ac`.`status`, `ac`.`date_added`, `ad`.`name` FROM `" . DB_PREFIX . "article_comment` `ac` LEFT JOIN `" . DB_PREFIX . "article` `a` ON (`ac`.`article_id` = `a`.`article_id`) LEFT JOIN `" . DB_PREFIX . "article_description` `ad` ON (`ac`.`article_id` = `ad`.`article_id`) LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ac`.`customer_id` = `c`.`customer_id`)";

			if (isset($data['filter_admin']) && $data['filter_admin'] == '1') {
				$implode[] = "`ac`.`customer_id` > '0'";
			}

			if (!empty($data['filter_customer'])) {
				$implode[] = "LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_customer']) . '%') . "'";
			}

			if (!empty($data['filter_author'])) {
				$implode[] = "LCASE(`c`.`author`) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_author']) . '%') . "'";
			}
		}

		if (!empty($data['filter_article'])) {
			$implode[] = "LCASE(`ad`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_article']) . '%') . "'";
		}

		if (!empty($data['filter_comment'])) {
			$implode[] = "LCASE(`ac`.`comment`) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_comment']) . '%') . "'";
		}

		if (isset($data['filter_comment_type']) && $data['filter_comment_type'] != '') {
			if ($data['filter_comment_type']) {
				$implode[] = "`ac`.`parent_id` > '0'";
			} else {
				$implode[] = "`ac`.`parent_id` = '0'";
			}
		}

		if (isset($data['filter_status']) && $data['filter_status'] != '') {
			$implode[] = "`ac`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_rating_from'])) {
			$implode[] = "`ac`.`rating` >= '" . (int)$data['filter_rating_from'] . "'";
		}

		if (!empty($data['filter_rating_to'])) {
			$implode[] = "`ac`.`rating` <= '" . (int)$data['filter_rating_to'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`ac`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`ac`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$implode[] = "`ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Article select options.
	 *
	 * Get the article records from the database.
	 *
	 * @return array<int, array<int, string>> data array of article_description names.
	 *
	 * @example
	 *
	 * $this->load->model('cms/comment');
	 *
	 * $results = $this->model_cms_comment->getArticleSelectOptions();
	 */
	public function getArticleSelectOptions(): array {
		$results = [];

		$sql = "SELECT * FROM `" . DB_PREFIX . "article_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		if ($query->num_rows) {
			foreach ($query->rows as $row) {
				$results[$row['article_id']] = [
					'article_id' 	=> $row['article_id'],
					'name' 			=> $row['name']
				];
			}
		}

		return $results;
	}

	/**
	 * Get Likes
	 *
	 * Retrieve article_rating records from the database.
	 * The returned array is grouped by rating, and the "rating" field contains either 0 or 1,
	 * representing 'dislike' or 'like' respectively. So there are always exactly 2 rows returned.
	 *
	 * ------------------------
	 * | row | rating | total |
	 * ------------------------
	 * |  0  |   0    |   1   |
	 * |  1  |   1    |   4   |
	 *
	 * The number of likes in the above example is 4. The number of dislikes is 1, so
	 * the total_rating would be calculated as 4 - 1 = 3.
	 *
	 * @param int $article_id         field in the article_rating table.
	 * @param int $article_comment_id field in the article_rating table.
	 *
	 * @return array<int, array<string, mixed>> rating records grouped by rating.
	 *
	 * @example
	 *
	 * $this->load->model('cms/comment');
	 *
	 * $results = $this->model_cms_comment->getLikes($article_id);
	 * $results = $this->model_cms_comment->getLikes($article_id, $article_comment_id);
	 */
	public function getLikes(int $article_id, int $article_comment_id = 0): array {
		$sql = "SELECT rating, COUNT(*) AS `total` FROM `" . DB_PREFIX . "article_rating` WHERE `article_id` = '" . (int)$article_id . "'";

		if (isset($article_comment_id)) {
			$sql .= " AND `article_comment_id` = '" . (int)$article_comment_id . "'";
		}

		$sql .= " GROUP BY rating";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}

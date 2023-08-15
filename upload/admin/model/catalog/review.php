<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Review
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Review extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addReview(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "review` SET `author` = '" . $this->db->escape((string)$data['author']) . "', `product_id` = '" . (int)$data['product_id'] . "', `text` = '" . $this->db->escape(strip_tags((string)$data['text'])) . "', `rating` = '" . (int)$data['rating'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `date_added` = '" . $this->db->escape((string)$data['date_added']) . "'");

		$review_id = $this->db->getLastId();

		// Update product rating
		$this->load->model('catalog/product');

		$this->model_catalog_product->editRating($data['product_id'], $this->getRating($data['product_id']));

		$this->cache->delete('product');

		return $review_id;
	}

	/**
	 * @param int   $review_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editReview(int $review_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "review` SET `author` = '" . $this->db->escape((string)$data['author']) . "', `product_id` = '" . (int)$data['product_id'] . "', `text` = '" . $this->db->escape(strip_tags((string)$data['text'])) . "', `rating` = '" . (int)$data['rating'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `date_added` = '" . $this->db->escape((string)$data['date_added']) . "', `date_modified` = NOW() WHERE `review_id` = '" . (int)$review_id . "'");

		// Update product rating
		$this->load->model('catalog/product');

		$this->model_catalog_product->editRating($data['product_id'], $this->getRating($data['product_id']));

		$this->cache->delete('product');
	}

	/**
	 * @param int $review_id
	 *
	 * @return void
	 */
	public function deleteReview(int $review_id): void {
		$review_info = $this->getReview($review_id);

		if ($review_info) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "review` WHERE `review_id` = '" . (int)$review_info['review_id'] . "'");

			// Update product rating
			$this->load->model('catalog/product');

			$this->model_catalog_product->editRating($review_info['product_id'], $this->getRating($review_info['product_id']));

			$this->cache->delete('product');
		}
	}

	/**
	 * @param int $review_id
	 *
	 * @return array
	 */
	public function getReview(int $review_id): array {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.`name` FROM `" . DB_PREFIX . "product_description` pd WHERE pd.`product_id` = r.`product_id` AND pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS product FROM `" . DB_PREFIX . "review` r WHERE r.`review_id` = '" . (int)$review_id . "'");

		return $query->row;
	}

	/**
	 * @param int $product_id
	 *
	 * @return int
	 */
	public function getRating(int $product_id): int {
		$query = $this->db->query("SELECT AVG(`rating`) AS `total` FROM `" . DB_PREFIX . "review` WHERE `product_id` = '" . (int)$product_id . "' AND `status` = '1'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getReviews(array $data = []): array {
		$sql = "SELECT r.`review_id`, pd.`name`, r.`author`, r.`rating`, r.`status`, r.`date_added` FROM `" . DB_PREFIX . "review` r LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (r.`product_id` = pd.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.`name` LIKE '" . $this->db->escape((string)$data['filter_product'] . '%') . "'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.`author` LIKE '" . $this->db->escape((string)$data['filter_author'] . '%') . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND r.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(r.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(r.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$sort_data = [
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.`date_added`";
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
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalReviews(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "review` r LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (r.`product_id` = pd.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.`name` LIKE '" . $this->db->escape((string)$data['filter_product'] . '%') . "'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.`author` LIKE '" . $this->db->escape((string)$data['filter_author'] . '%') . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND r.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(r.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(r.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * @return int
	 */
	public function getTotalReviewsAwaitingApproval(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "review` WHERE `status` = '0'");

		return (int)$query->row['total'];
	}
}

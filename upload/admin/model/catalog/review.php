<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Review
 *
 * Can be loaded using $this->load->model('catalog/review');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Review extends \Opencart\System\Engine\Model {
	/**
	 * Add Review
	 *
	 * Create a new review record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new review record
	 *
	 * @example
	 *
	 * $review_data = [
	 *     'author'     => 'Author Name',
	 *     'product_id' => 1,
	 *     'text'       => 'Review Text',
	 *     'rating'     => 4,
	 *     'status'     => 0,
	 *     'date_added' => '2021-01-01'
	 * ];
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $review_id = $this->model_catalog_review->addReview($review_data);
	 */
	public function addReview(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "review` SET `author` = '" . $this->db->escape((string)$data['author']) . "', `product_id` = '" . (int)$data['product_id'] . "', `text` = '" . $this->db->escape(strip_tags((string)$data['text'])) . "', `rating` = '" . (int)$data['rating'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_added` = '" . $this->db->escape((string)$data['date_added']) . "'");

		$review_id = $this->db->getLastId();

		// Update product rating
		$this->load->model('catalog/product');

		$this->model_catalog_product->editRating($data['product_id'], $this->model_catalog_review->getRating($data['product_id']));

		$this->cache->delete('product');

		return $review_id;
	}

	/**
	 * Edit Review
	 *
	 * Edit review record in the database.
	 *
	 * @param int                  $review_id primary key of the review record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $review_data = [
	 *     'author'     => 'Author Name',
	 *     'product_id' => 1,
	 *     'text'       => 'Review Text',
	 *     'rating'     => 4,
	 *     'status'     => 1,
	 * ];
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $this->model_catalog_review->editReview($review_id, $review_data);
	 */
	public function editReview(int $review_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "review` SET `author` = '" . $this->db->escape((string)$data['author']) . "', `product_id` = '" . (int)$data['product_id'] . "', `text` = '" . $this->db->escape(strip_tags((string)$data['text'])) . "', `rating` = '" . (int)$data['rating'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_added` = '" . $this->db->escape((string)$data['date_added']) . "', `date_modified` = NOW() WHERE `review_id` = '" . (int)$review_id . "'");

		// Update product rating
		$this->load->model('catalog/product');

		$this->model_catalog_product->editRating($data['product_id'], $this->model_catalog_review->getRating($data['product_id']));

		$this->cache->delete('product');
	}

	/**
	 * Edit Status
	 *
	 * Edit review status record in the database.
	 *
	 * @param int  $review_id primary key of the product record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $this->model_catalog_review->editStatus($review_id, $status);
	 */
	public function editStatus(int $review_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "review` SET `status` = '" . (bool)$status . "' WHERE `review_id` = '" . (int)$review_id . "'");
	}

	/**
	 * Delete Review
	 *
	 * Delete review record in the database.
	 *
	 * @param int $review_id primary key of the review record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $this->model_catalog_review->deleteReview($review_id);
	 */
	public function deleteReview(int $review_id): void {
		$review_info = $this->getReview($review_id);

		if ($review_info) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "review` WHERE `review_id` = '" . (int)$review_info['review_id'] . "'");

			// Update product rating
			$this->load->model('catalog/product');

			$this->model_catalog_product->editRating($review_info['product_id'], $this->model_catalog_review->getRating($review_info['product_id']));

			$this->cache->delete('product');
		}
	}

	/**
	 * Delete Reviews By Product ID
	 *
	 * Delete reviews by product records in the database.
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $this->model_catalog_review->deleteReviewsByProductId($product_id);
	 */
	public function deleteReviewsByProductId(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "review` WHERE `product_id` = '" . (int)$product_id . "'");

		$this->cache->delete('product');
	}

	/**
	 * Get Review
	 *
	 * Get the record of the review record in the database.
	 *
	 * @param int $review_id primary key of the review record
	 *
	 * @return array<string, mixed> review record that has review ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $review_info = $this->model_catalog_review->getReview($review_id);
	 */
	public function getReview(int $review_id): array {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT `pd`.`name` FROM `" . DB_PREFIX . "product_description` `pd` WHERE `pd`.`product_id` = `r`.`product_id` AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `product` FROM `" . DB_PREFIX . "review` `r` WHERE `r`.`review_id` = '" . (int)$review_id . "'");

		return $query->row;
	}

	/**
	 * Get Rating
	 *
	 * Get the rating of the review record in the database.
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return int total number of rating records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $rating_info = $this->model_catalog_review->getRating($product_id);
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
	 * Get Reviews
	 *
	 * Get the record of the review records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> review records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_product'   => 'Product Name',
	 *     'filter_author'    => 'Author Name',
	 *     'filter_status'    => 1,
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'sort'             => 'DESC',
	 *     'order'            => 'r.date_added',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $results = $this->model_catalog_review->getReviews($filter_data);
	 */
	public function getReviews(array $data = []): array {
		$sql = "SELECT `r`.`review_id`, `pd`.`name`, `r`.`author`, `r`.`rating`, `r`.`status`, `r`.`date_added` FROM `" . DB_PREFIX . "review` `r` LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`r`.`product_id` = `pd`.`product_id`) WHERE `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product'])) {
			$sql .= " AND LCASE(`pd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_product']) . '%') . "'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND LCASE(`r`.`author`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_author']) . '%') . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `r`.`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(`r`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(`r`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$sort_data = [
			'name'       => 'pd.name',
			'author'     => 'r.author',
			'rating'     => 'r.rating',
			'status'     => 'r.status',
			'date_added' => 'r.date_added'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `r`.`date_added`";
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
	 * Get Total Reviews
	 *
	 * Get the total number of review records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of review records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_product'   => 'Product Name',
	 *     'filter_author'    => 'Author Name',
	 *     'filter_status'    => 1,
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'sort'             => 'DESC',
	 *     'order'            => 'r.date_added',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $review_total = $this->model_catalog_review->getTotalReviews($filter_data);
	 */
	public function getTotalReviews(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "review` `r` LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`r`.`product_id` = `pd`.`product_id`) WHERE `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product'])) {
			$sql .= " AND LCASE(`pd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_product']) . '%') . "'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND LCASE(`r`.`author`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_author']) . '%') . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `r`.`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(`r`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(`r`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Reviews Awaiting Approval
	 *
	 * Get the total number of awaiting approvals on review records in the database.
	 *
	 * @return int total number of reviews awaiting approval records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $review_total = $this->model_catalog_review->getTotalReviewsAwaitingApproval());
	 */
	public function getTotalReviewsAwaitingApproval(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "review` WHERE `status` = '0'");

		return (int)$query->row['total'];
	}
}

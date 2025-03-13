<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Identifier
 *
 * Can be loaded using $this->load->model('catalog/identifier');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Identifier extends \Opencart\System\Engine\Model {
	/**
	 * Add Identifier
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
	public function addIdentifier(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "identifier` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$identifier_id = $this->db->getLastId();

		$this->cache->delete('identifier');

		return $identifier_id;
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
	 *     'date_added' => '2021-01-01'
	 * ];
	 *
	 * $this->load->model('catalog/review');
	 *
	 * $this->model_catalog_review->editReview($review_id, $review_data);
	 */
	public function editIdentifier(int $identifier_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "identifier` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `identifier_id` = '" . (int)$identifier_id . "'");

		$this->cache->delete('identifier');
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
	public function deleteIdentifier(int $identifier_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "identifier` WHERE `identifier_id` = '" . (int)$identifier_id . "'");

		$this->cache->delete('identifier');
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
	public function getIdentifier(int $identifier_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "identifier` WHERE `identifier_id` = '" . (int)$identifier_id . "'");

		return $query->row;
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
	public function getIdentifiers(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "identifier` ORDER BY `name` ASC";

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
	public function getTotalIdentifiers(array $data = []): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "identifier`");

		return (int)$query->row['total'];
	}
}

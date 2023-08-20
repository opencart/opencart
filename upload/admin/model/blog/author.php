<?php
namespace Opencart\Admin\Model\Blog;
/**
 *  Class Author
 *
 * @package Opencart\Admin\Model\Design
 */
class Author extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function add(array $data): int {
		$this->db->query(
			"INSERT INTO `" . DB_PREFIX . "blog_author` SET " .
			"`fullname` = '" . $this->db->escape((string)$data['fullname']) . "', " .
			"`email` = '" . $this->db->escape((string)$data['email']) . "'," .
			"`photo` = '" . $this->db->escape((string)$data['photo']) . "'," .
			"`date_added` = NOW()," .
			"`date_modified` = NOW()"
		);

		$blog_author_id = $this->db->getLastId();

		return $blog_author_id;
	}

	/**
	 * @param int   $blog_author_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function edit(int $blog_author_id, array $data): void {
		$this->db->query(
			"UPDATE `" . DB_PREFIX . "blog_author` SET " .
			"`fullname` = '" . $this->db->escape((string)$data['fullname']) . "', " .
			"`email` = '" . $this->db->escape((string)$data['email']) . "'," .
			"`photo` = '" . $this->db->escape((string)$data['photo']) . "'," .
			"`date_modified` = NOW() ".
			"where blog_author_id = '" . (int)$blog_author_id . "'"
		);
	}

	/**
	 * @param int $blog_author_id
	 *
	 * @return void
	 */
	public function delete(int $blog_author_id): void {
		// Delete the author.
		$this->db->query("DELETE FROM `" . DB_PREFIX . "blog_author` WHERE `blog_author_id` = '" . (int)$blog_author_id . "'");
		// Remove the all relation between the author and his/her posts.
		$this->db->query("UPDATE `" . DB_PREFIX . "blog_post` SET `blog_author_id` = NULL WHERE `blog_author_id` = '" . (int)$blog_author_id . "'");
	}

	/**
	 * @param int $blog_author_id
	 *
	 * @return array
	 */
	public function getAuthor(int $blog_author_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "blog_author` WHERE `blog_author_id` = '" . (int)$blog_author_id . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getAuthors(array $data = []): array {
		$sql = "SELECT *  FROM `" . DB_PREFIX . "blog_author`";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE `fullname` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		// Sorting fields.
		$sort_data = [
			'blog_author_id'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `blog_author_id`";
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
	public function getTotalAuthors(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "blog_author`");

		return (int)$query->row['total'];
	}
}

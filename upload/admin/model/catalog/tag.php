<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Tag
 *
 * Can be loaded using $this->load->model('catalog/tag');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Tag extends \Opencart\System\Engine\Model {
	/**
	 * Add Tag
	 *
	 * Create a new tag record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new tagrecord
	 *
	 * @example
	 *
	 * $tag_data = [
	 *     'tag' => 'tag'
	 * ];
	 *
	 * $this->load->model('catalog/tag');
	 *
	 * $tag_id = $this->model_catalog_tag->addTag($tag_data);
	 */
	public function addTag(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tag` SET `tag` = '" . $this->db->escape($data['tag']) . "'");

		return $this->db->getLastId();
	}

	/**
	 * Edit Tag
	 *
	 * Edit tag record in the database.
	 *
	 * @param int                  $tag_id primary key of the tag record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $tag_data = [
	 *     'tag' => 'tag'
	 * ];
	 *
	 * $this->load->model('catalog/tag');
	 *
	 * $this->model_catalog_tag->editTag($tag_id, $tag_data);
	 */
	public function editTag(int $tag_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "tag` SET `tag` = '" . $this->db->escape($data['tag']) . "' WHERE `tag_id` = '" . (int)$tag_id . "'");
	}

	/**
	 * Delete Tag
	 *
	 * Delete tag record in the database.
	 *
	 * @param int $filter_group_id primary key of the tag record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/tag');
	 *
	 * $this->model_catalog_tag->deleteTag($tag_id);
	 */
	public function deleteTag(int $tag_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tag` WHERE `tag_id` = '" . (int)$tag_id . "'");
	}

	/**
	 * Get Tag
	 *
	 * Get the record of the tag record in the database.
	 *
	 * @param int $tag_id primary key of the tag record
	 *
	 * @return array<string, mixed> tag record that has tag ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/tag');
	 *
	 * $tag_info = $this->model_catalog_tag->getFilterGroup($tag_id);
	 */
	public function getTag(int $tag_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tag` WHERE = '" . (int)$tag_id . "'");

		return $query->row;
	}

	/**
	 * Get Tags
	 *
	 * Get the record of the tag records in the database.
	 *
	 * @param array<string, mixed> $data array of tags
	 *
	 * @return array<int, array<string, mixed>> tag records
	 *
	 * @example
	 *
	 * $tag_data = [
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('catalog/tag');
	 *
	 * $results = $this->model_catalog_tag->getTag($tag_data);
	 */
	public function getTags(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "tag`";

		if (!empty($data['filter_tag'])) {
			$sql .= " WHERE LCASE(`tag`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_tag'])) . "'";
		}

		$sql .= " ORDER BY `tag` ASC";

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
	 * Get Total Tags
	 *
	 * Get the total number of tag records in the database.
	 *
	 * @return int total number of tag records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/tag');
	 *
	 * $tag_total = $this->model_catalog_tag->getTotalTags();
	 */
	public function getTotalTags(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "tag`";

		if (!empty($data['filter_tag'])) {
			$sql .= " WHERE LCASE(`tag`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_tag'])) . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}

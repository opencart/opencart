<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Model\Cms
 */
class Topic extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int $topic
	 */
	public function addTopic(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "topic` SET `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "'");

		$topic_id = $this->db->getLastId();

		foreach ($data['topic_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "topic_description` SET `topic_id` = '" . (int)$topic_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape((string)$value['image']) . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['topic_store'])) {
			foreach ($data['topic_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "topic_to_store` SET `topic_id` = '" . (int)$topic_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		foreach ($data['topic_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'topic_id', `value`= '" . (int)$topic_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
			}
		}

		$this->cache->delete('topic');

		return $topic_id;
	}

	/**
	 * @param int   $topic_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editTopic(int $topic_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "topic` SET `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "' WHERE `topic_id` = '" . (int)$topic_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_description` WHERE `topic_id` = '" . (int)$topic_id . "'");

		foreach ($data['topic_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "topic_description` SET `topic_id` = '" . (int)$topic_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape((string)$value['image']) . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_to_store` WHERE `topic_id` = '" . (int)$topic_id . "'");

		if (isset($data['topic_store'])) {
			foreach ($data['topic_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "topic_to_store` SET `topic_id` = '" . (int)$topic_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'topic_id' AND `value` = '" . (int)$topic_id . "'");

		foreach ($data['topic_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'topic_id', `value` = '" . (int)$topic_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
			}
		}

		$this->cache->delete('topic');
	}

	/**
	 * @param int $topic_id
	 *
	 * @return void
	 */
	public function deleteTopic(int $topic_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic` WHERE `topic_id` = '" . (int)$topic_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_description` WHERE `topic_id` = '" . (int)$topic_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_to_store` WHERE `topic_id` = '" . (int)$topic_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'topic_id' AND `value` = '" . (int)$topic_id . "'");

		$this->cache->delete('topic');
	}

	/**
	 * @param int $topic_id
	 *
	 * @return array
	 */
	public function getTopic(int $topic_id): array {
		$sql = "SELECT DISTINCT * FROM `" . DB_PREFIX . "topic` `t` LEFT JOIN `" . DB_PREFIX . "topic_description` `td` ON (`t`.`topic_id` = `td`.`topic_id`) WHERE `t`.`topic_id` = '" . (int)$topic_id . "' AND `td`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$topic_data = $this->cache->get('topic.'. md5($sql));

		if (!$topic_data) {
			$query = $this->db->query($sql);

			$topic_data = $query->row;

			$this->cache->set('topic.'. md5($sql), $topic_data);
		}

		return $topic_data;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getTopics(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "topic` `t` LEFT JOIN `" . DB_PREFIX . "topic_description` `td` ON (`t`.`topic_id` = `td`.`topic_id`) WHERE `td`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = [
			'td.name',
			't.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `t`.`sort_order`";
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

		$topic_data = $this->cache->get('topic.'. md5($sql));

		if (!$topic_data) {
			$query = $this->db->query($sql);

			$topic_data = $query->rows;

			$this->cache->set('topic.'. md5($sql), $topic_data);
		}

		return $topic_data;
	}

	/**
	 * @param int $topic_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $topic_id): array {
		$topic_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "topic_description` WHERE `topic_id` = '" . (int)$topic_id . "'");

		foreach ($query->rows as $result) {
			$topic_description_data[$result['language_id']] = [
				'image'            => $result['image'],
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			];
		}

		return $topic_description_data;
	}

	/**
	 * @param int $topic_id
	 *
	 * @return array
	 */
	public function getSeoUrls(int $topic_id): array {
		$topic_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'topic_id' AND `value` = '" . (int)$topic_id . "'");

		foreach ($query->rows as $result) {
			$topic_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $topic_seo_url_data;
	}

	/**
	 * @param int $topic_id
	 *
	 * @return array
	 */
	public function getStores(int $topic_id): array {
		$topic_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "topic_to_store` WHERE `topic_id` = '" . (int)$topic_id . "'");

		foreach ($query->rows as $result) {
			$topic_store_data[] = $result['store_id'];
		}

		return $topic_store_data;
	}

	/**
	 * @return int
	 */
	public function getTotalTopics(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "topic`");

		return (int)$query->row['total'];
	}
}

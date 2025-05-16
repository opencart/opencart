<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Topic
 *
 * Can be loaded using $this->load->model('cms/topic');
 *
 * @package Opencart\Admin\Model\Cms
 */
class Topic extends \Opencart\System\Engine\Model {
	/**
	 * Add Topic
	 *
	 * Create a new topic record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new topic record
	 *
	 * @example
	 *
	 * $topic_data = [
	 *     'topic_description' => [],
	 *     'sort_order'        => 0,
	 *     'status'            => 0
	 * ];
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $topic_id = $this->model_cms_topic->addTopic($topic_data);
	 */
	public function addTopic(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "topic` SET `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$topic_id = $this->db->getLastId();

		// Description
		foreach ($data['topic_description'] as $language_id => $value) {
			$this->model_cms_topic->addDescription($topic_id, $language_id, $value);
		}

		// Store
		if (isset($data['topic_store'])) {
			foreach ($data['topic_store'] as $store_id) {
				$this->model_cms_topic->addStore($topic_id, $store_id);
			}
		}

		// SEO
		$this->load->model('design/seo_url');

		foreach ($data['topic_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->model_design_seo_url->addSeoUrl('topic_id', $topic_id, $keyword, $store_id, $language_id);
			}
		}

		// Layouts
		if (isset($data['topic_layout'])) {
			foreach ($data['topic_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->model_cms_topic->addLayout($topic_id, $store_id, $layout_id);
				}
			}
		}

		$this->cache->delete('topic');

		return $topic_id;
	}

	/**
	 * Edit Topic
	 *
	 * Edit topic record in the database.
	 *
	 * @param int                  $topic_id primary key of the topic record
	 * @param array<string, mixed> $data     array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $topic_data = [
	 *     'topic_description' => [],
	 *     'sort_order'        => 0,
	 *     'status'            => 1
	 * ];
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->editTopic($topic_id, $topic_data);
	 */
	public function editTopic(int $topic_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "topic` SET `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `topic_id` = '" . (int)$topic_id . "'");

		// Description
		$this->model_cms_topic->deleteDescriptions($topic_id);

		foreach ($data['topic_description'] as $language_id => $value) {
			$this->model_cms_topic->addDescription($topic_id, $language_id, $value);
		}

		// Store
		$this->model_cms_topic->deleteStores($topic_id);

		if (isset($data['topic_store'])) {
			foreach ($data['topic_store'] as $store_id) {
				$this->model_cms_topic->addStore($topic_id, $store_id);
			}
		}

		// SEO
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('topic_id', $topic_id);

		foreach ($data['topic_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->model_design_seo_url->addSeoUrl('topic_id', $topic_id, $keyword, $store_id, $language_id);
			}
		}

		// Layouts
		$this->model_cms_topic->deleteLayouts($topic_id);

		if (isset($data['topic_layout'])) {
			foreach ($data['topic_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->model_cms_topic->addLayout($topic_id, $store_id, $layout_id);
				}
			}
		}

		$this->cache->delete('topic');
	}

	/**
	 * Delete Topic
	 *
	 * Delete topic record in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->deleteTopic($topic_id);
	 */
	public function deleteTopic(int $topic_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic` WHERE `topic_id` = '" . (int)$topic_id . "'");

		$this->model_cms_topic->deleteDescriptions($topic_id);
		$this->model_cms_topic->deleteStores($topic_id);

		// SEO
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('topic_id', $topic_id);

		$this->model_cms_topic->deleteLayouts($topic_id);

		$this->cache->delete('topic');
	}

	/**
	 * Get Topic
	 *
	 * Get the record of the topic record in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return array<string, mixed> topic records that have topic ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $topic_info = $this->model_cms_topic->getTopic($topic_id);
	 */
	public function getTopic(int $topic_id): array {
		$sql = "SELECT DISTINCT * FROM `" . DB_PREFIX . "topic` `t` LEFT JOIN `" . DB_PREFIX . "topic_description` `td` ON (`t`.`topic_id` = `td`.`topic_id`) WHERE `t`.`topic_id` = '" . (int)$topic_id . "' AND `td`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$topic_data = $this->cache->get('topic.' . md5($sql));

		if (!$topic_data) {
			$query = $this->db->query($sql);

			$topic_data = $query->row;

			$this->cache->set('topic.' . md5($sql), $topic_data);
		}

		return $topic_data;
	}

	/**
	 * Get Topics
	 *
	 * Get the record of the topic records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> topic records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 't.sort_order',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $topics = $this->model_cms_topic->getTopics($filter_data);
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

		$key = md5($sql);

		$topic_data = $this->cache->get('topic.' . $key);

		if (!$topic_data) {
			$query = $this->db->query($sql);

			$topic_data = $query->rows;

			$this->cache->set('topic.' . $key, $topic_data);
		}

		return $topic_data;
	}

	/**
	 * Get Total Topics
	 *
	 * Get the total number of topic records in the database.
	 *
	 * @return int total number of topic records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 't.sort_order',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $topic_total = $this->model_cms_topic->getTotalTopics($filter_data);
	 */
	public function getTotalTopics(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "topic`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new topic description record in the database.
	 *
	 * @param int                  $topic_id    primary key of the topic record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $topic_data['topic_description'] = [
	 *     'image'            => 'topic_image',
	 *     'name'             => 'Topic Name',
	 *     'description'      => 'Topic Description',
	 *     'meta_title'       => 'Meta Title',
	 *     'meta_description' => 'Meta Description',
	 *     'meta_keyword'     => 'Meta Keyword'
	 * ];
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->addDescription($topic_id, $language_id, $topic_data);
	 */
	public function addDescription(int $topic_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "topic_description` SET `topic_id` = '" . (int)$topic_id . "', `language_id` = '" . (int)$language_id . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `name` = '" . $this->db->escape($data['name']) . "', `description` = '" . $this->db->escape($data['description']) . "', `meta_title` = '" . $this->db->escape($data['meta_title']) . "', `meta_description` = '" . $this->db->escape($data['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($data['meta_keyword']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete topic description records in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->deleteDescriptions($topic_id);
	 */
	public function deleteDescriptions(int $topic_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_description` WHERE `topic_id` = '" . (int)$topic_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete topic descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the topic description records in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return array<int, array<string, mixed>> description records that have topic ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $results = $this->model_cms_topic->getDescriptions($topic_id);
	 */
	public function getDescriptions(int $topic_id): array {
		$topic_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "topic_description` WHERE `topic_id` = '" . (int)$topic_id . "'");

		foreach ($query->rows as $result) {
			$topic_description_data[$result['language_id']] = $result;
		}

		return $topic_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the topic descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $topic_description = $this->model_cms_topic->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "topic_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Store
	 *
	 * Create a new topic store record in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->addStore($topic_id, $store_id);
	 */
	public function addStore(int $topic_id, int $store_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "topic_to_store` SET `topic_id` = '" . (int)$topic_id . "', `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Stores
	 *
	 * Delete topic store records in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->deleteStores($topic_id);
	 */
	public function deleteStores(int $topic_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_to_store` WHERE `topic_id` = '" . (int)$topic_id . "'");
	}

	/**
	 * Get Stores
	 *
	 * Get the record of the topic store records in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return array<int, int> store records that have topic ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $topic_store = $this->model_cms_topic->getStores($topic_id);
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
	 * Add Layout
	 *
	 * Create a new topic layout record in the database.
	 *
	 * @param int $topic_id  primary key of the topic record
	 * @param int $store_id  primary key of the store record
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->addLayout($topic_id, $store_id, $layout_id);
	 */
	public function addLayout(int $topic_id, int $store_id, int $layout_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "topic_to_layout` SET `topic_id` = '" . (int)$topic_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts
	 *
	 * Delete topic layout records in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->deleteLayouts($topic_id);
	 */
	public function deleteLayouts(int $topic_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_to_layout` WHERE `topic_id` = '" . (int)$topic_id . "'");
	}

	/**
	 * Delete Layouts By Layout ID
	 *
	 * Delete topic layouts by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $this->model_cms_topic->deleteLayoutsByLayoutId($layout_id);
	 */
	public function deleteLayoutsByLayoutId(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "topic_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Get Layouts
	 *
	 * Get the record of the topic layout records in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return array<int, int> layout records that have topic ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $topic_layout = $this->model_cms_topic->getLayouts($topic_id);
	 */
	public function getLayouts(int $topic_id): array {
		$topic_id_layout_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "topic_to_layout` WHERE `topic_id` = '" . (int)$topic_id . "'");

		foreach ($query->rows as $result) {
			$topic_id_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $topic_id_layout_data;
	}

	/**
	 * Get Total Layouts By Layout ID
	 *
	 * Get the total number of topic layouts by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return int total number of layout records that have layout ID
	 */
	public function getTotalLayoutsByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "topic_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}
}

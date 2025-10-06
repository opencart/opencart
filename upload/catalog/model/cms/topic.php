<?php
namespace Opencart\Catalog\Model\Cms;
/**
 * Class Topic
 *
 * Can be called using $this->load->model('cms/topic');
 *
 * @package Opencart\Catalog\Model\Cms
 */
class Topic extends \Opencart\System\Engine\Model {
	/**
	 * Get Topic
	 *
	 * Get the record of the topic record in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return array<int, array<string, mixed>> topic record that has topic ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $topic_info = $this->model_cms_topic->getTopic($topic_id);
	 */
	public function getTopic(int $topic_id): array {
		$sql = "SELECT DISTINCT * FROM `" . DB_PREFIX . "topic` `t` LEFT JOIN `" . DB_PREFIX . "topic_description` `td` ON (`t`.`topic_id` = `td`.`topic_id`) LEFT JOIN `" . DB_PREFIX . "topic_to_store` `t2s` ON (`t`.`topic_id` = `t2s`.`topic_id`) WHERE `t`.`topic_id` = '" . (int)$topic_id . "' AND `td`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `t2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `t`.`status` = '1'";

		$key = md5($sql);

		$topic_data = $this->cache->get('topic.' . $key);

		if (!$topic_data) {
			$query = $this->db->query($sql);

			$topic_data = $query->row;

			$this->cache->set('topic.' . $key, $topic_data);
		}

		return $topic_data;
	}

	/**
	 * Get Topics
	 *
	 * Get the record of the topic records in the database.
	 *
	 * @return array<int, array<string, mixed>> topic records
	 *
	 * @example
	 *
	 * $this->load->model('cms/topic');
	 *
	 * $results = $this->model_cms_topic->getTopics();
	 */
	public function getTopics(): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "topic` `t` LEFT JOIN `" . DB_PREFIX . "topic_description` `td` ON (`t`.`topic_id` = `td`.`topic_id`) LEFT JOIN `" . DB_PREFIX . "topic_to_store` `t2s` ON (`t`.`topic_id` = `t2s`.`topic_id`) WHERE `td`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `t2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `t`.`status` = '1' ORDER BY `t`.`sort_order` DESC";

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
	 * Get Layout ID
	 *
	 * Get the record of the article layout record in the database.
	 *
	 * @param int $topic_id primary key of the topic record
	 *
	 * @return int total number of layout records that have article ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/article');
	 *
	 * $layout_id = $this->model_cms_article->getLayoutId($article_id);
	 */
	public function getLayoutId(int $topic_id): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "topic_to_layout` WHERE `topic_id` = '" . (int)$topic_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}

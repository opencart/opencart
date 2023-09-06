<?php
namespace Opencart\Catalog\Model\Cms;
/**
 * Class Topic
 *
 * @package Opencart\Catalog\Model\Cms
 */
class Topic extends \Opencart\System\Engine\Model {
	/**
	 * @param int $topic_id
	 *
	 * @return array
	 */
	public function getTopic(int $topic_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "topic` `t` LEFT JOIN `" . DB_PREFIX . "topic_description` `td` ON (`t`.`topic_id` = `td`.`topic_id`) LEFT JOIN `" . DB_PREFIX . "topic_to_store` `t2s` ON (`t`.`topic_id` = `t2s`.`topic_id`) WHERE `t`.`topic_id` = '" . (int)$topic_id . "' AND `td`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `t2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;
	}

	/**
	 * @return array
	 */
	public function getTopics(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "topic` `t` LEFT JOIN `" . DB_PREFIX . "topic_description` `td` ON (`t`.`topic_id` = `td`.`topic_id`) LEFT JOIN `" . DB_PREFIX . "topic_to_store` `t2s` ON (`t`.`topic_id` = `t2s`.`topic_id`) WHERE `td`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `t2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY `t`.`sort_order` DESC");

		return $query->rows;
	}
}

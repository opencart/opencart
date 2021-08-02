<?php
namespace Opencart\Catalog\Model\Account;
class Search extends \Opencart\System\Engine\Model {
	public function addSearch(array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_search` SET `store_id` = '" . (int)$this->config->get('config_store_id') . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `customer_id` = '" . (int)$data['customer_id'] . "', `keyword` = '" . $this->db->escape((string)$data['keyword']) . "', `category_id` = '" . (int)$data['category_id'] . "', `sub_category` = '" . (int)$data['sub_category'] . "', `description` = '" . (int)$data['description'] . "', `products` = '" . (int)$data['products'] . "', `ip` = '" . $this->db->escape((string)$data['ip']) . "', `date_added` = NOW()");
	}
}

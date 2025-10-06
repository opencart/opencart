<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Search
 *
 * Can be called using $this->load->model('account/search');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Search extends \Opencart\System\Engine\Model {
	/**
	 * Add Customer Search
	 *
	 * Create a new customer search record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $search_data = [
	 *     'customer_id'  => 1,
	 *     'keyword'      => '',
	 *     'category_id'  => 1,
	 *     'sub_category' => 0,
	 *     'description'  => 'Search Description',
	 *     'products'     => 1,
	 *     'ip'           => ''
	 * ];
	 *
	 * $this->load->model('account/search');
	 *
	 * $this->model_account_search->addSearch($search_data);
	 */
	public function addSearch(array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_search` SET `store_id` = '" . (int)$this->config->get('config_store_id') . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `customer_id` = '" . (int)$data['customer_id'] . "', `keyword` = '" . $this->db->escape($data['keyword']) . "', `category_id` = '" . (int)$data['category_id'] . "', `sub_category` = '" . (int)$data['sub_category'] . "', `description` = '" . (int)$data['description'] . "', `products` = '" . (int)$data['products'] . "', `ip` = '" . $this->db->escape($data['ip']) . "', `date_added` = NOW()");
	}
}

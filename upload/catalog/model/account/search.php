<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelAccountSearch extends Model {
	public function addSearch($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_search` SET `store_id` = '" . (int)$this->config->get('config_store_id') . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `customer_id` = '" . (int)$data['customer_id'] . "', `keyword` = '" . $this->db->escape($data['keyword']) . "', `category_id` = '" . (int)$data['category_id'] . "', `sub_category` = '" . (int)$data['sub_category'] . "', `description` = '" . (int)$data['description'] . "', `products` = '" . (int)$data['products'] . "', `ip` = '" . $this->db->escape($data['ip']) . "', `date_added` = NOW()");
	}
}

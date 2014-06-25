<?php
class ModelOpenbayEtsyProduct extends Model{
	public function getStatus($product_id) {
		return 0;
	}

	public function totalLinked(){
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total
				FROM `" . DB_PREFIX . "etsy_listing` `el`
				LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`el`.`product_id` = `p`.`product_id`)
				LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`)
				WHERE `el`.`status` = '1'
				AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	public function addLink($product_id, $etsy_item_id, $status_id = 0) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "etsy_listing` SET `product_id` = '" . (int)$product_id . "', `etsy_item_id` = '".$this->db->escape($etsy_item_id)."', `status` = '" . (int)$status_id . "', `created`  = now()");
	}

	public function loadLinked($limit = 100, $page = 1){
		$this->load->model('tool/image');

		$start = $limit * ($page - 1);

		$sql = "
		SELECT
			`el`.`etsy_item_id`,
			`p`.`product_id`,
			`p`.`sku`,
			`p`.`model`,
			`p`.`quantity`,
			`pd`.`name`
		FROM `" . DB_PREFIX . "etsy_listing` `el`
		LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`el`.`product_id` = `p`.`product_id`)
		LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`)
		WHERE `el`.`status` = '1'
		AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;

		$qry = $this->db->query($sql);

		$data = array();
		if($qry->num_rows){
			foreach($qry->rows as $row){
				$data[$row['ebay_item_id']] = array(
					'product_id'    => $row['product_id'],
					'sku'           => $row['sku'],
					'model'         => $row['model'],
					'qty'           => $row['quantity'],
					'name'          => $row['name'],
					'link_edit'     => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $row['product_id'], 'SSL'),
					'link_etsy'     => $this->config->get('ebay_itm_link').$row['ebay_item_id'],
				);
			}
		}

		return $data;
	}
}
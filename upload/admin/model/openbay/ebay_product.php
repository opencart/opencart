<?php
class ModelOpenbayEbayProduct extends Model {
	public function getTaxRate($class_id) {
		return $this->openbay->getTaxRate($class_id);
	}

	public function countImportImages() {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_image_import`");

		return $qry->num_rows;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'image') {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id'   => $product_option_value['product_option_value_id'],
						'option_value_id'           => $product_option_value['option_value_id'],
						'name'                      => $product_option_value['name'],
						'image'                     => $product_option_value['image'],
						'image_thumb'               => (!empty($product_option_value['image'])?$this->model_tool_image->resize($product_option_value['image'], 100, 100):''),
						'quantity'                  => $product_option_value['quantity'],
						'subtract'                  => $product_option_value['subtract'],
						'price'                     => $product_option_value['price'],
						'price_prefix'              => $product_option_value['price_prefix'],
						'points'                    => $product_option_value['points'],
						'points_prefix'             => $product_option_value['points_prefix'],
						'weight'                    => $product_option_value['weight'],
						'weight_prefix'             => $product_option_value['weight_prefix']
					);
				}

				$product_option_data[] = array(
					'product_option_id'     => $product_option['product_option_id'],
					'option_id'             => $product_option['option_id'],
					'name'                  => $product_option['name'],
					'type'                  => $product_option['type'],
					'product_option_value'  => $product_option_value_data,
					'required'              => $product_option['required']
				);
			}
		}

		return $product_option_data;
	}

	public function repairLinks() {
		//get distinct product id's where they are active
		$sql = $this->db->query("
			SELECT DISTINCT `product_id`
			FROM `" . DB_PREFIX . "ebay_listing`
			WHERE `status` = '1'");

		//loop over products and if count is more than 1, update all older entries to 0
		foreach($sql->rows as $row){
			$sql2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_listing` WHERE `product_id` = '".(int)$row['product_id']."' AND `status` = 1 ORDER BY `ebay_listing_id` DESC");

			if($sql2->num_rows > 1){
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_listing` SET `status` = 0  WHERE `product_id` = '".(int)$row['product_id']."'");
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_listing` SET `status` = 1  WHERE `ebay_listing_id` = '".(int)$sql2->row['ebay_listing_id']."'");
			}
		}
	}

	public function searchEbayCatalog($data) {

		if(!isset($data['page'])){ $page = 1; }else{ $page = $data['page']; }

		//validation for category id

		//validation for saerch term

	$response['data']   = $this->openbay->ebay->openbay_call('listing/searchCatalog/', array('page' => (int)$page, 'categoryId' => $data['categoryId'], 'search' => $data['search']));
		$response['error']  = $this->openbay->ebay->lasterror;
		$response['msg']    = $this->openbay->ebay->lastmsg;
		return $response;
	}
}
?>
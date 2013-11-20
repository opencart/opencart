<?php
class ModelAmazonusProduct extends Model {
	public function setStatus($insertionId, $statusString) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazonus_product`
			SET `status` = '" . $statusString . "'
			WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'
			");
	}

	public function getProductRows($insertionId) {
		return $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "amazonus_product`
			WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'
			")->rows;
	}

	public function getProduct($insertionId) {
		return $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "amazonus_product`
			WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'
			")->row;
	}

	public function linkItems(array $data) {
		foreach($data as $amazonusSku => $productId) {
			$varRow = $this->db->query("SELECT `var` FROM `" . DB_PREFIX . "amazonus_product`
				WHERE `sku` = '" . $amazonusSku . "' AND `product_id` = '" . (int)$productId . "'")->row;
			$var = isset($varRow['var']) ? $varRow['var'] : '';
			$this->linkProduct($amazonusSku, $productId, $var);
		}
	}

	public function insertError($data) {
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "amazonus_product_error`
			SET `sku` = '" . $this->db->escape($data['sku']) . "',
				`error_code` = '" . (int)$data['error_code'] . "',
				`message` = '" . $this->db->escape($data['message']) . "',
				`insertion_id` = '" . $this->db->escape($data['insertion_id']) . "'
				");

		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazonus_product`
			SET `status` = 'error'
			WHERE `sku` = '" . $this->db->escape($data['sku']) . "' AND `insertion_id` = '" . $this->db->escape($data['insertion_id']) . "'
			");
	 }

	public function deleteErrors($insertionId) {
		 $this->db->query("DELETE FROM `" . DB_PREFIX . "amazonus_product_error` WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'");
	 }

	public function setSubmitError($insertionId, $message) {
		$skuRows = $this->db->query("SELECT `sku`
			FROM `" . DB_PREFIX . "amazonus_product`
			WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'
			")->rows;

		foreach($skuRows as $skuRow) {
			$data = array(
				'sku' => $skuRow['sku'],
				'error_code' => '0',
				'message' => $message,
				'insertion_id' => $insertionId
			);
			$this->insertError($data);
		}
	 }

	public function linkProduct($amazonus_sku, $product_id, $var = '') {
		$count = $this->db->query("SELECT COUNT(*) as 'count' FROM `" . DB_PREFIX . "amazonus_product_link` WHERE `product_id` = '" . (int)$product_id . "' AND `amazonus_sku` = '" . $this->db->escape($amazonus_sku) . "' AND `var` = '" . $this->db->escape($var) . "' LIMIT 1")->row;
		if($count['count'] == 0) {
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "amazonus_product_link`
				SET `product_id` = '" . (int)$product_id . "', `amazonus_sku` = '" . $this->db->escape($amazonus_sku) . "', `var` = '" . $this->db->escape($var) . "'");
		}
	}

	public function getProductQuantity($product_id, $var = '') {
		$this->load->library('amazonus');

		$result = null;

		if ($var !== '' && $this->openbay->addonLoad('openstock')) {
			$this->load->model('tool/image');
			$this->load->model('openstock/openstock');
			$optionStocks = $this->model_openstock_openstock->getProductOptionStocks($product_id);

			$option = null;
			foreach ($optionStocks as $optionIterator) {
				if($optionIterator['var'] === $var) {
					$option = $optionIterator;
					break;
				}
			}

			if($option != null) {
				$result = $option['stock'];
			}
		} else {
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if (isset($product_info['quantity'])) {
				$result = $product_info['quantity'];
			}
		}
		return $result;
	}

	public function updateSearch($results) {
		foreach ($results as $result) {
			$resultsFound = count($result['results']);

			$data = json_encode($result['results']);

			$this->db->query("
				UPDATE " . DB_PREFIX . "amazonus_product_search
				SET matches = " . (int)$resultsFound . ",
					`data` = '" . $this->db->escape($data) . "',
					`status` = 'finished'
				WHERE product_id = " . (int)$result['product_id'] . "
				LIMIT 1
			");
		}
	}

	public function addListingReport($data) {
		$sql = "INSERT INTO " . DB_PREFIX . "amazonus_listing_report (sku, quantity, asin, price) VALUES ";

		$sqlValues = array();

		foreach ($data as $product) {
			$sqlValues[] = " ('" . $this->db->escape($product['sku']) . "', " . (int)$product['quantity'] . ", '" . $this->db->escape($product['asin']) . "', " . (double) $product['price'] . ") ";
		}

		$sql .= implode(',', $sqlValues);

		$this->db->query($sql);
	}

	public function removeListingReportLock($marketplace) {
		$this->config->set('openbay_amazonus_processing_listing_reports', false);

		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '0', serialized = 0 WHERE `key` = 'openbay_amazonus_processing_listing_reports'");
	}
}
?>
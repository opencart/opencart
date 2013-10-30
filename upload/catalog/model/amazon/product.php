<?php
class ModelAmazonProduct extends Model {
	public function setStatus($insertionId, $statusString) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazon_product`
			SET `status` = '" . $statusString . "'
			WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'
			");
	}

	public function getProductRows($insertionId) {
		return $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "amazon_product`
			WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'
			")->rows;
	}

	public function getProduct($insertionId) {
		return $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "amazon_product`
			WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'
			")->row;
	}

	public function linkItems(array $data) {
		foreach($data as $amazonSku => $productId) {
			$varRow = $this->db->query("SELECT `var` FROM `" . DB_PREFIX . "amazon_product`
				WHERE `sku` = '" . $amazonSku . "' AND `product_id` = '" . (int)$productId . "'")->row;
			$var = isset($varRow['var']) ? $varRow['var'] : '';
			$this->linkProduct($amazonSku, $productId, $var);
		}
	}

	public function insertError($data) {
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "amazon_product_error`
			SET `sku` = '" . $this->db->escape($data['sku']) . "',
				`error_code` = '" . (int)$data['error_code'] . "',
				`message` = '" . $this->db->escape($data['message']) . "',
				`insertion_id` = '" . $this->db->escape($data['insertion_id']) . "'
				");

		$this->db->query("
			UPDATE `" . DB_PREFIX . "amazon_product`
			SET `status` = 'error'
			WHERE `sku` = '" . $this->db->escape($data['sku']) . "' AND `insertion_id` = '" . $this->db->escape($data['insertion_id']) . "'
			");
	 }

	public function deleteErrors($insertionId) {
		 $this->db->query("DELETE FROM `" . DB_PREFIX . "amazon_product_error` WHERE `insertion_id` = '" . $this->db->escape($insertionId) . "'");
	 }

	public function setSubmitError($insertionId, $message) {
		$skuRows = $this->db->query("SELECT `sku`
			FROM `" . DB_PREFIX . "amazon_product`
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

	public function linkProduct($amazon_sku, $product_id, $var = '') {
		$count = $this->db->query("SELECT COUNT(*) as 'count' FROM `" . DB_PREFIX . "amazon_product_link` WHERE `product_id` = '" . (int)$product_id . "' AND `amazon_sku` = '" . $this->db->escape($amazon_sku) . "' AND `var` = '" . $this->db->escape($var) . "' LIMIT 1")->row;
		if($count['count'] == 0) {
			$this->db->query(
				"INSERT INTO `" . DB_PREFIX . "amazon_product_link`
				SET `product_id` = '" . (int)$product_id . "', `amazon_sku` = '" . $this->db->escape($amazon_sku) . "', `var` = '" . $this->db->escape($var) . "'");
		}
	}

	public function getProductQuantity($product_id, $var = '') {
		$this->load->library('amazon');

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
				UPDATE " . DB_PREFIX . "amazon_product_search
				SET matches = " . (int)$resultsFound . ",
					`data` = '" . $this->db->escape($data) . "',
					`status` = 'finished'
				WHERE product_id = " . (int)$result['product_id'] . " AND
					  marketplace = '" . $this->db->escape($result['marketplace']) . "'
				LIMIT 1
			");
		}
	}

	public function addListingReport($data) {
		$sql = "INSERT INTO " . DB_PREFIX . "amazon_listing_report (marketplace, sku, quantity, asin, price) VALUES ";

		$sqlValues = array();

		foreach ($data as $product) {
			$sqlValues[] = " ('" . $this->db->escape($product['marketplace']) . "', '" . $this->db->escape($product['sku']) . "', " . (int)$product['quantity'] . ", '" . $this->db->escape($product['asin']) . "', " . (double) $product['price'] . ") ";
		}

		$sql .= implode(',', $sqlValues);

		$this->db->query($sql);
	}

	public function removeListingReportLock($marketplace) {
		$marketplaces = $this->config->get('openbay_amazon_processing_listing_reports');

		if ($marketplaces && ($key = array_search($marketplace, $marketplaces)) !== false) {
			unset($marketplaces[$key]);

			$this->config->set('openbay_amazon_processing_listing_reports', $marketplaces);

			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($marketplaces)) . "', serialized = 1 WHERE `key` = 'openbay_amazon_processing_listing_reports'");
		}
	}
}

?>
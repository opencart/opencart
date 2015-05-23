<?php
class ModelOpenbayAmazonusListing extends Model {
	private $tabs = array();

	public function search($search_string) {

		$search_params = array(
			'search_string' => $search_string,
		);

		$results = json_decode($this->openbay->amazonus->call('productv3/search', $search_params), 1);

		$products = array();

		foreach ($results['Products'] as $result) {
			if ($result['price']['amount'] && $result['price']['currency']) {
				$price = $result['price']['amount'] . ' ' . $result['price']['currency'];
			} else {
				$price = '-';
			}

			$link = 'http://www.amazon.com/gp/product/' . $result['asin'] . '/';

			$products[] = array(
				'name' => $result['name'],
				'asin' => $result['asin'],
				'image' => $result['image'],
				'price' => $price,
				'link' => $link,
			);
		}

		return $products;
	}

	public function getProductByAsin($asin) {
		$data = array(
			'asin' => $asin,
		);

		$results = json_decode($this->openbay->amazonus->call('productv3/getProduct', $data), 1);

		return $results;
	}

	public function getBestPrice($asin, $condition) {
		$search_params = array(
			'asin' => $asin,
			'condition' => $condition,
		);

		$best_price = '';

		$result = json_decode($this->openbay->amazonus->call('productv3/getPrice', $search_params), 1);

		if (isset($result['Price']['Amount']) && $result['Price']['Currency'] && $this->currency->has($result['Price']['Currency'])) {
			$best_price['amount'] = number_format($this->currency->convert($result['Price']['Amount'], $result['Price']['Currency'], $this->config->get('config_currency')), 2, '.', '');
			$best_price['shipping'] = number_format($this->currency->convert($result['Price']['Shipping'], $result['Price']['Currency'], $this->config->get('config_currency')), 2, '.', '');
			$best_price['currency'] = $result['Price']['Currency'];
		}

		return $best_price;
	}

	public function simpleListing($data) {
		$request = array(
			'asin' => $data['asin'],
			'sku' => $data['sku'],
			'quantity' => $data['quantity'],
			'price' => $data['price'],
			'sale' => array(
				'price' => $data['sale_price'],
				'from' => $data['sale_from'],
				'to' => $data['sale_to'],
			),
			'condition' => $data['condition'],
			'condition_note' => $data['condition_note'],
			'start_selling' => $data['start_selling'],
			'restock_date' => $data['restock_date'],
			'response_url' => HTTPS_CATALOG . 'index.php?route=openbay/amazonus/listing',
			'product_id' => $data['product_id'],
		);

		$response = $this->openbay->amazonus->call('productv3/simpleListing', $request);
		$response = json_decode($response);
		if (empty($response)) {
			return array(
				'status' => 0,
				'message' => 'Problem connecting OpenBay: API'
			);
		}
		$response = (array)$response;

		if ($response['status'] === 1) {
			$this->db->query("
			REPLACE INTO `" . DB_PREFIX . "amazonus_product`
			SET `product_id` = " . (int)$data['product_id'] . ",
				`status` = 'uploaded',
				`version` = 3,
				`var` = ''
		");
		}

		return $response;
	}

	public function getBrowseNodes($request) {
		return $this->openbay->amazonus->call('productv3/getBrowseNodes', $request);
	}

	public function deleteSearchResults($product_ids) {
		$imploded_ids = array();

		foreach ($product_ids as $product_id) {
			$imploded_ids[] = (int)$product_id;
		}

		$imploded_ids = implode(',', $imploded_ids);

		$this->db->query("
			DELETE FROM " . DB_PREFIX .  "amazonus_product_search
			WHERE product_id IN ($imploded_ids)
		");
	}

	public function doBulkListing($data) {
		$this->load->model('catalog/product');
		$request = array();

		foreach($data['products'] as $product_id => $asin) {
			$product = $this->model_catalog_product->getProduct($product_id);

			if ($product) {
				$price = $product['price'];

				if ($this->config->get('openbay_amazonus_listing_tax_added') && $this->config->get('openbay_amazonus_listing_tax_added') > 0) {
					$price += $price * ($this->config->get('openbay_amazonus_listing_tax_added') / 100);
				}

				$request[] = array(
					'asin' => $asin,
					'sku' => $product['sku'],
					'quantity' => $product['quantity'],
					'price' => number_format($price, 2, ' . ', ''),
					'sale' => array(),
					'condition' => (isset($data['condition']) ? $data['condition'] : ''),
					'condition_note' => (isset($data['condition_note']) ? $data['condition_note'] : ''),
					'start_selling' => (isset($data['start_selling']) ? $data['start_selling'] : ''),
					'restock_date' => '',
					'response_url' => HTTPS_CATALOG . 'index.php?route=openbay/amazonus/listing',
					'product_id' => $product['product_id'],
				);
			}
		}

		if ($request) {
			$response = $this->openbay->amazonus->call('productv3/bulkListing', $request);

			$response = json_decode($response, 1);

			if ($response['status'] == 1) {
				foreach ($request as $product) {
					$this->db->query("
						REPLACE INTO `" . DB_PREFIX . "amazonus_product`
						SET `product_id` = " . (int)$product['product_id'] . ",
							`status` = 'uploaded',
							`var` = '',
							`version` = 3
					");
				}

				return true;
			}
		}

		return false;
	}

	public function doBulkSearch($search_data) {
		foreach ($search_data as $products) {
			foreach ($products as $product) {
				$this->db->query("
					REPLACE INTO " . DB_PREFIX . "amazonus_product_search (product_id, `status`)
					VALUES (" . (int)$product['product_id'] . ", 'searching')");
			}
		}

		$request_data = array(
			'search' => $search_data,
			'response_url' => HTTPS_CATALOG . 'index.php?route=openbay/amazonus/search'
		);

		$response = $this->openbay->amazonus->call('productv3/bulkSearch', $request_data);
	}
}
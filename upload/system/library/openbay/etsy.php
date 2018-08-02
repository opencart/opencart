<?php
namespace openbay;

final class Etsy {
	private $token;
    private $encryption_key;
    private $encryption_iv;
	private $url = 'https://api.openbaypro.io/';
	private $registry;
	private $logger;
	private $max_log_size = 50; //max log size in Mb

	public function __construct($registry) {
		$this->registry = $registry;
		$this->token = $this->config->get('etsy_token');
		$this->logging = $this->config->get('etsy_logging');

		if ($this->logging == 1) {
			$this->setLogger();
		}

		$this->setEncryptionKey($this->config->get('etsy_encryption_key'));
		$this->setEncryptionIv($this->config->get('etsy_encryption_iv'));
	}

	public function __get($name) {
		return $this->registry->get($name);
	}

    public function getEncryptionKey() {
        return $this->encryption_key;
    }

	public function setEncryptionKey($key) {
	    $this->encryption_key = $key;
    }

    public function getEncryptionIv() {
        return $this->encryption_iv;
    }

    public function setEncryptionIv($encryption_iv) {
        $this->encryption_iv = $encryption_iv;
    }

	public function resetConfig($token, $encryption_key) {
		$this->token = $token;
		$this->setEncryptionKey($encryption_key);
	}

	public function call($uri, $method, $data = array()) {
		if($this->config->get('etsy_status') == 1) {
			$headers = array ();
			$headers[] = 'X-Auth-Token: ' . $this->token;
			$headers[] = 'X-Endpoint-Version: 2';
			$headers[] = 'Content-Type: application/json';
			//$headers[] = 'Content-Length: '.strlen(json_encode($data));

			$defaults = array(
				CURLOPT_HEADER      	=> 0,
				CURLOPT_HTTPHEADER      => $headers,
				CURLOPT_URL             => $this->url . $uri,
				CURLOPT_USERAGENT       => "OpenBay Pro for Etsy/OpenCart",
				CURLOPT_FRESH_CONNECT   => 1,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_FORBID_REUSE    => 1,
				CURLOPT_TIMEOUT         => 180,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_SSL_VERIFYHOST  => 0,
				//CURLOPT_VERBOSE 		=> true,
				//CURLOPT_STDERR 			=> fopen(DIR_LOGS . 'curl_verbose.log', "w+")
			);

			if ($method == 'POST') {
				$defaults[CURLOPT_POST] = 1;
				$defaults[CURLOPT_POSTFIELDS] = json_encode($data);
			}

			$curl = curl_init();
			curl_setopt_array($curl, $defaults);

			$response = array();

			if (! $result = curl_exec($curl)) {
				$this->log('call() - Curl Failed ' . curl_error($curl) . ' ' . curl_errno($curl));

				return false;
			} else {
				$this->log('call() - Result of : "' . print_r($result, true) . '"');

				$encoding = mb_detect_encoding($result);

				if($encoding == 'UTF-8') {
					$result = preg_replace('/[^(\x20-\x7F)]*/', '', $result);
				}

				$result = json_decode($result, 1);

				$response['header_code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

				if(!empty($result)) {
					$response['data'] = $result;
				} else {
					$response['data'] = '';
				}
			}

			curl_close($curl);

			return $response;
		} else {
			$this->log('call() - OpenBay Pro / Etsy not active');

			return false;
		}
	}

	private function setLogger() {
		if (file_exists(DIR_LOGS . 'etsylog.log')) {
			if (filesize(DIR_LOGS . 'etsylog.log') > ($this->max_log_size * 1000000)) {
				rename(DIR_LOGS . 'etsylog.log', DIR_LOGS . '_etsylog_' . date('Y-m-d_H-i-s') . '.log');
			}
		}

		$this->logger = new \Log('etsylog.log');
	}

	public function log($data, $write = true) {
		if ($this->logging == 1) {
			if (function_exists('getmypid')) {
				$process_id = getmypid();
				$data = $process_id . ' - ' . print_r($data, true);
			}

            $this->logger->write($data);
		}
	}

	public function getServer() {
		return $this->url;
	}

	public function settingsUpdate() {
		$this->log("settingsUpdate() - start");

		$response = $this->call('v1/etsy/data/type/getSetup/', 'GET');

		if (isset($response['data']) && is_array($response['data'])) {
			foreach ($response['data'] as $key => $options) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_setting_option` WHERE  `key` = '" . $this->db->escape($key) . "' LIMIT 1");

				$this->db->query("INSERT INTO `" . DB_PREFIX . "etsy_setting_option` SET `data` = '" . $this->db->escape(json_encode($options)) . "', `key` = '" . $this->db->escape($key) . "', `last_updated`  = now()");

				$this->log("settingsUpdate() - updated option: " . $key);
			}

			$this->log("settingsUpdate() - complete");
		} else {
			$this->log("settingsUpdate() - failed - no data response");
		}
	}

	public function getSetting($key) {
		$this->log("getSetting() - " . $key);

		$qry = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "etsy_setting_option` WHERE `key` = '" . $this->db->escape($key) . "' LIMIT 1");

		if($qry->num_rows > 0) {
			$this->log("getSetting() - Found setting");

			return json_decode($qry->row['data']);
		} else {
			return false;
		}
	}

	public function getLinks($product_id, $status = 0, $limit = null) {
		$this->log("getLinks() - Product_id: " . $product_id . " status: " . $status . " limit:" . $limit);

		if ($limit != null) {
			$sql_limit = ' LIMIT 1';
		} else {
			$sql_limit = '';
		}

		$qry = $this->db->query("SELECT `el`.*, `p`.`quantity` FROM `" . DB_PREFIX . "etsy_listing` `el` LEFT JOIN `" . DB_PREFIX . "product` `p` ON `el`.`product_id` = `p`.`product_id` WHERE `el`.`product_id` = '" . (int)$product_id . "' AND `el`.`status` = '" . (int)$status . "' ORDER BY `el`.`created` DESC" . $sql_limit);

		if ($qry->num_rows) {
			$this->log("getLinks() - " . $qry->num_rows . " found");

			$links = array();

			foreach ($qry->rows as $row) {
				$links[] = $row;
			}

			return $links;
		} else {
			$this->log("getLinks() - no links found");

			return false;
		}
	}

	public function getLinkedProduct($etsy_item_id) {
		$this->log("getLinkedProduct() - etsy_item_id: " . $etsy_item_id);

		$qry = $this->db->query("SELECT `p`.`quantity`, `p`.`product_id`, `p`.`model`, `el`.`etsy_listing_id`, `el`.`status` AS `link_status` FROM `" . DB_PREFIX . "etsy_listing` `el` LEFT JOIN `" . DB_PREFIX . "product` `p` ON `p`.`product_id` = `el`.`product_id` WHERE `el`.`etsy_item_id` = '" . (int)$etsy_item_id . "' AND `el`.`status` = 1");

		if($qry->num_rows) {
			$this->log("getLinkedProduct() - " . $qry->num_rows . " found");

			return $qry->row;
		} else {
			$this->log("getLinkedProduct() - no link found");

			return false;
		}
	}

	public function updateListingStock($etsy_item_id, $new_stock, $status) {
		$this->log("updateListingStock() - ItemID: " . $etsy_item_id . ", new stock: " . $new_stock . ", status: " . $status);

		if ($new_stock > 0) {
			$this->log("updateListingStock() - stock > 0 - update stock");

			if ($status == 'edit') {
				$status = 'inactive';
			}

			$response = $this->call('v1/etsy/product/listing/' . (int)$etsy_item_id . '/updateStock/', 'POST', array('quantity' => $new_stock, 'state' => $status));

			if (isset($response['data']['error'])) {
				return $response;
			} else {
				return true;
			}
		} else {
			$this->log("updateListingStock() - stock > 0 - set to inactive");

			$this->deleteLink(null, $etsy_item_id);

			$response = $this->call('v1/etsy/product/listing/' . (int)$etsy_item_id . '/inactive/', 'POST');

			if (isset($response['data']['error'])) {
				$this->log("updateListingStock() - Error: " . json_encode($response));

				return $response;
			} else {
				$this->log("updateListingStock() - Item ended OK");

				return true;
			}
		}
	}

	public function deleteProduct($product_id) {
		$this->log("deleteProduct() - Product ID: " . $product_id);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_listing` WHERE `product_id` = '" . (int)$product_id . "'");
	}

	public function deleteLink($etsy_listing_id = null, $etsy_item_id = null) {
		$this->log("deleteLink() - Listing ID: " . $etsy_listing_id . ", item ID" . $etsy_item_id);

		if ($etsy_listing_id != null) {
			$this->log("deleteLink() - Listing ID is not null");

			$this->db->query("UPDATE `" . DB_PREFIX . "etsy_listing` SET `status` = 0 WHERE `etsy_listing_id` = '" . (int)$etsy_listing_id . "' LIMIT 1");
		} elseif ($etsy_item_id != null) {
			$this->log("deleteLink() - Item ID is not null");

			$this->db->query("UPDATE `" . DB_PREFIX . "etsy_listing` SET `status` = 0 WHERE `etsy_item_id` = '" . (int)$etsy_item_id . "' LIMIT 1");
		}
	}

	public function productUpdateListen($product_id, $data = array()) {
		$this->log("productUpdateListen() - " . $product_id . ", Data: " . json_encode($data));

		$links = $this->getLinks($product_id, 1);

		if (!empty($links)) {
			foreach ($links as $link) {
				$this->log("productUpdateListen() - Item ID: " . $link['etsy_item_id']);

				$etsy_listing = $this->getEtsyItem($link['etsy_item_id']);

				if ($etsy_listing != false && isset($etsy_listing['state']) && ($etsy_listing['state'] == 'active' || $etsy_listing['state'] == 'private' || $etsy_listing['state'] == 'draft' || $etsy_listing['state'] == 'edit')) {
					$this->log("productUpdateListen() - Listing state seems valid");

					if ($etsy_listing['quantity'] != $link['quantity']) {
						$this->log("productUpdateListen() - Stock is different, do update");

						$this->updateListingStock($link['etsy_item_id'], $link['quantity'], $etsy_listing['state']);
					} else {
						$this->log("productUpdateListen() - Stock is the same: " . $etsy_listing['quantity'] . " " . $link['quantity']);
					}
				} else {
					$this->log("productUpdateListen() - Listing state seems invalid");
					$this->log("productUpdateListen() - " . json_encode($etsy_listing));

					$this->deleteLink($link['etsy_listing_id']);
				}
			}
		} else {
			$this->log("productUpdateListen() - No links");
		}
	}

	public function orderFind($order_id = null, $receipt_id = null) {
		$this->log("orderFind() - OrderID: " . $order_id . ", Receipt ID: " . $receipt_id);

		if ($order_id != null) {
			$this->log("orderFind() - Order ID is not null");

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "etsy_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

			if($query->num_rows > 0) {
				$this->log('orderFind() - Found');
				return $query->row;
			} else {
				$this->log('orderFind() - Not found');
				return false;
			}
		} elseif ($receipt_id != null) {
			$this->log("orderFind() - Receipt ID is not null");

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "etsy_order` WHERE `receipt_id` = '" . (int)$receipt_id . "' LIMIT 1");

			if($query->num_rows > 0) {
				$this->log('orderFind() - Found');
				return $query->row;
			} else {
				$this->log('orderFind() - Not found');
				return false;
			}
		}
	}

	public function orderDelete($order_id) {
		$this->log("orderDelete() - ID: " . $order_id);

		if(!$this->orderFind($order_id)) {
			$query = $this->db->query("SELECT `p`.`product_id` FROM `" . DB_PREFIX . "order_product` `op` LEFT JOIN `" . DB_PREFIX . "product` `p` ON `op`.`product_id` = `p`.`product_id` WHERE `op`.`order_id` = '" . (int)$order_id . "'");

			if($query->num_rows > 0) {
				$this->log("orderDelete() - " . $query->num_rows . " products");

				foreach ($query->rows as $product) {
					$this->log("orderDelete() - Processing ID: " . $product['product_id']);

					$this->productUpdateListen((int)$product['product_id']);
				}
			} else {
				$this->log("orderDelete() - No products in order");
			}
		} else {
			$this->log("orderDelete() - Not an Etsy order");
		}
	}

	public function orderUpdatePaid($receipt_id, $status) {
		$this->log("orderUpdatePaid() - Receipt ID: " . $receipt_id . ", Status: " . $status);

		$response = $this->openbay->etsy->call('v1/etsy/order/update/payment/', 'POST', array('receipt_id' => $receipt_id, 'status' => $status));

		if (isset($response['data']['error'])) {
			$this->log("orderUpdatePaid() - Error: " . json_encode($response));

			return $response;
		} else {
			$this->log("orderUpdatePaid() - OK");

			return true;
		}
	}

	public function orderUpdateShipped($receipt_id, $status) {
		$this->log("orderUpdateShipped() - Receipt ID: " . $receipt_id . ", Status: " . $status);

		$response = $this->openbay->etsy->call('v1/etsy/order/update/shipping/', 'POST', array('receipt_id' => $receipt_id, 'status' => $status));

		if (isset($response['data']['error'])) {
			$this->log("orderUpdateShipped() - Error: " . json_encode($response));

			return $response;
		} else {
			$this->log("orderUpdateShipped() - OK");

			return true;
		}
	}

	public function putStockUpdateBulk($product_id_array, $end_inactive) {
		$this->log("putStockUpdateBulk() - ok");
		$this->log("putStockUpdateBulk() - Item count: " . count($product_id_array));

		foreach($product_id_array as $product_id) {
			$this->log("putStockUpdateBulk() - Product ID: " . $product_id);

			$links = $this->getLinks($product_id, 1);

			if (!empty($links)) {
				$this->log("putStockUpdateBulk() - Links found: " . count($links));

				foreach ($links as $link) {
					$etsy_listing = $this->getEtsyItem($link['etsy_item_id']);

					if ($etsy_listing != false && isset($etsy_listing['state']) && ($etsy_listing['state'] == 'active' || $etsy_listing['state'] == 'private' || $etsy_listing['state'] == 'draft' || $etsy_listing['state'] == 'edit')) {
						$this->log("putStockUpdateBulk() - Listing state seems valid");

						if ($etsy_listing['quantity'] != $link['quantity']) {
							$this->log("putStockUpdateBulk() - Stock is different, do update");

							$this->updateListingStock($link['etsy_item_id'], $link['quantity'], $etsy_listing['state']);
						} else {
							$this->log("putStockUpdateBulk() - Stock is the same: " . $etsy_listing['quantity'] . " " . $link['quantity']);
						}
					} else {
						$this->log("putStockUpdateBulk() - Listing state seems invalid");
						$this->log("putStockUpdateBulk() - " . json_encode($etsy_listing));

						$this->deleteLink($link['etsy_listing_id']);
					}
				}
			} else {
				$this->log("putStockUpdateBulk() - No link found");
			}
		}
	}

	public function getEtsyItem($listing_id) {
		$this->log("getEtsyItem(): " . $listing_id);

		$response = $this->openbay->etsy->call('v1/etsy/product/listing/' . $listing_id . '/', 'GET');

		if (isset($response['data']['error'])) {
			$this->log("getEtsyItem() error: ". $response['data']['error']);

			return $response;
		} else {
			$this->log("getEtsyItem() - OK : " . json_encode($response));

			return $response['data']['results'][0];
		}
	}

	public function validate() {
		if ($this->config->get('etsy_token') && $this->config->get('etsy_encryption_key') && $this->config->get('etsy_encryption_iv')) {
			$this->log("Etsy details valid");

			return true;
		} else {
			$this->log("Etsy details are not valid");

			return false;
		}
	}
}

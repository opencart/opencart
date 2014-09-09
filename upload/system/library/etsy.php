<?php
final class Etsy {
	private $token;
	private $enc1;
	private $enc2;
	private $url = 'http://etsy.openbaypro.com/';
	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
		$this->token = $this->config->get('etsy_token');
		$this->enc1 = $this->config->get('etsy_enc1');
		$this->enc2 = $this->config->get('etsy_enc2');
		$this->logging = $this->config->get('etsy_logging');

		$this->load->library('log');
		$this->logger = new Log('etsylog.log');
	}

	public function __get($name) {
		return $this->registry->get($name);
	}

	public function call($uri, $method, $data = array()) {
		if($this->config->get('etsy_status') == 1) {
			$headers = array ();
			$headers[] = 'X-Auth-Token: ' . $this->token;
			$headers[] = 'X-Auth-Enc: ' . $this->enc1;
			$headers[] = 'Content-Type: application/json';
			//$headers[] = 'Content-Length: '.strlen(json_encode($data));

			$defaults = array(
				CURLOPT_HEADER      	=> 0,
				CURLOPT_HTTPHEADER      => $headers,
				CURLOPT_URL             => $this->url . $uri,
				CURLOPT_USERAGENT       => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
				CURLOPT_FRESH_CONNECT   => 1,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_FORBID_REUSE    => 1,
				CURLOPT_TIMEOUT         => 10,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_SSL_VERIFYHOST  => 0,
				//CURLOPT_VERBOSE 		=> true,
				//CURLOPT_STDERR 			=> fopen(DIR_LOGS . 'curl_verbose.log', "w+")
			);

			if ($method == 'POST') {
				$defaults[CURLOPT_POST] = 1;
				$defaults[CURLOPT_POSTFIELDS] = json_encode($data);
			}

			$ch = curl_init();
			curl_setopt_array($ch, $defaults);

			$response = array();

			if (! $result = curl_exec($ch)) {
				$this->log('call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch));

				return false;
			} else {
				$this->log('call() - Result of : "' . print_r($result, true) . '"');

				$encoding = mb_detect_encoding($result);

				if($encoding == 'UTF-8') {
					$result = preg_replace('/[^(\x20-\x7F)]*/', '', $result);
				}

				$result = json_decode($result, 1);

				$response['header_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				if(!empty($result)) {
					$response['data'] = $result;
				}else{
					$response['data'] = '';
				}
			}

			curl_close($ch);

			return $response;
		}else{
			$this->log('call() - OpenBay Pro / Etsy not active');

			return false;
		}
	}

	public function log($data, $write = true) {
		if(function_exists('getmypid')) {
			$process_id = getmypid();
			$data = $process_id . ' - ' . $data;
		}

		if($write == true) {
			$this->logger->write($data);
		}
	}

	public function encryptArgs($data) {
		$token = $this->openbay->pbkdf2($this->enc1, $this->enc2, 1000, 32);
		$crypt = $this->openbay->encrypt($data, $token, true);

		return $crypt;
	}

	public function decryptArgs($crypt, $is_base_64 = true) {
		if ($is_base_64) {
			$crypt = base64_decode($crypt, true);
			if (!$crypt) {
				return false;
			}
		}

		$token = $this->openbay->pbkdf2($this->enc1, $this->enc2, 1000, 32);
		$data = $this->openbay->decrypt($crypt, $token);

		return $data;
	}

	public function getServer() {
		return $this->url;
	}

	public function settingsUpdate() {
		$this->log('Etsy loadDataTypes() start');

		$response = $this->call('data/type/getSetup', 'GET');

		foreach ($response['data'] as $key => $options) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_setting_option` WHERE  `key` = '" . $this->db->escape($key) . "' LIMIT 1");

			$this->db->query("INSERT INTO `" . DB_PREFIX . "etsy_setting_option` SET `data` = '" . $this->db->escape(serialize($options)) . "', `key` = '" . $this->db->escape($key) . "', `last_updated`  = now()");

			$this->log('Updated Etsy option: ' . $key);
		}

		$this->log('Etsy loadDataTypes() complete');
	}

	public function getSetting($key) {
		$qry = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "etsy_setting_option` WHERE `key` = '" . $this->db->escape($key) . "' LIMIT 1");

		if($qry->num_rows > 0) {
			return unserialize($qry->row['data']);
		}else{
			return false;
		}
	}

	public function getLinks($product_id, $status = 0, $limit = null) {
		$this->log('getLinks() - Product_id: ' . $product_id . ' status: ' . $status . ' limit:' . $limit);

		if ($limit != null) {
			$sql_limit = ' LIMIT 1';
		} else {
			$sql_limit = '';
		}

		$qry = $this->db->query("SELECT `el`.*, `p`.`quantity` FROM `" . DB_PREFIX . "etsy_listing` `el` LEFT JOIN `" . DB_PREFIX . "product` `p` ON `el`.`product_id` = `p`.`product_id` WHERE `el`.`product_id` = '" . (int)$product_id . "' AND `el`.`status` = '" . (int)$status . "' ORDER BY `el`.`created` DESC" . $sql_limit);

		if ($qry->num_rows) {
			$links = array();
			foreach ($qry->rows as $row) {
				$links[] = $row;
			}

			return $links;
		} else {
			return false;
		}
	}

	public function getLinkedProduct($etsy_item_id) {
		$qry = $this->db->query("SELECT `p`.`quantity`, `p`.`product_id`, `p`.`model`, `el`.`etsy_listing_id`, `el`.`status` AS `link_status` FROM `" . DB_PREFIX . "etsy_listing` `el` LEFT JOIN `" . DB_PREFIX . "product` `p` ON `p`.`product_id` = `el`.`product_id` WHERE `el`.`etsy_item_id` = '" . (int)$etsy_item_id . "' AND `el`.`status` = 1");

		if($qry->num_rows) {
			return $qry->row;
		}else{
			return false;
		}
	}

	public function updateAllStock($product_id, $new_stock) {
		/**
		 * This will update all linked listings with the set stock amount
		 */

		/** @var loop over linked items $response
		$response = $this->openbay->etsy->call('product/listing/'.(int)$data['listing_id'].'/image', 'POST', $data);

		if (isset($response['data']['error'])) {
			$this->response->setOutput(json_encode($response['data']));
		} else {
			$this->response->setOutput(json_encode($response['data']['results'][0]));
		}
		 * */
	}

	public function updateListingStock($etsy_item_id, $new_stock) {
		if ($new_stock > 0) {
			$response = $this->call('product/listing/' . (int)$etsy_item_id . '/updateStock', 'POST', array('quantity' => $new_stock));

			if (isset($response['data']['error'])) {
				return $response;
			} else {
				return true;
			}
		} else {
			$this->deleteLink(null, $etsy_item_id);

			$response = $this->call('product/listing/' . (int)$etsy_item_id . '/inactive', 'POST');

			if (isset($response['data']['error'])) {
				return $response;
			} else {
				return true;
			}
		}
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_listing` WHERE `product_id` = '" . $this->db->escape($product_id) . "'");
	}

	public function deleteLink($etsy_listing_id = null, $etsy_item_id = null) {
		if ($etsy_listing_id != null) {
			$this->db->query("UPDATE `" . DB_PREFIX . "etsy_listing` SET `status` = 0 WHERE `etsy_listing_id` = '" . (int)$etsy_listing_id . "' LIMIT 1");
		} elseif ($etsy_item_id != null) {
			$this->db->query("UPDATE `" . DB_PREFIX . "etsy_listing` SET `status` = 0 WHERE `etsy_item_id` = '" . (int)$etsy_item_id . "' LIMIT 1");
		}
	}

	public function productUpdateListen($product_id, $data) {
		$links = $this->getLinks($product_id, 1);

		if (!empty($links)) {
			foreach ($links as $link) {
				$etsy_listing = $this->getEtsyItem($link['etsy_item_id']);

				$this->log(print_r($etsy_listing, true));

				$this->log('Listings update');

				if ($etsy_listing != false && isset($etsy_listing['state']) && ($etsy_listing['state'] == 'active' || $etsy_listing['state'] == 'private')) {
					if ($etsy_listing['quantity'] != $link['quantity']) {
						$this->updateListingStock($link['etsy_item_id'], $link['quantity']);
					}
				} else {
					$this->deleteLink($link['etsy_listing_id']);
				}
			}
		}
	}

	public function orderFind($order_id = null, $receipt_id = null) {
		if ($order_id != null) {
			$this->log('Find order id: ' . $order_id);
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "etsy_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

			if($query->num_rows > 0) {
				$this->log('Found');
				return $query->row;
			}else{
				$this->log('Not found');
				return false;
			}
		} elseif($receipt_id != null) {
			$this->log('Find receipt id: ' . $receipt_id);
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "etsy_order` WHERE `receipt_id` = '" . (int)$receipt_id . "' LIMIT 1");

			if($query->num_rows > 0) {
				$this->log('Found');
				return $query->row;
			}else{
				$this->log('Not found');
				return false;
			}
		}
	}

	public function addOrder($order_id) {
		if(!$this->orderFind($order_id)) {
			$query = $this->db->query("SELECT `p`.`quantity`, `p`.`product_id`, `el`.`etsy_item_id` FROM `" . DB_PREFIX . "order_product` `op` LEFT JOIN `" . DB_PREFIX . "product` `p` ON `op`.`product_id` = `p`.`product_id` LEFT JOIN `" . DB_PREFIX . "etsy_listing` `el` ON `op`.`product_id` = `p`.`product_id` WHERE `op`.`order_id` = '" . (int)$order_id . "' AND `el`.`status` = 1");

			if($query->num_rows > 0) {
				foreach ($query->rows as $product) {
					$this->updateListingStock((int)$product['etsy_item_id'], (int)$product['quantity']);
				}
			}
		}
	}

	public function orderDelete($order_id) {
		if(!$this->orderFind($order_id)) {
			$query = $this->db->query("SELECT `p`.`quantity`, `p`.`product_id`, `el`.`etsy_item_id` FROM `" . DB_PREFIX . "order_product` `op` LEFT JOIN `" . DB_PREFIX . "product` `p` ON `op`.`product_id` = `p`.`product_id` LEFT JOIN `" . DB_PREFIX . "etsy_listing` `el` ON `op`.`product_id` = `p`.`product_id` WHERE `op`.`order_id` = '" . (int)$order_id . "' AND `el`.`status` = 1");

			if($query->num_rows > 0) {
				foreach ($query->rows as $product) {
					$this->updateListingStock((int)$product['etsy_item_id'], (int)$product['quantity']);
				}
			}
		}
	}

	public function orderUpdatePaid($receipt_id, $status) {
		$response = $this->openbay->etsy->call('order/update/payment', 'POST', array('receipt_id' => $receipt_id, 'status' => $status));

		if (isset($response['data']['error'])) {
			return $response;
		} else {
			return true;
		}
	}

	public function orderUpdateShipped($receipt_id, $status) {
		$response = $this->openbay->etsy->call('order/update/shipping', 'POST', array('receipt_id' => $receipt_id, 'status' => $status));

		if (isset($response['data']['error'])) {
			return $response;
		} else {
			return true;
		}
	}

	public function putStockUpdateBulk($product_id_array, $end_inactive) {
		foreach($product_id_array as $product_id) {
			$links = $this->getLinks($product_id, 1);

			if (!empty($links)) {
				foreach ($links as $link) {
					$etsy_listing = $this->getEtsyItem($link['etsy_item_id']);

					if ($etsy_listing != false && isset($etsy_listing['state']) && ($etsy_listing['state'] == 'active' || $etsy_listing['state'] == 'private')) {
						if ($etsy_listing['quantity'] != $link['quantity']) {
							$this->updateListingStock($link['etsy_item_id'], $link['quantity']);
						}
					} else {
						$this->deleteLink($link['etsy_listing_id']);
					}
				}
			}
		}
	}

	public function getEtsyItem($listing_id) {
		$response = $this->openbay->etsy->call('product/listing/' . $listing_id, 'GET');

		if (isset($response['data']['error'])) {
			return $response;
		} else {
			return $response['data']['results'][0];
		}
	}

	public function validate() {
		if ($this->config->get('etsy_token') && $this->config->get('etsy_enc1') && $this->config->get('etsy_enc2')) {
			return true;
		} else {
			return false;
		}
	}
}
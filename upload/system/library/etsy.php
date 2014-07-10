<?php
final class Etsy {
	private $registry;
	private $url    = 'http://etsy.welfordlocal.co.uk/';

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

	public function log($data, $write = true) {
		if(function_exists('getmypid')) {
			$pId = getmypid();
			$data = $pId . ' - ' . $data;
		}

		if($write == true) {
			$this->logger->write($data);
		}
	}

	public function getApiServer() {
		return $this->url;
	}

	public function call($uri, $method, $data = array()) {
		if($this->config->get('etsy_status') == 1) {

			$headers = array ();
			$headers[] = 'X-Auth-Token: '.$this->token;
			$headers[] = 'X-Auth-Enc: '.$this->enc1;
			$headers[] = 'Content-Type: application/json';
			//$headers[] = 'Content-Length: '.strlen(json_encode($data));

			$defaults = array(
				CURLOPT_HEADER      	=> 0,
				CURLOPT_HTTPHEADER      => $headers,
				CURLOPT_URL             => $this->url.$uri,
				CURLOPT_USERAGENT       => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
				CURLOPT_FRESH_CONNECT   => 1,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_FORBID_REUSE    => 1,
				CURLOPT_TIMEOUT         => 10,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_SSL_VERIFYHOST  => 0,
				CURLOPT_VERBOSE 		=> true,
				CURLOPT_STDERR 			=> fopen(DIR_LOGS.'curl_verbose.log', "w+")
			);

			if ($method == 'POST') {
				$defaults[CURLOPT_POST] = 1;
				$defaults[CURLOPT_POSTFIELDS] = json_encode($data);
			}

			$ch = curl_init();
			curl_setopt_array($ch, $defaults);

			$response = array();

			if( ! $result = curl_exec($ch)) {
				$this->log('call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch));

				return false;
			} else {
				$this->log('call() - Result of : "' . print_r($result, true) . '"');

				$encoding = mb_detect_encoding($result);

				if($encoding == 'UTF-8') {
					$result = preg_replace('/[^(\x20-\x7F)]*/','', $result);
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

	public function settingsUpdate() {
		$this->log('Etsy loadDataTypes() start');

		$response = $this->call('data/type/getSetup', 'GET');

		foreach ($response['data'] as $key => $options) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_setting_option` WHERE  `key` = '".$this->db->escape($key)."' LIMIT 1");

			$this->db->query("INSERT INTO `" . DB_PREFIX . "etsy_setting_option` SET `data` = '" . $this->db->escape(serialize($options)) . "', `key` = '".$this->db->escape($key)."', `last_updated`  = now()");

			$this->log('Updated Etsy option: '.$key);
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

	public function getLinks($product_id, $status = 0) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "etsy_listing` WHERE `product_id` = '" . (int)$product_id . "' AND `status` = '".(int)$status."'");

		if($qry->num_rows) {
			$links = array();
			foreach ($qry->rows as $row) {
				$links[] = $row;
			}

			return $links;
		}else{
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

	public function updateListingStock($listing_id, $new_stock) {
		/**
		 * This will update a single listing stock level
		 */

		$response = $this->openbay->etsy->call('product/listing/'.(int)$listing_id.'/updateStock', 'POST', array('quantity' => $new_stock));

		if (isset($response['data']['error'])) {
			return $response;
		} else {
			return true;
		}
	}

	public function decryptArgs($crypt, $isBase64 = true) {
		if ($isBase64) {
			$crypt = base64_decode($crypt, true);
			if (!$crypt) {
				return false;
			}
		}

		$token = $this->pbkdf2($this->encPass, $this->encSalt, 1000, 32);
		$data = $this->decrypt($crypt, $token);

		return $data;
	}

	private function encrypt($msg, $k, $base64 = false) {
		if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', ''))
			return false;

		$iv = mcrypt_create_iv(32, MCRYPT_RAND);

		if (mcrypt_generic_init($td, $k, $iv) !== 0)
			return false;

		$msg = mcrypt_generic($td, $msg);
		$msg = $iv . $msg;
		$mac = $this->pbkdf2($msg, $k, 1000, 32);
		$msg .= $mac;

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		if ($base64) {
			$msg = base64_encode($msg);
		}

		return $msg;
	}

	private function decrypt($msg, $k, $base64 = false) {
		if ($base64) {
			$msg = base64_decode($msg);
		}

		if (!$td = mcrypt_module_open('rijndael-256', '', 'ctr', '')) {
			return false;
		}

		$iv = substr($msg, 0, 32);
		$mo = strlen($msg) - 32;
		$em = substr($msg, $mo);
		$msg = substr($msg, 32, strlen($msg) - 64);
		$mac = $this->pbkdf2($iv . $msg, $k, 1000, 32);

		if ($em !== $mac) {
			return false;
		}

		if (mcrypt_generic_init($td, $k, $iv) !== 0) {
			return false;
		}

		$msg = mdecrypt_generic($td, $msg);
		$msg = unserialize($msg);

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return $msg;
	}

	private function pbkdf2($p, $s, $c, $kl, $a = 'sha256') {
		$hl = strlen(hash($a, null, true));
		$kb = ceil($kl / $hl);
		$dk = '';

		for ($block = 1; $block <= $kb; $block++) {

			$ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);

			for ($i = 1; $i < $c; $i++)
				$ib ^= ($b = hash_hmac($a, $b, $p, true));

			$dk .= $ib;
		}

		return substr($dk, 0, $kl);
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_listing` WHERE `product_id` = '" . $this->db->escape($product_id) . "'");
	}

	public function productUpdateListen($product_id, $data) {
		// is the item linked?

		// get the listing from etsy

		// is it active?
			// yes
				// does the stock match? If not push an update.

			// no
				// does it have an old link?
					// yes
						// do we have relist items setting to true?
							// yes - relist the item
							// no
					// no

	}

	public function orderNew($order_id) {

	}

	public function putStockUpdateBulk($product_id_array, $end_inactive) {

	}

	public function deleteOrder($order_id) {

	}
}
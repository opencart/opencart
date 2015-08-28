<?php
namespace openbay;

final class Ebay {
	private $token;
	private $enc1;
	private $enc2;
	private $url = 'https://uk.openbaypro.com/';
	private $registry;
	private $no_log = array('notification/getPublicNotifications/', 'setup/getEbayCategories/', 'item/getItemAllList/', 'account/validate/', 'item/getItemListLimited/');
	private $logger;
	private $max_log_size = 50; //max log size in Mb

	public function __construct($registry) {
		$this->registry = $registry;
		$this->token = $this->config->get('ebay_token');
		$this->secret = $this->config->get('ebay_secret');
		$this->enc1 = $this->config->get('ebay_string1');
		$this->enc2 = $this->config->get('ebay_string2');
		$this->logging = $this->config->get('ebay_logging');
		$this->tax = $this->config->get('ebay_tax');
		$this->server = 1;
		$this->lasterror = '';
		$this->lastmsg = '';

		if ($this->logging == 1) {
			$this->setLogger();
		}
	}

	public function __get($name) {
		return $this->registry->get($name);
	}

	public function call($call, array $post = null, array $options = array(), $content_type = 'json', $status_override = false) {
		if ($this->config->get('ebay_status') == 1 || $status_override == true) {
			$this->lasterror    = '';
			$this->lastmsg      = '';

			if (!in_array($call, $this->no_log)) {
				$this->log('call(' . $call . ') - Data: ' .  json_encode($post));
			}

			if (defined("HTTPS_CATALOG")) {
				$domain = HTTPS_CATALOG;
			} else {
				$domain = HTTPS_SERVER;
			}

			$data = array('token' => $this->token, 'secret' => $this->secret, 'server' => $this->server, 'domain' => $domain, 'openbay_version' => (int)$this->config->get('openbay_version'), 'opencart_version' => VERSION, 'data' => $post, 'content_type' => $content_type, 'language' => $this->config->get('openbay_language'));

			$defaults = array(
				CURLOPT_POST            => 1,
				CURLOPT_HEADER          => 0,
				CURLOPT_URL             => $this->url . $call,
				CURLOPT_USERAGENT       => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
				CURLOPT_FRESH_CONNECT   => 1,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_FORBID_REUSE    => 1,
				CURLOPT_TIMEOUT         => 0,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_SSL_VERIFYHOST  => 0,
				CURLOPT_POSTFIELDS      => http_build_query($data, '', "&")
			);

			$ch = curl_init();
			curl_setopt_array($ch, ($options + $defaults));
			if (! $result = curl_exec($ch)) {
				$this->log('call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch));
			}
			curl_close($ch);

			if (!in_array($call, $this->no_log)) {
				$this->log('call() - Result of : "' . $result . '"');
			}

			if ($content_type == 'json') {
				$encoding = mb_detect_encoding($result);

				if ($encoding == 'UTF-8') {
					$result = preg_replace('/[^(\x20-\x7F)]*/', '', $result);
				}

				$result             = json_decode($result, 1);
				$this->lasterror    = $result['error'];
				$this->lastmsg      = $result['msg'];

				if (!empty($result['data'])) {
					return $result['data'];
				} else {
					return false;
				}
			}elseif ($content_type == 'xml') {
				$result             = simplexml_load_string($result);
				$this->lasterror    = $result->error;
				$this->lastmsg      = $result->msg;

				if (!empty($result->data)) {
					return $result->data;
				} else {
					return false;
				}
			}
		} else {
			$this->log('call() - OpenBay Pro not active');
		}
	}

	public function callNoResponse($call, array $post = null, array $options = array(), $content_type = 'json') {
		if ($this->config->get('ebay_status') == 1) {
			$this->log('openbay_noresponse_call(' . $call . ') - Data :' .  json_encode($post));

			if (defined("HTTPS_CATALOG")) {
				$domain = HTTPS_CATALOG;
			} else {
				$domain = HTTPS_SERVER;
			}

			$data = array('token' => $this->token, 'secret' => $this->secret, 'server' => $this->server, 'domain' => $domain, 'openbay_version' => (int)$this->config->get('openbay_version'), 'opencart_version' => VERSION, 'data' => $post, 'content_type' => $content_type, 'language' => $this->config->get('openbay_language'));

			$defaults = array(
				CURLOPT_POST            => 1,
				CURLOPT_HEADER          => 0,
				CURLOPT_URL             => $this->url . $call,
				CURLOPT_USERAGENT       => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
				CURLOPT_FRESH_CONNECT   => 1,
				CURLOPT_RETURNTRANSFER  => 0,
				CURLOPT_FORBID_REUSE    => 1,
				CURLOPT_TIMEOUT         => 5,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_SSL_VERIFYHOST  => 0,
				CURLOPT_POSTFIELDS      => http_build_query($data, '', "&")
			);

			$ch = curl_init();
			curl_setopt_array($ch, ($options + $defaults));
			curl_exec($ch);
			$this->log(curl_error($ch));
			curl_close($ch);
		} else {
			$this->log('openbay_noresponse_call() - OpenBay Pro not active . ');
		}
	}

	private function setLogger() {
		$this->load->library('log');

		if(file_exists(DIR_LOGS . 'ebaylog.log')) {
			if(filesize(DIR_LOGS . 'ebaylog.log') > ($this->max_log_size * 1000000)) {
				rename(DIR_LOGS . 'ebaylog.log', DIR_LOGS . '_ebaylog_' . date('Y-m-d_H-i-s') . '.log');
			}
		}

		$this->logger = new \Log('ebaylog.log');
	}

	public function log($data, $write = true) {
		if ($this->logging == 1) {
			if (function_exists('getmypid')) {
				$process_id = getmypid();
				$data = $process_id . ' - ' . $data;
			}

			if ($write == true) {
				$this->logger->write($data);
			}
		}
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

	public function getSetting($key) {
		$qry = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = '" . $this->db->escape($key) . "' LIMIT 1");

		if ($qry->num_rows > 0) {
			return unserialize($qry->row['data']);
		} else {
			return false;
		}
	}

	public function getEbayItemId($product_id) {
		$this->log('getEbayItemId() - Product ID: ' . $product_id);

		$qry = $this->db->query("SELECT `ebay_item_id` FROM `" . DB_PREFIX . "ebay_listing` WHERE `product_id` = '" . (int)$product_id . "' AND `status` = '1' LIMIT 1");

		if (!$qry->num_rows) {
			$this->log('No link found - getEbayItemId()');
			return false;
		} else {
			$this->log('Returning ' . $qry->row['ebay_item_id'] . ' - getEbayItemId()');
			return $qry->row['ebay_item_id'];
		}
	}

	public function getEndedEbayItemId($product_id) {
		$this->log('getEndedEbayItemId() - ID: ' . $product_id);

		$qry = $this->db->query("SELECT `ebay_item_id` FROM `" . DB_PREFIX . "ebay_listing` WHERE `product_id` = '" . (int)$product_id . "' AND `status` = '0' ORDER BY `ebay_listing_id` DESC LIMIT 1");

		if (!$qry->num_rows) {
			$this->log('getEndedEbayItemId() - No link');
			return false;
		} else {
			$this->log('getEndedEbayItemId() - Returning ' . $qry->row['ebay_item_id']);
			return $qry->row['ebay_item_id'];
		}
	}

	public function removeItemByItemId($item_id) {
		$this->log('removeItemByItemId() - ID: ' . $item_id);

		$this->db->query("UPDATE `" . DB_PREFIX . "ebay_listing` SET `status` = '0' WHERE `ebay_item_id` = '" . $this->db->escape($item_id) . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_stock_reserve` WHERE `item_id` = '" . $this->db->escape($item_id) . "'");
	}

	public function removeItemByProductId($product_id) {
		$this->log('removeItemByProductId() - ID: ' . $product_id . '');

		$this->db->query("UPDATE `" . DB_PREFIX . "ebay_listing` SET `status` = '0' WHERE `product_id` = '" . (int)$product_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_stock_reserve` WHERE `product_id` = '" . (int)$product_id . "'");
	}

	public function deleteProduct($product_id) {
		$this->log('deleteProduct() - ID: ' . $product_id);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_listing` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_stock_reserve` WHERE `product_id` = '" . (int)$product_id . "'");
	}

	public function orderDelete($order_id) {
		/**
		 * @todo
		 */
	}

	public function getLiveListingArray() {
	/*
	 * Returns the list of linked items with eBay from the database
	 * @return array ([product id] = ebay item id)
	 */
		$this->log('getLiveListingArray()');

		$qry = $this->db->query("SELECT `product_id`, `ebay_item_id` FROM `" . DB_PREFIX . "ebay_listing` WHERE `status` = '1'");

		$data = array();
		if ($qry->num_rows > 0) {
			foreach ($qry->rows as $row) {
				$data[$row['product_id']] = $row['ebay_item_id'];
			}
		}

		return $data;
	}

	public function getEndedListingArray() {
		$this->log('getEndedListingArray()');
		$active = $this->getLiveListingArray();

		$qry = $this->db->query("SELECT e.* FROM (SELECT `product_id`, MAX(`ebay_listing_id`) as `ebay_listing_id` FROM `" . DB_PREFIX . "ebay_listing` WHERE `status` = 0 GROUP BY `product_id`) `a` INNER JOIN `" . DB_PREFIX . "ebay_listing` `e` ON (`e`.`ebay_listing_id` = `a`.`ebay_listing_id`)");

		$data = array();
		if ($qry->num_rows > 0) {
			foreach ($qry->rows as $row) {
				$data[$row['product_id']] = $row['ebay_item_id'];
			}
		}

		foreach ($active as $k => $v) {
			if (array_key_exists($k, $data)) {
				unset($data[$k]);
			}
		}

		return $data;
	}

	public function getLiveProductArray() {
		/**
		* Returns the list of linked items with eBay from the database
		* @return array ([ebay item id] = product id)
		*/
		$qry = $this->db->query("SELECT `product_id`, `ebay_item_id` FROM `" . DB_PREFIX . "ebay_listing` WHERE `status` = '1'");

		$data = array();
		if ($qry->num_rows) {
			foreach ($qry->rows as $row) {
				$data[$row['ebay_item_id']] = $row['product_id'];
			}
		}

		return $data;
	}

	public function endItem($item_id) {
		$this->log('endItem() - ID "' . $item_id);

		if ($this->config->get('ebay_enditems') == 1) {
			$this->call('item/endItem/', array('id' => $item_id));
			$this->removeItemByItemId($item_id);

			if ($this->lasterror != true) {
				$this->log('endItem() - OK');
				return array('error' => false, 'msg' => 'ok');
			} else {
				return array('error' => true, 'msg' => $this->lasterror);
			}
		} else {
			$this->removeItemByItemId($item_id);
			$this->log('endItem() - config has disabled ending items');

			$message = "An item has gone out of stock but your settings are not set to end eBay items automatically.\r\n\r\n";
			$message.= "You need to ensure you have stock left of this item or end your eBay listing manually.\r\n\r\n";
			$message.= "eBay item ID: $item_id";

			$this->notifyAdmin('eBay item not ended: ' . $item_id, $message);

			return array('error' => true, 'msg' => 'Settings do not allow you to end items, but the link has been removed . ');
		}
	}

	public function ebaySaleStockReduce($product_id, $sku = null) {
		/**
		* Gets the product info from an ID and sends to ebay update method.
		*/
		$this->log('ebaySaleStockReduce() - Is stock update needed (Item ID: ' . $product_id . ',SKU: ' . $sku . ')');

		if (!empty($product_id)) {
			if ($sku == null) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1");
				$this->log('ebaySaleStockReduce() - Send item ID: "' . $product_id . '", Stock: "' . $query->row['quantity'] . '" to decideEbayStockAction()');
				$this->decideEbayStockAction($product_id, $query->row['quantity'], $query->row['subtract']);
			} else {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_variant` WHERE `product_id` = '" . (int)$product_id . "' AND `sku` = '" . $this->db->escape($sku) . "' LIMIT 1");
				$this->log('ebaySaleStockReduce() - Send item ID: ' . $product_id . ', VAR: ' . $sku . ', passing ' . $query->row['stock'] . ' to decideEbayStockAction()');
				$this->decideEbayStockAction($product_id, $query->row['stock'], $query->row['subtract'], $sku);
			}
		}
	}

	public function notifyAdmin($subject, $message) {
		$this->log('Sending email to: ' . $this->config->get('config_email') . ' - notifyAdmin()');

		$mail = new \Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText($message);
		$mail->send();
	}

	public function validateJsonDecode($data) {
		$data = (string)$data;

		$encoding = mb_detect_encoding($data);

		if ($encoding == 'UTF-8') {
			$data = preg_replace('/[^(\x20-\x7F)]*/', '', $data);
			$data = preg_replace('#\\\\x[0-9a-fA-F]{2,2}#', '', $data);
		}

		$data = json_decode($data);

		if (function_exists('json_last_error')) {
			switch (json_last_error()) {
				case JSON_ERROR_NONE:
					$this->log('validateJsonDecode() - No json decode errors');
				break;
				case JSON_ERROR_DEPTH:
					$this->log('validateJsonDecode() - Maximum stack depth exceeded');
				break;
				case JSON_ERROR_STATE_MISMATCH:
					$this->log('validateJsonDecode() - Underflow or the modes mismatch');
				break;
				case JSON_ERROR_CTRL_CHAR:
					$this->log('validateJsonDecode() - Unexpected control character found');
				break;
				case JSON_ERROR_SYNTAX:
					$this->log('validateJsonDecode() - Syntax error, malformed JSON');
				break;
				case JSON_ERROR_UTF8:
					$this->log('validateJsonDecode() - Malformed UTF-8 characters, possibly incorrectly encoded');
				break;
				default:
					$this->log('validateJsonDecode() - Unknown error');
				break;
			}
		} else {
			$this->log('validateJsonDecode() - json_last_error PHP function does not exist');
		}

		return $data;
	}

	private function eBayShippingStatus($item, $txn, $status, $tracking_no = '', $carrier_id = '') {
		$this->log('eBayShippingStatus() - Update order shipping status (Item: ' . $item . ',Txn: ' . $txn . ',Status:' . $status . ',Tracking: ' . $tracking_no . ', Carrier: ' . $carrier_id . ')');
		return $this->call('order/shippingStatus/', array('item' => $item, 'txn' => $txn, 'status' => $status, 'carrier' => $carrier_id, 'tracking' => $tracking_no));
	}

	private function eBayPaymentStatus($item, $txn, $status) {
		$this->log('eBayPaymentStatus() - Updates order payment status (Item: ' . $item . ',Txn: ' . $txn . ',Status:' . $status . ')');
		return $this->call('order/paymentStatus/', array('item' => $item, 'txn' => $txn, 'status' => $status));
	}

	private function getSaleRecord($sale_id) {
		$this->log('getSaleRecord() - Get ebay sale record ID: ' . $sale_id);
		return $this->call('order/getSmpRecord/', array('id' => $sale_id));
	}

	public function getEbayActiveListings() {
		$this->log('getEbayActiveListings() - Get active eBay items from API');
		return $this->call('item/getItemAllList/');
	}

	public function getEbayItemList($limit = 100, $page = 1, $filter = array()) {
		$this->log('getEbayItemList() - Get active eBay items from API');
		return $this->call('item/getItemListLimited/', array('page' => $page, 'limit' => $limit, 'filter' => $filter));
	}

	public function disableProduct($product_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `status` = 0 WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1");
	}

	public function disableVariant($product_id, $sku) {
		$this->db->query("UPDATE `" . DB_PREFIX . "product_option_variant` SET `active` = 0 WHERE `product_id` = '" . (int)$product_id . "' AND `sku` = '" . $this->db->escape($sku) . "' LIMIT 1");
	}

	public function putStockUpdate($item_id, $stock, $sku = null) {
		$this->log('putStockUpdate()');
		$this->log('putStockUpdate() - New local stock: ' . $stock);

		$listing    = $this->call('item/getItem', array('itemId' => $item_id));
		$product_id = $this->getProductId($item_id);
		$reserve    = $this->getReserve($product_id, $item_id, (($sku != null) ? $sku : ''));

		if ($listing['status'] == 1 ) {
			if ($reserve != false) {
				$this->log('putStockUpdate() - Reserve stock: ' . $reserve);

				if ($stock > $reserve) {
					$this->log('putStockUpdate() - Stock is larger than reserve, setting level to reserve');
					$stock = $reserve;
				}
			}

			if ($sku == null) {
				$this->log('putStockUpdate() - Listing stock: ' . $listing['qty'] . ', new stock: ' . $stock);

				if ($stock <= 0) {
					if ($this->config->get('ebay_disable_nostock') == 1) {
						$this->disableProduct($product_id);
					}

					$this->endItem($item_id);
					return true;
				}elseif ($listing['qty'] != $stock) {
					$this->call('item/reviseStock/', array('itemId' => $item_id, 'stock' => $stock));
					$this->log('putStockUpdate() - OK');
					return true;
				} else {
					$this->log('putStockUpdate() - No update needed');
					return false;
				}
			} else {
				// Need to loop over current item check if other variants have stock
				$variant_stock = false;
				foreach ($listing['variation']['vars'] as $var) {
					if (($var['sku'] != $sku) && ($var['qty'] > 0)) {
						//other variations have stock
						$variant_stock = true;
						$this->log('Another variation has stock (SKU: ' . $var['sku'] . ')');
						break;
					}
				}

				if ($stock <= 0) {
					if ($this->config->get('ebay_disable_nostock') == 1) {
						$this->disableVariant($product_id, $sku);
					}
				}

				if ($variant_stock == true || $stock > 0) {
					$this->log('putStockUpdate() - Revising item with Item ID "' . $item_id . '" to stock level "' . $stock . '", sku "' . $sku . '"');
					$this->call('item/reviseStock/', array('itemId' => $item_id, 'stock' => $stock, 'sku' => $sku));
					return true;
				} else {
					$this->log('putStockUpdate() - Sending end for item, no variants have stock!');
					$this->endItem($item_id);
				}
			}
		} else {
			$this->removeItemByItemId($item_id);

			if($sku == null) {
				if ($stock <= 0 && $this->config->get('ebay_disable_nostock') == 1) {
					$this->disableProduct($product_id);
				}
			} else {
				if ($stock <= 0 && $this->config->get('ebay_disable_nostock') == 1) {
					$this->disableVariant($product_id, $sku);
				}
			}

			$this->log('putStockUpdate() - Listing not active, item id: ' . $item_id . ', status returned: ' . $listing['statusActual']);
		}
	}

	public function putStockUpdateBulk($product_id_array, $end_inactive = false) {
		// We know is that these product ID's have been modified. They should only be passed if the stock has changed so we can assume this.
		$this->log('putStockUpdateBulk()');

		$openstock = false;
		if ($this->openbay->addonLoad('openstock') == true) {
			$this->load->model('module/openstock');
			$openstock = true;
		}

		// Get the active OpenCart items that were linked to eBay If they have stock now, relist them.
		$ended_data = $this->getEndedListingArray();

		/**
		 * Get the active OpenCart items that are also linked
		 * Compare against the stock from eBay
		 * If listing active and local stock = 0, end it
		 * If listing inactive, remove link
		 * If listing active and local stock not the same, update it
		 */
		$ebay_listings = $this->getEbayActiveListings();
		$live_data = $this->getLiveListingArray();

		$linked_items        = array();
		$linked_ended_items   = array();

		foreach ($product_id_array as $product_id) {
			if (array_key_exists((int)$product_id, $live_data)) {
				//product has been passed and is linked to active item
				$linked_items[] = array('productId' => (int)$product_id, 'itemId' => $live_data[$product_id]);
			}elseif (array_key_exists((int)$product_id, $ended_data)) {
				//product has been passed and is not currently active
				$linked_ended_items[] = array('productId' => (int)$product_id, 'itemId' => $ended_data[$product_id]);
			} else {
				//product does not exist in live or ended links so has never been linked.
			}
		}

		//loop through ended listings, if back in stock and not multi var - relist it
		foreach ($linked_ended_items as $item) {
			if ($openstock == true) {
				$options = $this->model_module_openstock->getVariants($item['productId']);
			} else {
				$options = array();
			}

			if (empty($options)) {
				//get the stock level of the linked items
				$local_stock = $this->getProductStockLevel($item['productId']);

				if ((int)$local_stock['quantity'] > 0 && $local_stock['status'] == 1) {
					//product has stock and is enabled, so re list it.
					$reserve = $this->getReserve($item['productId'], $item['itemId']);

					if ($reserve != false) {
						if ($local_stock['quantity'] > $reserve) {
							$local_stock['quantity'] = $reserve;
						}
					}

					if ($this->config->get('ebay_relistitems') == 1) {
						//relist item with new stock
						$this->relistItem($item['itemId'], $item['productId'], (int)$local_stock['quantity']);
					}
				}
			} else {
				$this->log('putStockUpdateBulk() - options existed for item (' . $item['itemId'] . ') when trying to relist');
				// @todo - support relisting of variant items, if possible with ebay!
			}
		}

		//loop through the active listings and update the store or end the item
		foreach ($linked_items as $item) {
			//get the stock level of the linked item
			$local_stock = $this->getProductStockLevel($item['productId']);

			//check if the itemid was returned by ebay, if not unlink it as it is ended.
			if (!isset($ebay_listings[$item['itemId']])) {
				$this->log('eBay item was not returned, removing link (' . $item['itemId'] . ')');
				$this->removeItemByItemId($item['itemId']);
			} else {
				//check if the local item is now inactive - end if it is
				if ($end_inactive == true && $local_stock['status'] == 0) {
					$this->endItem($item['itemId']);
				} else {
					//get any options that are set for this product
					if ($openstock == true) {
						$options = $this->model_module_openstock->getVariants($item['productId']);
					} else {
						$options = array();
					}

					if (empty($options) && empty($ebay_listings[$item['itemId']]['variants'])) {
						$this->log('putStockUpdateBulk() - Item has no variants');

						//compare to the ebay data get retrieved
						if ((int)$local_stock['quantity'] != (int)$ebay_listings[$item['itemId']]['qty']) {
							$reserve = $this->getReserve($item['productId'], $item['itemId']);

							if ($reserve != false) {
								if ($local_stock['quantity'] > $reserve) {
									$local_stock['quantity'] = $reserve;
								}
							}

							$this->putStockUpdate($item['itemId'], (int)$local_stock['quantity']);
						}
					}elseif (!empty($options) && !empty($ebay_listings[$item['itemId']]['variants'])) {
						// This item has variants
						$this->log('putStockUpdateBulk() - Variants found');

						//create an index of var codes to search against
						$var_ids = array();
						foreach ($options as $k => $v) {
							$var_ids[$k] = $v['var'];
						}

						//loop over eBay variants
						foreach ($ebay_listings[$item['itemId']]['variants'] as $ebay_variant) {
							$this->log('Checking eBay SKU: ' . $ebay_variant['sku'] . ' for item: ' . $item['itemId']);

							if (in_array($ebay_variant['sku'], $var_ids)) {
								$option_id = array_search($ebay_variant['sku'], $var_ids);

								//compare the stock - if different trigger update
								if ($ebay_variant['qty'] != $options[$option_id]['stock']) {
									$reserve = $this->getReserve($item['productId'], $item['itemId'], $ebay_variant['sku']);

									if ($reserve != false) {
										if ($options[$option_id]['stock'] > $reserve) {
											$options[$option_id]['stock'] = $reserve;
										}
									}

									$this->log('putStockUpdateBulk() - Revising variant item: ' . $item['itemId'] . ',Stock: ' . $options[$option_id]['stock'] . ', SKU ' . $ebay_variant['sku']);
									$this->call('item/reviseStock/', array('itemId' => $item['itemId'], 'stock' => $options[$option_id]['stock'], 'sku' => $ebay_variant['sku']));
								}
							}
						}
					} else {
						$this->log('Unsure if this item has variants, debug:');
						$this->log('Local: ' . $options);
						$this->log('eBay: ' . serialize($ebay_listings[$item['itemId']]['variants']));
					}
				}
			}
		}
	}

	public function getProductStockLevel($product_id, $sku = '') {
		$this->log('getProductStockLevel() - ID: ' . $product_id . ', SKU: ' . $sku);

		if ($sku == '' || $sku == null) {
			$qry = $this->db->query("SELECT `quantity`, `status` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1");

			return array('quantity' => (int)$qry->row['quantity'], 'status' => ($qry->row['status']));
		} else {
			$qry = $this->db->query("SELECT `stock`, `active` FROM `" . DB_PREFIX . "product_option_variant` WHERE `product_id` = '" . (int)$product_id . "' AND `sku` = '" . $this->db->escape($sku) . "' LIMIT 1");

			return array('quantity' => (int)$qry->row['stock'], 'status' => ($qry->row['active']));
		}
	}

	public function productUpdateListen($product_id, $data = array()) {
		$this->log('productUpdateListen(' . $product_id . ')');

		$item_id = $this->getEbayItemId($product_id);

		$product = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1")->row;

		if ($item_id != false) {
			$this->log('productUpdateListen(' . $product_id . ') - listing found (' . $item_id . ')');

			if ($this->openbay->addonLoad('openstock') && (isset($product['has_option']) && $product['has_option'] == 1)) {
				$this->load->model('module/openstock');
				$this->load->model('tool/image');
				$this->load->model('catalog/product');

				$this->log('productUpdateListen(' . $product_id . ') - Variant');

				if (!isset($data['variant'])) {
					$variants = $this->model_module_openstock->getVariants($product_id);
				} else {
					$variants = $data['variant'];
				}

				$variant_data = array();

				$groups = $this->openbay->getProductOptions($product_id);
				$variant_data['groups']  = array();
				$variant_data['related'] = array();

				foreach ($groups as $grp) {
					$t_tmp = array();
					foreach ($grp['product_option_value'] as $grp_node) {
						$t_tmp[$grp_node['option_value_id']] = $grp_node['name'];

						$variant_data['related'][$grp_node['product_option_value_id']] = $grp['name'];
					}
					$variant_data['groups'][] = array('name' => $grp['name'], 'child' => $t_tmp);
				}

				$v = 0;
				$stock = false;

				foreach ($variants as $option) {
					if (!empty($option['sku'])) {
						if ($option['stock'] > 0 || $stock == true) {
							$stock = true;
						}

						// PRODUCT RESERVE LEVELS FOR VARIANT ITEMS (DOES NOT PASS THROUGH NORMAL SYSTEM)
						$reserve = $this->getReserve($product_id, $item_id, $option['sku']);
						if ($reserve != false) {
							$this->log('productUpdateListen() / Variant (' . $option['sku'] . ') - Reserve stock: ' . $reserve);

							if ($option['stock'] > $reserve) {
								$this->log('putStockUpdate() - Stock (' . $option['stock'] . ') is larger than reserve (' . $reserve . '), setting level to reserve');
								$option['stock'] = $reserve;
							}
						}

						$variant_data['opt'][$v]['sku'] = $option['sku'];
						$variant_data['opt'][$v]['qty'] = $option['stock'];
						$variant_data['opt'][$v]['active'] = 0;

						if ($option['active'] == 1) {
							$variant_data['opt'][$v]['active'] = 1;
						}

						$v++;
					}
				}

				$variant_data['groups'] = base64_encode(serialize($variant_data['groups']));
				$variant_data['related'] = base64_encode(serialize($variant_data['related']));
				$variant_data['id'] = $item_id;

				//send to the api to process
				if (!empty($variant_data['opt'])) {
					if ($stock == true) {
						$this->log('productUpdateListen() - Sending to API');
						$response = $this->call('item/reviseStockVariants', $variant_data);

						return $response;
					} else {
						$this->log('productUpdateListen() - Ending item');
						$this->endItem($item_id);
					}
				}
			} else {
				$this->log('productUpdateListen(' . $product_id . ') - Not a variant');

				$this->decideEbayStockAction($product_id, $product['quantity'], $product['subtract']);

				return array('msg' => 'ok', 'error' => false);
			}
		} else {
			$old_item_id = $this->getEndedEbayItemId($product_id);
			$this->log('productUpdateListen(' . $product_id . ') - Standard item. Old ID: ' . $old_item_id);

			if ($old_item_id != false) {
				if ($this->openbay->addonLoad('openstock') && (isset($product['has_option']) && $product['has_option'] == 1)) {
					$this->log('productUpdateListen(' . $product_id . ') - multi variant items relist not supported');
					/**
					 * reserved for multi variant products can be relisted automatically.
					 */
				} else {
					$this->log('productUpdateListen(' . $product_id . ') - Normal, stock(' . $product['quantity'] . ') > 0');
					if ($product['quantity'] > 0) {
						if ($this->config->get('ebay_relistitems') == 1) {
							$this->relistItem($old_item_id, $product_id, $product['quantity']);
						}
					}
				}
			} else {
				$this->log('productUpdateListen() - stoping, nothing found');
			}
		}
	}

	public function orderStatusListen($order_id, $status_id, $data = array()) {
		$ebay_order = $this->getOrder($order_id);

		if (isset($ebay_order['smp_id'])) {
			$ebay_id = $ebay_order['smp_id'];
		} else {
			$ebay_id = false;
		}

		$this->log('orderStatusListen() - Order ' . $order_id . ' changed status');

		if ($ebay_id != false) {
			$this->log('orderStatusListen() - It is an eBay order, new status: ' . $status_id);

			$item_txn_array = $this->getSaleRecord($ebay_id);

			if (!empty($item_txn_array)) {
				//Has it been marked as paid?
				if ($status_id == $this->config->get('ebay_status_paid_id')) {
					$this->log('orderStatusListen() - Updating to paid status');
					foreach ($item_txn_array as $item) {
						$tmp = simplexml_load_string($this->eBayPaymentStatus($item['item'], $item['txn'], true));
					}
				}

				// Has it been marked as shipped?
				if ($status_id == $this->config->get('ebay_status_shipped_id')) {
					$this->log('orderStatusListen() - Updating to shipped status');
					foreach ($item_txn_array as $item) {
						$tmp = simplexml_load_string($this->eBayShippingStatus($item['item'], $item['txn'], true, (isset($data['tracking_no']) ? $data['tracking_no'] : ''), (isset($data['carrier_id']) ? $data['carrier_id'] : '')));
					}
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_order` SET `carrier_id` = '" . $this->db->escape((isset($data['carrier_id']) ? $data['carrier_id'] : '')) . "', `tracking_no` = '" . $this->db->escape((isset($data['tracking_no']) ? $data['tracking_no'] : '')) . "' WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
				}

				//Has it been marked as cancelled?
				if ($status_id == $this->config->get('ebay_status_cancelled_id')) {
					$this->log('orderStatusListen() - Updating to cancelled status');
					foreach ($item_txn_array as $item) {
						$tmp = simplexml_load_string($this->eBayPaymentStatus($item['item'], $item['txn'], false));
					}

					foreach ($item_txn_array as $item) {
						$tmp = simplexml_load_string($this->eBayShippingStatus($item['item'], $item['txn'], false));
					}
				}

				//Has it been marked as refunded?
				if ($status_id == $this->config->get('ebay_status_refunded_id')) {
					$this->log('orderStatusListen() - Updating to refunded status');
					foreach ($item_txn_array as $item) {
						$tmp = simplexml_load_string($this->eBayPaymentStatus($item['item'], $item['txn'], false));
					}

					foreach ($item_txn_array as $item) {
						$tmp = simplexml_load_string($this->eBayShippingStatus($item['item'], $item['txn'], false));
					}
				}
			} else {
				// @todo return error to use here
				$this->log('orderStatusListen() - The TXN array was empty, could not get order info to update status. ');
			}
		} else {
			$this->log('orderStatusListen() - It is not an eBay order');
		}
	}

	public function decideEbayStockAction($product_id, $qty, $subtract, $sku = null) {
		if ($subtract == 1) {
			$this->log('decideEbayStockAction() - Product ID: ' . $product_id . ', Current stock: ' . $qty);

			$item_id = $this->getEbayItemId($product_id);

			if ($item_id != false) {
				$this->putStockUpdate($item_id, $qty, $sku);
			}
		} else {
			$this->log('decideEbayStockAction() - Product ID: ' . $product_id . ' does not subtract stock');
		}
	}

	public function getProductId($ebay_item, $status = 0) {
		$this->log('getProductId() - Item: ' . $ebay_item);

		$status_sql = '';
		if ($status == 1) {
			$status_sql = ' AND `status` = 1';
		}

		$qry = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "ebay_listing` WHERE `ebay_item_id` = '" . $this->db->escape($ebay_item) . "'" . $status_sql . " LIMIT 1");

		if (!$qry->num_rows) {
			return false;
		} else {
			return $qry->row['product_id'];
		}
	}

	public function getProductIdFromKey($key) {
		$qry = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "ebay_listing_pending` WHERE `key` = '" . $this->db->escape($key) . "' LIMIT 1");

		if (!$qry->num_rows) {
			return false;
		} else {
			return $qry->row['product_id'];
		}
	}

	public function validate() {
		if ($this->config->get('ebay_status') != 0 && $this->config->get('ebay_token') != '' && $this->config->get('ebay_secret') != '' && $this->config->get('ebay_string1') != '' && $this->config->get('ebay_string2') != '') {
			return true;
		} else {
			return false;
		}
	}

	public function getAllocatedStock($product_id) {
		$qry = $this->db->query("SELECT SUM(`qty`) AS `total` FROM `" . DB_PREFIX . "ebay_transaction` WHERE `product_id` = '" . (int)$product_id . "' AND `order_id` = '0' LIMIT 1");
		return (int)$qry->row['total'];
	}

	public function getImages() {
		$this->log('getImages() - Getting product images . ');
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_image_import`");

		if ($qry->num_rows) {
			foreach ($qry->rows as $img) {
				$this->log('Image: ' . $img['name']);

				$img_large = str_replace(array('$_1.JPG', '$_01.JPG', '$_12.JPG'), '$_57.JPG', $img['image_original']);

				$header_response = $this->getImageInfo($img_large);

				$copy_status = false;

				if ($header_response == 200) {
					// a supersize version was found.
					$copy_status = $this->getImageCopy($img_large, $img['image_new']);
				} else {
					// fall back to trying the original image
					$header_response = $this->getImageInfo($img['image_original']);

					if ($header_response == 200) {
						$copy_status = $this->getImageCopy($img['image_original'], $img['image_new']);
					}
				}

				if ($copy_status == true) {
					if ($img['imgcount'] == 0) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `image` = 'catalog/" . $img['name'] . "' WHERE `product_id` = '" . (int)$img['product_id'] . "' LIMIT 1");
					} else {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "product_image` SET `product_id` = '" . (int)$img['product_id'] . "', `image` = 'catalog/" . $this->db->escape($img['name']) . "', `sort_order` = '" . (int)$img['imgcount'] . "'");
					}

					$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_image_import` WHERE `id` = '" . (int)$img['id'] . "' LIMIT 1");
				}
			}
		}
	}

	private function getImageInfo($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);

		if(curl_exec($ch) === false) {
			$this->log('Curl Error: ' . curl_error($ch));
		}

		$header_response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$this->log($header_response);

		curl_close($ch);

		return $header_response;
	}

	private function getImageCopy($url, $new_image) {
		$handle = @fopen($url, 'r');

		if ($handle !== false) {
			if (!@copy($url, $new_image)) {
				$this->log('getImages() - FAILED COPY: ' . $url);
				$this->log(print_r(error_get_last(), true));
				return false;
			} else {
				$this->log('getImages() - Copy OK : ' . $url);
				return true;
			}
		} else {
			$this->log('getImages() - URL not found : ' . $url);
			return false;
		}
	}

	public function getEbayListing($item_id) {
		$this->log('getEbayListing()');
		return $this->call('item/getItem/', array('itemId' => $item_id));
	}

	public function relistItem($item_id, $product_id, $qty) {
		$this->log('relistItem() - Starting relist item, ID: ' . $item_id . ', product: ' . $product_id . ', qty: ' . $qty);

		$response = $this->call('listing/relistItem/', array('itemId' => $item_id, 'qty' => $qty));

		if (!empty($response['ItemID'])) {
			$this->log('relistItem() - Created: ' . $response['ItemID']);
			$this->createLink($product_id, $response['ItemID'], '');
			return $response['ItemID'];
		} else {
			$this->log('relistItem() - Relisting failed ID: ' . $item_id);
			return false;
		}
	}

	public function createLink($product_id, $item_id, $variant) {
		$this->deleteProduct($product_id);
		$this->removeItemByItemId($item_id);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_listing` SET `product_id` = '" . (int)$product_id . "', `ebay_item_id` = '" . $this->db->escape($item_id) . "', `variant` = '" . (int)$variant . "', `status` = '1'");
	}

	public function addReserve($data, $item_id, $variant) {
		if ($variant == 1) {
			foreach ($data['opt'] as $variation) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_variant` WHERE `product_id` = '" . (int)$data['product_id'] . "' AND `sku` = '" . $this->db->escape($variation['sku']) . "' LIMIT 1");

				if ($query->row['stock'] != $variation['qty']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_stock_reserve` SET `product_id` = '" . (int)$data['product_id'] . "', `item_id` = '" . $this->db->escape($item_id) . "', `variant_id` = '" . $this->db->escape($variation['sku']) . "', `reserve` = '" . (int)$variation['qty'] . "'");
				}
			}
		} else {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$data['product_id'] . "' LIMIT 1");

			if ($query->row['quantity'] != $data['qty'][0]) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_stock_reserve` SET `product_id`    = '" . (int)$data['product_id'] . "', `item_id` = '" . $this->db->escape($item_id) . "', `variant_id` = '', `reserve` = '" . (int)$data['qty'][0] . "'");
			}
		}
	}

	public function getReserve($product_id, $item_id, $sku = '') {
		$this->log('getReserve()');
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_stock_reserve` WHERE `product_id` = '" . (int)$product_id . "' AND `variant_id` = '" . $this->db->escape($sku) . "' AND `item_id` = '" . $this->db->escape($item_id) . "'  LIMIT 1");

		if ($query->num_rows > 0) {
			$this->log('getReserve() - returning: ' . $query->row['reserve']);
			return $query->row['reserve'];
		} else {
			$this->log('getReserve() - none');
			return false;
		}
	}

	public function updateReserve($product_id, $item_id, $reserve, $sku = '', $variant = 0) {
		$this->log('updateReserve() - start');
		$this->log('updateReserve() - $product_id: ' . $product_id);
		$this->log('updateReserve() - $item_id: ' . $item_id);
		$this->log('updateReserve() - $reserve: ' . $reserve);
		$this->log('updateReserve() - $sku: ' . $sku);
		$this->log('updateReserve() - $variant: ' . $variant);

		if ($reserve == 0) {
			$this->deleteReserve($product_id, $item_id, $sku);
		} else {
			if ($this->getReserve($product_id, $item_id, $sku) != false) {
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_stock_reserve` SET `reserve` = '" . (int)$reserve . "' WHERE `product_id` = '" . (int)$product_id . "' AND `variant_id` = '" . $this->db->escape($sku) . "' AND `item_id` = '" . $this->db->escape($item_id) . "'  LIMIT 1");
			} else {
				if ($variant == 0) {
					$this->log('updateReserve() - not a variant');
					$this->addReserve(array('product_id' => $product_id, 'qty' => array(0 => $reserve)), $item_id, 0);
				} else {
					$this->log('updateReserve() - variant');
					$this->addReserve(array('product_id' => $product_id, 'opt' => array(array('sku' => $sku, 'qty' => $reserve))), $item_id, 1);
				}
			}
		}
	}

	public function deleteReserve($product_id, $item_id, $sku = '') {
		$this->log('deleteReserve()');
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_stock_reserve` WHERE `product_id` = '" . (int)$product_id . "' AND `variant_id` = '" . $this->db->escape($sku) . "' AND `item_id` = '" . $this->db->escape($item_id) . "'  LIMIT 1");
	}

	public function getCarriers() {
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "ebay_shipping");

		$couriers = array();
		foreach ($qry->rows as $row) {
			$couriers[] = $row;
		}

		return $couriers;
	}

	public function getOrder($order_id) {
		if ($this->openbay->testDbTable(DB_PREFIX . "ebay_order") == true) {
			$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

			if ($qry->num_rows > 0) {
				return $qry->row;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getOrderBySmpId($smp_id) {
		if ($this->openbay->testDbTable(DB_PREFIX . "ebay_order") == true) {
			$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_order` WHERE `smp_id` = '" . (int)$smp_id . "' LIMIT 1");

			if ($qry->num_rows > 0) {
				return $qry->row;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function updateCategories() {
		$cat_array = $this->call('setup/getEbayCategories/', array(), array(), 'json', true);

		if ($this->lasterror != true) {
			$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "ebay_category`");

			if (!empty($cat_array)) {
				foreach ($cat_array as $cat) {
					if ($cat['BestOfferEnabled'] == true) {
						$cat['BestOfferEnabled'] = 1;
					} else {
						$cat['BestOfferEnabled'] = 0;
					}

					if ($cat['AutoPayEnabled'] == true) {
						$cat['AutoPayEnabled'] = 1;
					} else {
						$cat['AutoPayEnabled'] = 0;
					}

					$this->db->query("
						INSERT INTO `" . DB_PREFIX . "ebay_category` SET
							`CategoryID`        = '" . (int)$cat['CategoryID'] . "',
							`CategoryParentID`  = '" . (int)$cat['CategoryParentID'] . "',
							`CategoryLevel`     = '" . (int)$cat['CategoryLevel'] . "',
							`BestOfferEnabled`  = '" . (int)$cat['BestOfferEnabled'] . "',
							`AutoPayEnabled`    = '" . (int)$cat['AutoPayEnabled'] . "',
							`CategoryName`      = '" . $this->db->escape((string)$cat['CategoryName']) . "'
					");
				}
			}
		}

		return array('msg' => $this->lastmsg, 'error' => $this->lasterror);
	}

	public function updateSettings() {
		$response = $this->call('setup/getEbayDetails/', array(), array(), 'json', true);

		$this->log('Getting eBay settings / sync');

		if ($this->lasterror === false) {
			if (isset($response['listing_restrictions'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'listing_restrictions' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['listing_restrictions'])) . "', `last_updated`  = now() WHERE `key` = 'listing_restrictions' LIMIT 1");
					$this->log('Updated listing_restrictions into ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'listing_restrictions', `data` = '" . $this->db->escape(serialize($response['listing_restrictions'])) . "', `last_updated`  = now()");
					$this->log('Inserted listing_restrictions into ebay_setting_option table');
				}
			} else {
				$this->log('listing_restrictions data not set!');
			}

			if (isset($response['product_details'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'product_details' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['product_details'])) . "', `last_updated`  = now() WHERE `key` = 'product_details' LIMIT 1");
					$this->log('Updated product_details into ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'product_details', `data` = '" . $this->db->escape(serialize($response['product_details'])) . "', `last_updated`  = now()");
					$this->log('Inserted product_details into ebay_setting_option table');
				}
			} else {
				$this->log('Non identifier text not set!');
			}

			if (isset($response['urls']['ViewItemURL'])) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE  `key` = 'ebay_itm_link' LIMIT 1");

				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape((string)$response['urls']['ViewItemURL']) . "', `key` = 'ebay_itm_link', `code` = 'openbay'");

				$this->log('Updated eBay item link');
			} else {
				$this->log('Item link URL not set!');
			}

			//ebay payment methods
			if (isset($response['payment_options'])) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "ebay_payment_method`");
				$this->log('Emptied ebay_payment_method table');

				foreach ($response['payment_options'] as $child) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_payment_method` SET `ebay_name` = '" . $this->db->escape((string)$child['PaymentOption']) . "', `local_name` = '" . $this->db->escape((string)$child['Description']) . "'");
				}

				$this->log('Populated ebay_payment_method table');
			} else {
				$this->log('No payment options set!');
			}

			//ebay shipping
			if (isset($response['shipping_service'])) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "ebay_shipping`");
				$this->log('Emptied ebay_shipping table');
				foreach ($response['shipping_service'] as $service) {
					if (!empty($service['InternationalService']) && $service['InternationalService'] == 'true') {
						$service['InternationalService'] = 1;
					} else {
						$service['InternationalService'] = 0;
					}

					if (!empty($service['ValidForSellingFlow'])) {
						$service['ValidForSellingFlow'] = 1;
					} else {
						$service['ValidForSellingFlow'] = 0;
					}

					if (!empty($service['ShippingTimeMin'])) {
						$min = (int)$service['ShippingTimeMin'];
					} else {
						$min = 1;
					}
					if (!empty($service['ShippingTimeMax'])) {
						$max = (int)$service['ShippingTimeMax'];
					} else {
						$max = 21;
					}

					$this->db->query("
						INSERT INTO `" . DB_PREFIX . "ebay_shipping` SET
							`description`               = '" . $this->db->escape((string)$service['Description']) . "',
							`InternationalService`      = '" . (int)$service['InternationalService'] . "',
							`ShippingService`           = '" . $this->db->escape((string)$service['ShippingService']) . "' ,
							`ShippingServiceID`         = '" . (int)$service['ShippingServiceID'] . "',
							`ServiceType`               = '" . $this->db->escape(strtolower(implode(',', $service['ServiceType']))) . "' ,
							`ValidForSellingFlow`       = '" . (int)$service['ValidForSellingFlow'] . "',
							`ShippingCategory`          = '" . $this->db->escape((string)$service['ShippingCategory']) . "' ,
							`ShippingTimeMin`           = '" . (int)$min . "',
							`ShippingTimeMax`           = '" . (int)$max . "'
					");
				}
				$this->log('Populated ebay_shipping table');
			} else {
				$this->log('No shiopping details set!');
			}

			//shipping locations
			if (isset($response['shipping_location'])) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "ebay_shipping_location`");
				$this->log('Emptied ebay_shipping_location table');
				foreach ($response['shipping_location'] as $service) {
					$this->db->query("
						INSERT INTO `" . DB_PREFIX . "ebay_shipping_location`
						SET
							`description`         = '" . $this->db->escape((string)$service['Description']) . "',
							`detail_version`      = '" . $this->db->escape($service['DetailVersion']) . "',
							`shipping_location`   = '" . $this->db->escape((string)$service['ShippingLocation']) . "' ,
							`update_time`         = '" . (int)$service['UpdateTime'] . "'
					");
				}
				$this->log('Populated ebay_shipping_location table');
			} else {
				$this->log('No shipping locations set!');
			}

			//shipping locations exclude
			if (isset($response['exclude_shipping_location'])) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "ebay_shipping_location_exclude`");
				$this->log('Emptied ebay_shipping_location_exclude table');
				foreach ($response['exclude_shipping_location'] as $service) {
					$this->db->query("
						INSERT INTO `" . DB_PREFIX . "ebay_shipping_location_exclude`
						SET
							`description`   = '" . $this->db->escape((string)$service['Description']) . "',
							`location`      = '" . $this->db->escape((string)$service['Location']) . "',
							`region`        = '" . $this->db->escape((string)$service['Region']) . "'
					");
				}
				$this->log('Populated exclude_shipping_location table');
			} else {
				$this->log('No shipping exclude locations set!');
			}

			//max dispatch times
			if (isset($response['dispatch_time_max'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'dispatch_time_max' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['dispatch_time_max'])) . "', `last_updated`  = now() WHERE `key` = 'dispatch_time_max' LIMIT 1");
					$this->log('Updated dispatch_time_max into ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'dispatch_time_max', `data` = '" . $this->db->escape(serialize($response['dispatch_time_max'])) . "', `last_updated`  = now()");
					$this->log('Inserted dispatch_time_max into ebay_setting_option table');
				}
			} else {
				$this->log('No dispatch_time_max set!');
			}

			//countries
			if (isset($response['countries'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'countries' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['countries'])) . "', `last_updated`  = now() WHERE `key` = 'countries' LIMIT 1");
					$this->log('Updated countries into ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'countries', `data` = '" . $this->db->escape(serialize($response['countries'])) . "', `last_updated`  = now()");
					$this->log('Inserted countries into ebay_setting_option table');
				}
			} else {
				$this->log('No countries set!');
			}

			//returns
			if (isset($response['returns'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'returns' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['returns'])) . "', `last_updated`  = now() WHERE `key` = 'returns' LIMIT 1");
					$this->log('Updated returns info in to ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'returns', `data` = '" . $this->db->escape(serialize($response['returns'])) . "', `last_updated`  = now()");
					$this->log('Inserted returns info in to ebay_setting_option table');
				}
			} else {
				$this->log('No returns set!');
			}

			//package sizes
			if (isset($response['package_type'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'package_type' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['package_type'])) . "', `last_updated`  = now() WHERE `key` = 'package_type' LIMIT 1");
					$this->log('Updated package_type info in to ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'package_type', `data` = '" . $this->db->escape(serialize($response['package_type'])) . "', `last_updated`  = now()");
					$this->log('Inserted package_type info in to ebay_setting_option table');
				}
			} else {
				$this->log('No package_type set!');
			}

			//vat enabled
			if (isset($response['vat_enabled'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'vat_enabled' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . (int)$response['package_type'] . "', `last_updated`  = now() WHERE `key` = 'vat_enabled' LIMIT 1");
					$this->log('Updated vat_enabled in to ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'vat_enabled', `data` = '" . (int)$response['package_type'] . "', `last_updated`  = now()");
					$this->log('Inserted vat_enabled info in to ebay_setting_option table');
				}
			} else {
				$this->log('No vat_enabled set!');
			}

			//shipping types
			if (isset($response['shipping_types'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'shipping_types' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['shipping_types'])) . "', `last_updated`  = now() WHERE `key` = 'shipping_types' LIMIT 1");
					$this->log('Updated shipping_types info in to ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'shipping_types', `data` = '" . $this->db->escape(serialize($response['shipping_types'])) . "', `last_updated`  = now()");
					$this->log('Inserted shipping_types info in to ebay_setting_option table');
				}
			} else {
				$this->log('No shipping_types set!');
			}

			//measurement types
			if (isset($response['measurement_types'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'measurement_types' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['measurement_types'])) . "', `last_updated`  = now() WHERE `key` = 'measurement_types' LIMIT 1");
					$this->log('Updated measurement_types info in to ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'measurement_types', `data` = '" . $this->db->escape(serialize($response['measurement_types'])) . "', `last_updated`  = now()");
					$this->log('Inserted measurement_types info in to ebay_setting_option table');
				}
			} else {
				$this->log('No measurement_types set!');
			}

			// Product details
			if (isset($response['product_details'])) {
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_setting_option` WHERE `key` = 'product_details' LIMIT 1");

				if ($qry->num_rows > 0) {
					$this->db->query("UPDATE `" . DB_PREFIX . "ebay_setting_option` SET `data` = '" . $this->db->escape(serialize($response['product_details'])) . "', `last_updated`  = now() WHERE `key` = 'product_details' LIMIT 1");
					$this->log('Updated product_details info in to ebay_setting_option table');
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_setting_option` SET `key` = 'product_details', `data` = '" . $this->db->escape(serialize($response['product_details'])) . "', `last_updated`  = now()");
					$this->log('Inserted product_details info in to ebay_setting_option table');
				}
			} else {
				$this->log('No product_details set!');
			}
		}

		return array('msg' => $this->lastmsg, 'error' => $this->lasterror);
	}

	public function updateStore() {
		$store = $this->call('setup/getSellerStore/', array(), array(), 'json', true);

		if ($this->lasterror != true) {
			if ($store['store'] == true) {
				$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_store_category`;");
				$this->db->query("
							CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_store_category` (
							  `ebay_store_category_id` int(11) NOT NULL AUTO_INCREMENT,
							  `parent_id` int(11) NOT NULL,
							  `CategoryID` char(100) NOT NULL,
							  `CategoryName` char(100) NOT NULL,
							  PRIMARY KEY (`ebay_store_category_id`)
							) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

				if (!empty($store['settings']['categories'])) {
					foreach ($store['settings']['categories'] as $cat1) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_store_category` SET `CategoryID` = '" . $this->db->escape($cat1['id']) . "', `CategoryName` = '" . $this->db->escape($cat1['name']) . "'");
						$id1 = $this->db->getLastId();

						if (!empty($cat1['children'])) {
							foreach ($cat1['children'] as $cat2) {
								$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_store_category` SET `CategoryID` = '" . $this->db->escape($cat2['id']) . "', `CategoryName` = '" . $this->db->escape($cat2['name']) . "', `parent_id` = '" . $this->db->escape($id1) . "'");
								$id2 = $this->db->getLastId();

								if (!empty($cat2['children'])) {
									foreach ($cat2['children'] as $cat3) {
										$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_store_category` SET `CategoryID` = '" . $this->db->escape($cat3['id']) . "', `CategoryName` = '" . $this->db->escape($cat3['name']) . "', `parent_id` = '" . $this->db->escape($id2) . "'");
										$id3 = $this->db->getLastId();

										if (!empty($cat3['children'])) {
											foreach ($cat3['children'] as $cat4) {
												$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_store_category` SET `CategoryID` = '" . $this->db->escape($cat4['id']) . "', `CategoryName` = '" . $this->db->escape($cat4['name']) . "', `parent_id` = '" . $this->db->escape($id3) . "'");
												$id4 = $this->db->getLastId();
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return array('msg' => $this->lastmsg, 'error' => $this->lasterror);
	}

	public function editSetting($group, $data, $store_id = 0) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($group) . "'");

		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
			}
		}
	}

	public function getShippingServiceInfo($service_code) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_shipping` WHERE `ShippingService` = '" . $this->db->escape($service_code) . "' LIMIT 1");

		if ($qry->num_rows) {
			return $qry->row;
		} else {
			return false;
		}
	}
}
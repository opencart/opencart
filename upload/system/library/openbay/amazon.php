<?php
namespace openbay;

final class Amazon {
	private $token;
    private $encryption_key;
    private $encryption_iv;
	private $url = 'https://uk-amazon.openbaypro.com/';
	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
		$this->token = $this->config->get('openbay_amazon_token');

		$this->setEncryptionKey($this->config->get('openbay_amazon_encryption_key'));
		$this->setEncryptionIv($this->config->get('openbay_amazon_encryption_iv'));
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

	public function call($method, $data = array(), $use_json = true) {
        if (!empty($data)) {
            if ($use_json) {
                $string = json_encode($data);
            } else {
                $string = $data;
            }

            $encrypted = $this->openbay->encrypt($string, $this->getEncryptionKey(), $this->getEncryptionIv(), false);
        } else {
            $encrypted = '';
        }

        $post_data = array(
            'token' => $this->token,
            'data' => base64_encode($encrypted),
            'opencart_version' => VERSION
        );

        $headers = array();
        $headers[] = 'X-Endpoint-Version: 2';

		$defaults = array(
            CURLOPT_HEADER      	=> 0,
            CURLOPT_HTTPHEADER      => $headers,
			CURLOPT_POST            => 1,
			CURLOPT_URL             => $this->url . $method,
			CURLOPT_USERAGENT       => 'OpenBay Pro for Amazon/Opencart',
			CURLOPT_FRESH_CONNECT   => 1,
			CURLOPT_RETURNTRANSFER  => 1,
			CURLOPT_FORBID_REUSE    => 1,
			CURLOPT_TIMEOUT         => 30,
			CURLOPT_SSL_VERIFYPEER  => 0,
			CURLOPT_SSL_VERIFYHOST  => 0,
			CURLOPT_POSTFIELDS      => http_build_query($post_data, '', "&"),
		);

		$curl = curl_init();

		curl_setopt_array($curl, $defaults);

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}

	public function callNoResponse($method, $data = array(), $use_json = true) {
        if (!empty($data)) {
            if ($use_json) {
                $string = json_encode($data);
            } else {
                $string = $data;
            }

            $encrypted = $this->openbay->encrypt($string, $this->getEncryptionKey(), $this->getEncryptionIv(), false);
        } else {
            $encrypted = '';
        }

        $post_data = array(
            'token' => $this->token,
            'data' => rawurlencode(base64_encode($encrypted)),
            'opencart_version' => VERSION
        );

        $headers = array();
        $headers[] = 'X-Endpoint-Version: 2';

		$defaults = array(
            CURLOPT_HEADER      	=> 0,
            CURLOPT_HTTPHEADER      => $headers,
			CURLOPT_POST            => 1,
			CURLOPT_URL             => $this->url . $method,
			CURLOPT_USERAGENT       => 'OpenBay Pro for Amazon/Opencart',
			CURLOPT_FRESH_CONNECT   => 1,
			CURLOPT_RETURNTRANSFER  => 1,
			CURLOPT_FORBID_REUSE    => 1,
			CURLOPT_TIMEOUT         => 2,
			CURLOPT_SSL_VERIFYPEER  => 0,
			CURLOPT_SSL_VERIFYHOST  => 0,
			CURLOPT_POSTFIELDS      => http_build_query($post_data, '', "&"),
		);
		$curl = curl_init();

		curl_setopt_array($curl, $defaults);

		curl_exec($curl);

		curl_close($curl);
	}

	public function getServer() {
		return $this->url;
	}

	public function productUpdateListen($product_id, $data = array()) {
		$logger = new \Log('amazon_stocks.log');
		$logger->write('productUpdateListen(), product ID: ' . $product_id);

		$product = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1")->row;

		if ($this->openbay->addonLoad('openstock') && (isset($product['has_option']) && $product['has_option'] == 1)) {
			$this->load->model('extension/module/openstock');
			$logger->write('Variant item');

			$quantity_data = array();

			// check if post data['variant'], if not then call db to get variants
			if (!isset($data['variant'])) {
				$variants = $this->model_extension_module_openstock->getVariants($product_id);
			} else {
				$variants = $data['variant'];
			}

			foreach ($variants as $variant) {
				$amazon_sku_rows = $this->db->query("SELECT `amazon_sku` FROM `" . DB_PREFIX . "amazon_product_link` WHERE `product_id` = '" . (int)$product_id . "' AND `var` = '" . $this->db->escape($variant['sku']) . "'")->rows;

				foreach($amazon_sku_rows as $amazon_sku_row) {
					$quantity_data[$amazon_sku_row['amazon_sku']] = $variant['stock'];
				}
			}

			if(!empty($quantity_data)) {
				$logger->write('Updating with: ' . print_r($quantity_data, true));
				$this->updateQuantities($quantity_data);
			} else {
				$logger->write('Not required.');
			}
		} else {
			$this->putStockUpdateBulk(array($product_id));
		}

		$logger->write('productUpdateListen() - finished');
	}

	public function bulkUpdateOrders($orders) {
		// Is the module enabled and called from admin?
		if ($this->config->get('openbay_amazon_status') != 1 || !defined('HTTPS_CATALOG')) {
			return;
		}
		$this->load->model('extension/openbay/amazon');

		$log = new \Log('amazon.log');
		$log->write('Called bulkUpdateOrders method');

		$request = array(
			'orders' => array(),
		);

		foreach ($orders as $order) {
			$amazon_order = $this->getOrder($order['order_id']);
			$amazon_order_products = $this->model_extension_openbay_amazon->getAmazonOrderedProducts($order['order_id']);

			$products = array();

			foreach ($amazon_order_products as $amazon_order_product) {
				$products[] = array(
					'amazon_order_item_id' => $amazon_order_product['amazon_order_item_id'],
					'quantity' => $amazon_order_product['quantity'],
				);
			}

			$order_info = array(
				'amazon_order_id' => $amazon_order['amazon_order_id'],
				'status' => $order['status'],
				'products' => $products,
			);

			if ($order['status'] == 'shipped' && !empty($order['carrier'])) {
				if ($order['carrier_from_list']) {
					$order_info['carrier_id'] = $order['carrier'];
				} else {
					$order_info['carrier_name'] = $order['carrier'];
				}

				$order_info['tracking'] = $order['tracking'];
			}

			$request['orders'][] = $order_info;
		}

		$log->write('order/bulkUpdate call: ' . print_r($request, 1));

		$response = $this->call('order/bulkUpdate', $request);

		$log->write('order/bulkUpdate response: ' . $response);
	}

	public function updateOrder($order_id, $order_status_string, $courier_id = '', $courier_from_list = true, $tracking_no = '') {

		if ($this->config->get('openbay_amazon_status') != 1) {
			return;
		}

		/* Is called from admin? */
		if (!defined('HTTPS_CATALOG')) {
			return;
		}

		$amazon_order = $this->getOrder($order_id);

		if(!$amazon_order) {
			return;
		}

		$amazon_order_id = $amazon_order['amazon_order_id'];

		$log = new \Log('amazon.log');
		$log->write("Order's $amazon_order_id status changed to $order_status_string");

		$this->load->model('extension/openbay/amazon');
		$amazon_order_products = $this->model_extension_openbay_amazon->getAmazonOrderedProducts($order_id);

		$request_node = new \SimpleXMLElement('<Request/>');

		$request_node->addChild('AmazonOrderId', $amazon_order_id);
		$request_node->addChild('Status', $order_status_string);

		if(!empty($courier_id)) {
			if($courier_from_list) {
				$request_node->addChild('CourierId', $courier_id);
			} else {
				$request_node->addChild('CourierOther', $courier_id);
			}
			$request_node->addChild('TrackingNo', $tracking_no);
		}

		$order_items_node = $request_node->addChild('OrderItems');

		foreach ($amazon_order_products as $product) {
			$new_order_item = $order_items_node->addChild('OrderItem');
			$new_order_item->addChild('ItemId', htmlspecialchars($product['amazon_order_item_id']));
			$new_order_item->addChild('Quantity', (int)$product['quantity']);
		}

		$doc = new \DOMDocument('1.0');
		$doc->preserveWhiteSpace = false;
		$doc->loadXML($request_node->asXML());
		$doc->formatOutput = true;

		$this->model_extension_openbay_amazon->updateAmazonOrderTracking($order_id, $courier_id, $courier_from_list, !empty($courier_id) ? $tracking_no : '');
		$log->write('Request: ' . $doc->saveXML());
		$response = $this->call('order/update2', $doc->saveXML(), false);
		$log->write("Response for Order's status update: $response");
	}

	public function getCategoryTemplates() {
		$result = $this->call("productv2/RequestTemplateList");
		if(isset($result)) {
			return (array)json_decode($result);
		} else {
			return array();
		}
	}

	public function registerInsertion($data) {
		$result = $this->call("productv2/RegisterInsertionRequest", $data);
		if(isset($result)) {
			return (array)json_decode($result);
		} else {
			return array();
		}
	}

	public function insertProduct($data) {
		$result = $this->call("productv2/InsertProductRequest", $data);
		if(isset($result)) {
			return (array)json_decode($result);
		} else {
			return array();
		}
	}

	public function updateQuantities($data) {
		$result = $this->call("product/UpdateQuantityRequest", $data);
		if(isset($result)) {
			return (array)json_decode($result);
		} else {
			return array();
		}
	}

	public function getStockUpdatesStatus($data) {
		$result = $this->call("status/StockUpdates", $data);
		if(isset($result)) {
			return $result;
		} else {
			return false;
		}
	}

	public function putStockUpdateBulk($product_id_array, $end_inactive = false){
		$logger = new \Log('amazon_stocks.log');
		$logger->write('putStockUpdateBulk(), End inactive: ' . (int)$end_inactive . ', ids: ' . json_encode($product_id_array));

		$quantity_data = array();

		foreach($product_id_array as $product_id) {
			$linked_skus = $this->db->query("SELECT `amazon_sku` FROM `" . DB_PREFIX . "amazon_product_link` WHERE `product_id` = '" . (int)$product_id . "'")->rows;

			if (!empty($linked_skus)) {
				foreach ($linked_skus as $sku) {
					$product = $this->db->query("SELECT `quantity`, `status` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "'")->row;

					if (!empty($product)) {
						if ($end_inactive && $product['status'] == '0') {
							$quantity_data[$sku['amazon_sku']] = 0;
						} else {
							$quantity_data[$sku['amazon_sku']] = $product['quantity'];
						}
					}
				}
			} else {
				$logger->write('No linked SKU');
			}
		}

		if(!empty($quantity_data)) {
			$logger->write('New Qty:' . print_r($quantity_data, true));

			$response = $this->updateQuantities($quantity_data);

			$logger->write('API Response: ' . print_r($response, true));
		} else {
			$logger->write('No update needed');
		}
	}

	public function getOrderdProducts($order_id) {
		return $this->db->query("SELECT `op`.`product_id`, `p`.`quantity` as `quantity_left` FROM `" . DB_PREFIX . "order_product` as `op` LEFT JOIN `" . DB_PREFIX . "product` as `p` ON `p`.`product_id` = `op`.`product_id` WHERE `op`.`order_id` = '" . (int)$order_id . "'")->rows;
	}

	public function validate() {
		if($this->config->get('openbay_amazon_status') != 0 &&
			$this->config->get('openbay_amazon_token') != '' &&
			$this->config->get('openbay_amazon_encryption_key') != '' &&
			$this->config->get('openbay_amazon_encryption_iv') != ''){
			return true;
		} else {
			return false;
		}
	}

	public function deleteProduct($product_id){
		$this->db->query("DELETE FROM `" . DB_PREFIX . "amazon_product_link` WHERE `product_id` = '" . (int)$product_id . "'");
	}

	public function orderDelete($order_id){
		/**
		 * @todo
		 */
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazon_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if($qry->num_rows > 0) {
			return $qry->row;
		} else {
			return false;
		}
	}

	public function getCarriers() {
		return array(
			"USPS",
			"UPS",
			"UPSMI",
			"FedEx",
			"DHL",
			"Fastway",
			"GLS",
			"GO!",
			"Hermes Logistik Gruppe",
			"Royal Mail",
			"Parcelforce",
			"City Link",
			"TNT",
			"Target",
			"SagawaExpress",
			"NipponExpress",
			"YamatoTransport",
			"DHL Global Mail",
			"UPS Mail Innovations",
			"FedEx SmartPost",
			"OSM",
			"OnTrac",
			"Streamlite",
			"Newgistics",
			"Canada Post",
			"Blue Package",
			"Chronopost",
			"Deutsche Post",
			"DPD",
			"La Poste",
			"Parcelnet",
			"Poste Italiane",
			"SDA",
			"Smartmail",
			"FEDEX_JP",
			"JP_EXPRESS",
			"NITTSU",
			"SAGAWA",
			"YAMATO",
			"BlueDart",
			"AFL/Fedex",
			"Aramex",
			"India Post",
			"Professional",
			"DTDC",
			"Overnite Express",
			"First Flight",
			"Delhivery",
			"Lasership",
		);
	}

	public function parseCategoryTemplate($xml) {
		$simplexml = null;

		libxml_use_internal_errors(true);
		if(($simplexml = simplexml_load_string($xml)) == false) {
			return false;
		}

		$category = (string)$simplexml->filename;

		$tabs = array();
		foreach($simplexml->tabs->tab as $tab) {
			$attributes = $tab->attributes();
			$tabs[] = array(
				'id' => (string)$attributes['id'],
				'name' => (string)$tab->name,
			);
		}

		$fields = array();
		$field_types = array('required', 'desired', 'optional');
		foreach ($field_types as $type) {
			foreach ($simplexml->fields->$type->field as $field) {
				$attributes = $field->attributes();
				$fields[] = array(
					'name' => (string)$attributes['name'],
					'title' => (string)$field->title,
					'definition' => (string)$field->definition,
					'accepted' => (array)$field->accepted,
					'type' => (string)$type,
					'child' => false,
					'order' => (isset($attributes['order'])) ? (string)$attributes['order'] : '',
					'tab' => (string)$attributes['tab'],
				);
			}
			foreach ($simplexml->fields->$type->childfield as $field) {
				$attributes = $field->attributes();
				$fields[] = array(
					'name' => (string)$attributes['name'],
					'title' => (string)$field->title,
					'definition' => (string)$field->definition,
					'accepted' => (array)$field->accepted,
					'type' => (string)$type,
					'child' => true,
					'parent' => (array)$field->parent,
					'order' => (isset($attributes['order'])) ? (string)$attributes['order'] : '',
					'tab' => (string)$attributes['tab'],
				);
			}
		}

		foreach($fields as $index => $field) {
			$fields[$index]['unordered_index'] = $index;
		}

		usort($fields, array('openbay\Amazon','compareFields'));

		return array(
			'category' => $category,
			'fields' => $fields,
			'tabs' => $tabs,
		);
	}

	private static function compareFields($field1, $field2) {
		if($field1['order'] == $field2['order']) {
			return ($field1['unordered_index'] < $field2['unordered_index']) ? -1 : 1;
		} else if(!empty($field1['order']) && empty($field2['order'])) {
			return -1;
		} else if(!empty($field2['order']) && empty($field1['order'])) {
			return 1;
		} else {
			return ($field1['order'] < $field2['order']) ? -1 : 1;
		}
	}
}

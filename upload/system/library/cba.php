<?php
class CBA {
	private $access_key;
	private $secret_key;
	private $merchant_id;
	private $contract_id;
	private $marketplace;
	private $mode;

	public function __construct($merchant_id, $access_key, $secret_key, $marketplace) {
		$this->setMerchantId($merchant_id);
		$this->setAccessKey($access_key);
		$this->setSecretKey($secret_key);
		$this->setMarketplace($marketplace);
	}

	public function scheduleReports() {
		$args = $this->getCommonParameters();
		$args['Merchant'] = $this->getMerchantId();
		$args['Action'] = 'ManageReportSchedule';
		$args['Version'] = '2009-01-01';
		$args['ReportType'] = '_GET_ORDERS_DATA_';
		$args['Schedule'] = '_15_MINUTES_';

		$this->getMwsResponse('POST', '/', array(), $args);
	}

	public function processFeedResponses($settings, $db) {
		$qry = $db->query("SELECT `submission_id` FROM `" . DB_PREFIX . "order_amazon_report` WHERE `status` = 'processing'");

		$submission_ids = array();

		foreach ($qry->rows as $row) {
			$submission_ids[] = $row['submission_id'];
		}

		for ($i = 0; $i < count($submission_ids); $i += 50) {
			$ids = array_slice($submission_ids, $i, 50);

			$args = $this->getCommonParameters();
			$args['Merchant'] = $this->getMerchantId();
			$args['Action'] = 'GetReportList';
			$args['Version'] = '2009-01-01';
			$args['Acknowledged'] = 'false';

			$count = 1;

			foreach ($ids as $id) {
				$args['ReportRequestIdList.Id.' . $count++] = $id;
			}

			$response = $this->getMwsResponse('POST', '/', array(), $args);
			$xml = simplexml_load_string($response);

			if (isset($xml->Error->Code) && (string)$xml->Error->Code == 'RequestThrottled') {
				return;
			}

			if (isset($xml->GetReportListResult) || isset($xml->GetReportListByNextTokenResult)) {
				if (isset($xml->GetReportListResult)) {
					$list = $xml->GetReportListResult->ReportInfo;
					$next_token = ((string)$xml->GetReportListResult->HasNext == 'true') ? (string)$xml->GetReportListResult->NextToken : false;
				} else {
					$list = $xml->GetReportListByNextTokenResult->ReportInfo;
					$next_token = ((string)$xml->GetReportListByNextTokenResult->HasNext == 'true') ? (string)$xml->GetReportListByNextTokenResult->NextToken : false;
				}

				$report_ids = array();

				foreach ($list as $list_item) {
					$args = $this->getCommonParameters();
					$args['Merchant'] = $this->getMerchantId();
					$args['Action'] = 'GetReport';
					$args['Version'] = '2009-01-01';
					$args['ReportId'] = (string)$list_item->ReportId;

					$report = $this->getMwsResponse('POST', '/', array(), $args);

					$lines = explode("\n", $report);

					$errors = array();

					foreach ($lines as $line) {
						$values = explode("\t", $line);
						if (isset($values[5]) && ($values[4] == 'Error' || $values[4] == 'Fatal')) {
							$errors[] = 'Order ID: ' . $values[1] . '<br /> Order Item ID: ' . $values[2] . '<br /> Error Message: ' . trim($values[5]);
						}
					}

					if (empty($errors)) {
						$status = 'success';
					} else {
						$status = 'error';
					}

					$error_message = implode('<br />', $errors);

					$submission_id = (string)$list_item->ReportRequestId;

					$db->query("UPDATE `" . DB_PREFIX . "order_amazon_report` SET `status` = '" . $db->escape($status) . "', text = '" . $db->escape($error_message) . "' WHERE `submission_id` = '" . $db->escape($submission_id) . "'");

					$report_ids[] = (string)$list_item->ReportId;
				}

				$args = $this->getCommonParameters();
				$args['Merchant'] = $this->getMerchantId();
				$args['Action'] = 'UpdateReportAcknowledgements';
				$args['Version'] = '2009-01-01';
				$args['Acknowledged'] = 'true';

				for ($i = 1; $i <= count($report_ids); $i++) {
					$args['ReportIdList.Id.' . $i] = $report_ids[$i - 1];
				}

				$this->getMwsResponse('POST', '/', array(), $args);
			}
		}
	}

	public function processOrderReports($settings, $db) {
		$log = new Log('cba_cron.log');
		$log->write('Started cron job');

		$args = $this->getCommonParameters();
		$args['Merchant'] = $this->getMerchantId();
		$args['Action'] = 'GetReportList';
		$args['Version'] = '2009-01-01';
		$args['ReportTypeList.Type.1'] = '_GET_ORDERS_DATA_';
		$args['Acknowledged'] = 'false';

		$response = $this->getMwsResponse('POST', '/', array(), $args);

		$xml = simplexml_load_string($response);

		while ($xml && (isset($xml->GetReportListResult) || isset($xml->GetReportListByNextTokenResult))) {
			if (isset($xml->GetReportListResult)) {
				$list = $xml->GetReportListResult->ReportInfo;
				$next_token = ((string)$xml->GetReportListResult->HasNext == 'true') ? (string)$xml->GetReportListResult->NextToken : false;
			} else {
				$list = $xml->GetReportListByNextTokenResult->ReportInfo;
				$next_token = ((string)$xml->GetReportListByNextTokenResult->HasNext == 'true') ? (string)$xml->GetReportListByNextTokenResult->NextToken : false;
			}

			$report_ids = array();

			foreach ($list as $list_item) {
				// retrieve report
				$args = $this->getCommonParameters();
				$args['Merchant'] = $this->getMerchantId();
				$args['Action'] = 'GetReport';
				$args['Version'] = '2009-01-01';
				$args['ReportId'] = (string)$list_item->ReportId;

				$report_response = $this->getMwsResponse('POST', '/', array(), $args);
				$report_xml = simplexml_load_string($report_response);

				foreach ($report_xml->Message as $message) {
					$amazon_order_id = (string)$message->OrderReport->AmazonOrderID;

					$billing_email = (string)$message->OrderReport->BillingData->BuyerEmailAddress;
					$billing_name = (string)$message->OrderReport->BillingData->BuyerName;
					$billing_phone_number = (string)$message->OrderReport->BillingData->BuyerPhoneNumber;

					$shipping_name = (string)$message->OrderReport->FulfillmentData->Address->Name;
					$shipping_address1 = (string)$message->OrderReport->FulfillmentData->Address->AddressFieldOne;
					$shipping_address2 = (string)$message->OrderReport->FulfillmentData->Address->AddressFieldTwo;
					$shipping_city = (string)$message->OrderReport->FulfillmentData->Address->City;
					$shipping_zone = (string)$message->OrderReport->FulfillmentData->Address->StateOrRegion;
					$shipping_post_code = (string)$message->OrderReport->FulfillmentData->Address->PostalCode;
					$shipping_country_code = (string)$message->OrderReport->FulfillmentData->Address->CountryCode;

					$result = $db->query("SELECT `order_id` FROM `" . DB_PREFIX . "order_amazon` WHERE `amazon_order_id` = '" . $db->escape($amazon_order_id) . "'")->row;

					if (!isset($result['order_id']) || empty($result['order_id'])) {
						$log->write("Order " . $amazon_order_id . " was not found");
						continue;
					}

					$order_id = $result['order_id'];

					$db->query("UPDATE `" . DB_PREFIX . "order` AS `o`, `" . DB_PREFIX . "order_amazon` `oa` SET `o`.`payment_firstname` = '" . $db->escape($billing_name) . "', `o`.`firstname` = '" . $db->escape($billing_name) . "', `o`.`email` = '" . $db->escape($billing_email) . "', `o`.`telephone` = '" . $db->escape($billing_phone_number) . "', `o`.`shipping_firstname` = '" . $db->escape($shipping_name) . "', `o`.`shipping_address_1` = '" . $db->escape($shipping_address1) . "', `o`.`shipping_address_2` = '" . $db->escape($shipping_address2) . "', `o`.`shipping_city` = '" . $db->escape($shipping_city) . "', `o`.`shipping_zone` = '" . $db->escape($shipping_zone) . "', `o`.`shipping_country` = '" . $db->escape($shipping_country_code) . "', `o`.`shipping_postcode` = '" . $db->escape($shipping_post_code) . "', `o`.`order_status_id` = " . (int)$settings->get('amazon_checkout_ready_status_id') . " WHERE `o`.`order_id` = " . (int)$order_id);

					$db->query("INSERT INTO `" . DB_PREFIX . "order_history` (`order_id`, `order_status_id`, `comment`, `date_added`) VALUES (" . (int)$order_id . ", " . (int)$settings->get('amazon_checkout_ready_status_id') . ", '', NOW())");

					foreach ($message->OrderReport->Item as $item) {
						$amazon_order_item_code = (string)$item->AmazonOrderItemCode;
						$order_product_id = (string)$item->SKU;

						$db->query("REPLACE INTO `" . DB_PREFIX . "order_amazon_product` (`order_product_id`, `amazon_order_item_code`) SELECT `op`.`order_product_id`, '" . $db->escape($amazon_order_item_code) . "' FROM `" . DB_PREFIX . "order_product` `op` WHERE `op`.`order_product_id` = '" . $db->escape($order_product_id) . "'");
					}
				}

				$report_ids[] = (string)$list_item->ReportId;
			}

			$args = $this->getCommonParameters();
			$args['Merchant'] = $this->getMerchantId();
			$args['Action'] = 'UpdateReportAcknowledgements';
			$args['Version'] = '2009-01-01';
			$args['Acknowledged'] = 'true';

			for ($i = 1; $i <= count($report_ids); $i++) {
				$args['ReportIdList.Id.' . $i] = $report_ids[$i - 1];
			}

			$this->getMwsResponse('POST', '/', array(), $args);

			if ($next_token) {
				$args = $this->getCommonParameters();
				$args['Merchant'] = $this->getMerchantId();
				$args['Action'] = 'GetReportListByNextToken';
				$args['Version'] = '2009-01-01';
				$args['NextToken'] = $next_token;

				$response = $this->getMwsResponse('POST', '/', array(), $args);

				$xml = simplexml_load_string($response);

				if (isset($xml->Error->Code) && (string)$xml->Error->Code == 'RequestThrottled') {
					$xml = false;
				}
			} else {
				$xml = false;
			}
		}

		$log->write('Finished cron job');
	}

	public function orderAdjustment($flat_file) {
		$headers = array(
			'Content-Type: text/xml',
			'Content-MD5: ' . base64_encode(md5($flat_file, true)),
		);

		$args = $this->getCommonParameters();
		$args['Merchant'] = $this->getMerchantId();
		$args['Action'] = 'SubmitFeed';
		$args['Version'] = '2009-01-01';
		$args['FeedType'] = '_POST_FLAT_FILE_PAYMENT_ADJUSTMENT_DATA_';

		$response = $this->getMwsResponse('POST', '/', $args, array(), $flat_file, $headers);

		$response_xml = simplexml_load_string($response);

		$cba_log = new Log('cba.log');
		$cba_log->write("Order was adjusted. Response:\n" . print_r($response_xml, 1));

		return $response;
	}

	public function orderCanceled($order) {
		$flat = "TemplateType=OrderCancellation\tVersion=1.0/1.0.1\tThis row for Amazon.com use only.  Do not modify or delete.\n";
		$flat .= "order-id\tmerchant-order-id\tcancellation-reason-code\tamazon-order-item-code\n";

		foreach ($order['products'] as $product) {
			$flat .= $order['amazon_order_id'] . "\t\tGeneralAdjustment\t" . $product['amazon_order_item_code'] . "\n";
		}

		$headers = array(
			'Content-Type: text/xml',
			'Content-MD5: ' . base64_encode(md5($flat, true)),
		);

		$args = $this->getCommonParameters();
		$args['Merchant'] = $this->getMerchantId();
		$args['Action'] = 'SubmitFeed';
		$args['Version'] = '2009-01-01';
		$args['FeedType'] = '_POST_FLAT_FILE_ORDER_ACKNOWLEDGEMENT_DATA_';

		$response = $this->getMwsResponse('POST', '/', $args, array(), $flat, $headers);

		$response_xml = simplexml_load_string($response);

		$cba_log = new Log('cba.log');
		$cba_log->write('Marked order ' . $order['amazon_order_id'] . ' as canceled. Response  ' . print_r($response_xml, 1));
	}

	public function orderShipped($order) {
		$xml = '<?xml version="1.0"?>
<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">
  <Header>
	<DocumentVersion>1.01</DocumentVersion>
	<MerchantIdentifier>' . $this->getMerchantId() . '</MerchantIdentifier>
  </Header>
  <MessageType>OrderFulfillment</MessageType>
  <Message>
	<MessageID>1</MessageID>
	<OrderFulfillment>
	  <AmazonOrderID>' . $order['amazon_order_id'] . '</AmazonOrderID>
	  <FulfillmentDate>' . date('c') . '</FulfillmentDate>
';

		foreach ($order['products'] as $product) {
			$xml .= '
	  <Item>
		<AmazonOrderItemCode>' . $product['amazon_order_item_code'] . '</AmazonOrderItemCode>
		<Quantity>' . $product['quantity'] . '</Quantity>
	  </Item>
';
		}

		$xml .= '
	</OrderFulfillment>
  </Message>
</AmazonEnvelope>';

		$headers = array(
			'Content-Type: text/xml',
			'Content-MD5: ' . base64_encode(md5($xml, true)),
		);

		$args = $this->getCommonParameters();
		$args['Merchant'] = $this->getMerchantId();
		$args['Action'] = 'SubmitFeed';
		$args['Version'] = '2009-01-01';
		$args['FeedType'] = '_POST_ORDER_FULFILLMENT_DATA_';

		$response = $this->getMwsResponse('POST', '/', $args, array(), $xml, $headers);

		$response_xml = simplexml_load_string($response);

		$cba_log = new Log('cba.log');
		$cba_log->write('Marked order ' . $order['amazon_order_id'] . ' as shippped. Response  ' . print_r($response_xml, 1));
	}

	public function setPurchaseItems($parameters) {
		$url_params = $this->getCommonParameters();
		$url_params['Action'] = 'SetPurchaseItems';
		$url_params['PurchaseContractId'] = $parameters['contract_id'];

		$i = 1;
		foreach ($parameters['products'] as $product) {
			$url_params['PurchaseItems.PurchaseItem.' . $i . '.MerchantId'] = $this->getMerchantId();
			$url_params['PurchaseItems.PurchaseItem.' . $i . '.MerchantItemId'] = $product['model'];
			$url_params['PurchaseItems.PurchaseItem.' . $i . '.SKU'] = $product['model'];
			$url_params['PurchaseItems.PurchaseItem.' . $i . '.Quantity'] = $product['quantity'];
			$url_params['PurchaseItems.PurchaseItem.' . $i . '.Title'] = $product['title'];
			$url_params['PurchaseItems.PurchaseItem.' . $i . '.UnitPrice.Amount'] = $product['price'];
			$url_params['PurchaseItems.PurchaseItem.' . $i++ . '.UnitPrice.CurrencyCode'] = $parameters['currency'];
		}

		$response = $this->getResponse('GET', $url_params);
		$xml = simplexml_load_string($response);

		if (isset($xml->ResponseMetadata->RequestId)) {
			return true;
		}

		return $xml;
	}

	public function setContractCharges($parameters) {
		$url_params = $this->getCommonParameters();
		$url_params['Action'] = 'SetContractCharges';
		$url_params['PurchaseContractId'] = $parameters['contract_id'];
		$url_params['Charges.Shipping.Amount'] = $parameters['shipping_price'];
		$url_params['Charges.Shipping.CurrencyCode'] = $parameters['currency'];

		if (isset($parameters['discount'])) {
			$url_params['Charges.Promotions.Promotion.1.PromotionId'] = '1';
			$url_params['Charges.Promotions.Promotion.1.Description'] = '';
			$url_params['Charges.Promotions.Promotion.1.Discount.Amount'] = $parameters['discount'];
			$url_params['Charges.Promotions.Promotion.1.Discount.CurrencyCode'] = $parameters['currency'];
		}

		$response = $this->getResponse('GET', $url_params);
		$xml = simplexml_load_string($response);

		if (isset($xml->ResponseMetadata->RequestId)) {
			return true;
		}

		return false;
	}

	public function completePurchaseContracts($parameters) {
		$url_params = $this->getCommonParameters();
		$url_params['Action'] = 'CompletePurchaseContract';
		$url_params['PurchaseContractId'] = $parameters['contract_id'];
		$url_params['IntegratorId'] = 'WelfordMedia';
		$url_params['IntegratorName'] = 'WelfordMedia V2.0';

		$response = $this->getResponse('GET', $url_params);

		$order_ids = array();

		$xml = simplexml_load_string($response);

		if (isset($xml->CompletePurchaseContractResult->OrderIds->OrderId)) {
			foreach ($xml->CompletePurchaseContractResult->OrderIds->OrderId as $amazon_order_id) {
				$order_ids[] = (string)$amazon_order_id;
			}
		}

		return $order_ids;
	}

	public function getPurchaseContract($contract_id) {
		$parameters = $this->getCommonParameters();
		$parameters['Action'] = 'GetPurchaseContract';
		$parameters['PurchaseContractId'] = $contract_id;

		return $this->getResponse('GET', $parameters);
	}

	private function getMwsResponse($http_method, $uri, $get_args, $post_args, $post_body = '', $headers = array()) {
		if ($this->getMarketplace() == 'uk') {
			$string_to_sign = $http_method . "\nmws.amazonservices.co.uk\n" . $uri . "\n";
		} elseif ($this->getMarketplace() == 'de') {
			$string_to_sign = $http_method . "\nmws.amazonservices.de\n" . $uri . "\n";
		}

		if (!empty($get_args)) {
			uksort($get_args, 'strcmp');
			$string_to_sign .= $this->getParametersAsString($get_args);
			$get_args['Signature'] = base64_encode(hash_hmac('sha256', $string_to_sign, $this->getSecretKey(), true));
		} else {
			uksort($post_args, 'strcmp');
			$string_to_sign .= $this->getParametersAsString($post_args);
			$post_args['Signature'] = base64_encode(hash_hmac('sha256', $string_to_sign, $this->getSecretKey(), true));
		}

		if (empty($post_body)) {
			$post_data = $this->getParametersAsString($post_args);
		} else {
			$post_data = $post_body;
		}

		if ($this->getMarketplace() == 'uk') {
			$request_url = 'https://mws.amazonservices.co.uk' . $uri;
		} elseif ($this->getMarketplace() == 'de') {
			$request_url = 'https://mws.amazonservices.de' . $uri;
		}

		if (!empty($get_args)) {
			$request_url .= '?' . $this->getParametersAsString($get_args);
		}

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_URL => $request_url,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_BINARYTRANSFER => 1,
			CURLOPT_POSTFIELDS => $post_data,
		);

		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		$response = curl_exec($ch);

		return $response;
	}

	private function getResponse($http_method, $parameters) {
		$string_to_sign = $http_method . "\n";

		if ($this->getMode() == 'live') {
			if ($this->getMarketplace() == 'uk') {
				$string_to_sign .= "payments.amazon.co.uk\n";
			} elseif ($this->getMarketplace() == 'de') {
				$string_to_sign .= "payments.amazon.de\n";
			}
		} else {
			if ($this->getMarketplace() == 'uk') {
				$string_to_sign .= "payments-sandbox.amazon.co.uk\n";
			} elseif ($this->getMarketplace() == 'de') {
				$string_to_sign .= "payments-sandbox.amazon.de\n";
			}
		}

		$string_to_sign .= "/cba/api/purchasecontract/\n";

		uksort($parameters, 'strcmp');
		$string_to_sign .= $this->getParametersAsString($parameters);

		$parameters['Signature'] = base64_encode(hash_hmac('sha256', $string_to_sign, $this->getSecretKey(), true));

		if ($this->getMode() == 'live') {
			if ($this->getMarketplace() == 'uk') {
				$end_point = "payments.amazon.co.uk";
			} elseif ($this->getMarketplace() == 'de') {
				$end_point = "payments.amazon.de";
			}
		} else {
			if ($this->getMarketplace() == 'uk') {
				$end_point = "payments-sandbox.amazon.co.uk";
			} elseif ($this->getMarketplace() == 'de') {
				$end_point = "payments-sandbox.amazon.de";
			}
		}

		$request_url = 'https://' . $end_point . '/cba/api/purchasecontract/?' . $this->getParametersAsString($parameters);

		$curl_options = array(
			CURLOPT_URL => $request_url,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POST => $http_method == 'POST' ? 1 : 0,
		);

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);
		$response = curl_exec($ch);

		curl_close($ch);

		return $response;
	}

	private function urlencode($value) {
		return str_replace('%7E', '~', rawurlencode($value));
	}

	public function getParametersAsString(array $parameters) {
		$query_parameters = array();
		foreach ($parameters as $key => $value) {
			$query_parameters[] = $key . '=' . $this->urlencode($value);
		}
		return implode('&', $query_parameters);
	}

	private function getCommonParameters() {
		return array(
			'SignatureMethod' => 'HmacSHA256',
			'AWSAccessKeyId' => $this->getAccessKey(),
			'SignatureVersion' => '2',
			'Timestamp' => date('c'),
			'Version' => '2010-08-31',
		);
	}

	public function getAccessKey() {
		return $this->access_key;
	}

	public function setAccessKey($access_key) {
		$this->access_key = $access_key;
	}

	public function getSecretKey() {
		return $this->secret_key;
	}

	public function setSecretKey($secret_key) {
		$this->secret_key = $secret_key;
	}

	public function getMerchantId() {
		return $this->merchant_id;
	}

	public function setMerchantId($merchant_id) {
		$this->merchant_id = $merchant_id;
	}

	public function getContractId() {
		return $this->contract_id;
	}

	public function setContractId($contract_id) {
		$this->contract_id = $contract_id;
	}

	public function getMode() {
		return $this->mode;
	}

	public function setMode($mode) {
		$this->mode = $mode;
	}

	public function getMarketplace() {
		return $this->marketplace;
	}

	public function setMarketplace($marketplace) {
		$this->marketplace = $marketplace;
	}
}
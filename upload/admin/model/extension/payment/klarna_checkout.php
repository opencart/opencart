<?php
use Klarna\Rest\Transport\Connector as KCConnector;
use Klarna\Rest\Transport\ConnectorInterface as KCConnectorInterface;
use Klarna\Rest\OrderManagement\Order as KCOrder;

class ModelExtensionPaymentKlarnaCheckout extends Model {
	public function connector($merchant_id, $secret, $url) {
		try {
			$connector = KCConnector::create(
				$merchant_id,
				$secret,
				$url
			);

			return $connector;
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omRetrieve(KCConnector $connector, $order_id) {
		try {
			$order = new KCOrder($connector, $order_id);

			return $order->fetch();
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omCancel(KCConnector $connector, $order_id) {
		try {
			$order = new KCOrder($connector, $order_id);

			return $order->cancel();
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omCapture(KCConnector $connector, $order_id, $data) {
		try {
			$order = new KCOrder($connector, $order_id);

			return $order->createCapture($data);
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omRefund(KCConnector $connector, $order_id, $data) {
		try {
			$order = new KCOrder($connector, $order_id);

			return $order->refund($data);
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omExtendAuthorizationTime(KCConnector $connector, $order_id) {
		try {
			$order = new KCOrder($connector, $order_id);

			return $order->extendAuthorizationTime();
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omUpdateMerchantReference(KCConnector $connector, $order_id, $data) {
		try {
			$order = new KCOrder($connector, $order_id);

			return $order->updateMerchantReferences($data);
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omUpdateAddress(KCConnector $connector, $order_id, $data) {
		try {
			$order = new KCOrder($connector, $order_id);

			return $order->updateCustomerDetails($data);
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omReleaseAuthorization(KCConnector $connector, $order_id) {
		try {
			$order = new KCOrder($connector, $order_id);

			return $order->releaseRemainingAuthorization();
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omShippingInfo(KCConnector $connector, $order_id, $capture_id, $data) {
		try {
			$order = new KCOrder($connector, $order_id);

			$capture = $order->fetchCapture($capture_id);
			return $capture->addShippingInfo($data);
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omCustomerDetails(KCConnector $connector, $order_id, $capture_id, $data) {
		try {
			$order = new KCOrder($connector, $order_id);

			$capture = $order->fetchCapture($capture_id);
			return $capture->updateCustomerDetails($data);
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function omTriggerSendOut(KCConnector $connector, $order_id, $capture_id) {
		try {
			$order = new KCOrder($connector, $order_id);

			$capture = $order->fetchCapture($capture_id);
			return $capture->triggerSendout();
		} catch (\Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function getConnector($accounts, $currency) {
		$klarna_account = false;
		$connector = false;

		if ($accounts && $currency) {
			foreach ($accounts as $account) {
				if ($account['currency'] == $currency) {
					if ($account['environment'] == 'test') {
						if ($account['api'] == 'NA') {
							$base_url = KCConnectorInterface::NA_TEST_BASE_URL;
						} elseif ($account['api'] == 'EU')  {
							$base_url = KCConnectorInterface::EU_TEST_BASE_URL;
						}
					} elseif ($account['environment'] == 'live') {
						if ($account['api'] == 'NA') {
							$base_url = KCConnectorInterface::NA_BASE_URL;
						} elseif ($account['api'] == 'EU')  {
							$base_url = KCConnectorInterface::EU_BASE_URL;
						}
					}

					$klarna_account = $account;
					$connector = $this->connector(
						$account['merchant_id'],
						$account['secret'],
						$base_url
					);

					break;
				}
			}
		}

		return array($klarna_account, $connector);
	}

	public function getOrder($order_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "klarna_checkout_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1")->row;
	}

	public function checkForPaymentTaxes() {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "tax_rule tr ON (`tr`.`tax_class_id` = `p`.`tax_class_id`) WHERE `tr`.`based` = 'payment'");

		return $query->row['total'];
	}

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "klarna_checkout_order` (
			  `klarna_checkout_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `order_ref` VARCHAR(255) NOT NULL,
			  `data` text NOT NULL,
			  PRIMARY KEY (`klarna_checkout_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('extension_klarna_checkout_js', 'catalog/controller/checkout/checkout/before', 'extension/payment/klarna_checkout/eventLoadCheckoutJs');
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "klarna_checkout_order`;");

		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('extension_klarna_checkout_js');
	}

	public function log($data) {
		if ($this->config->get('payment_klarna_checkout_debug')) {
			$backtrace = debug_backtrace();
			$log = new Log('klarna_checkout.log');
			$log->write('(' . $backtrace[1]['class'] . '::' . $backtrace[1]['function'] . ') - ' . print_r($data, true));
		}
	}
}
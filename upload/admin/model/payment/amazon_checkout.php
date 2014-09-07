<?php
class ModelPaymentAmazonCheckout extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "order_amazon` (
				`order_id` int(11) NOT NULL,
				`amazon_order_id` varchar(255) NOT NULL,
				`free_shipping`  tinyint NOT NULL DEFAULT 0,
				KEY `amazon_order_id` (`amazon_order_id`),
				PRIMARY KEY `order_id` (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "order_amazon_product` (
			`order_product_id`  int NOT NULL ,
			`amazon_order_item_code`  varchar(255) NOT NULL,
			PRIMARY KEY (`order_product_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "order_amazon_report` (
				`order_id`  int NOT NULL ,
				`submission_id`  varchar(255) NOT NULL ,
				`status` enum('processing','error','success') NOT NULL ,
				`text`  text NOT NULL,
				PRIMARY KEY (`submission_id`),
				INDEX (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "order_total_tax` (
				`order_total_id`  INT,
				`code` VARCHAR(255),
				`tax` DECIMAL(10, 4) NOT NULL,
				PRIMARY KEY (`order_total_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "order_amazon`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "order_amazon_product`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "order_amazon_report`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "order_total_tax`;");
	}

	public function orderStatusChange($order_id, $data) {
		if ($this->config->get('amazon_checkout_status') == 1) {
			$order = $this->getOrder($order_id);

			if ($order) {
				$this->load->library('cba');
				$cba = new CBA($this->config->get('amazon_checkout_merchant_id'), $this->config->get('amazon_checkout_access_key'), $this->config->get('amazon_checkout_access_secret'), $this->config->get('amazon_checkout_marketplace'));

				if ($data['order_status_id'] == $this->config->get('amazon_checkout_shipped_status_id')) {
					$cba->orderShipped($order);
				}

				if ($data['order_status_id'] == $this->config->get('amazon_checkout_canceled_status_id')) {
					$cba->orderCanceled($order);
				}
			}
		}
	}

	public function addReportSubmission($order_id, $feed_submissionid) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_amazon_report` (`order_id`, `submission_id`, `status`, `text`) VALUES (" . (int)$order_id . ", '" . $this->db->escape($feed_submissionid) . "', 'processing', '')");
	}

	public function getReportSubmissions($order_id) {
		return $this->db->query("SELECT `submission_id`, `status`, `text` FROM `" . DB_PREFIX . "order_amazon_report` WHERE `order_id` = " . (int)$order_id)->rows;
	}

	public function getOrder($order_id) {
		$order = array();

		$result = $this->db->query("SELECT amazon_order_id FROM " . DB_PREFIX . "order_amazon WHERE order_id = " . (int)$order_id);

		if ($result->num_rows) {
			$order['amazon_order_id'] = $result->row['amazon_order_id'];

			$order['products'] = array();

			$results = $this->db->query("SELECT oap.order_product_id, amazon_order_item_code, op.quantity FROM " . DB_PREFIX . "order_amazon_product oap JOIN " . DB_PREFIX . "order_product op USING(order_product_id) WHERE order_id = " . (int)$order_id . "
			")->rows;

			foreach ($results as $result) {
				$order['products'][$result['order_product_id']] = array(
					'amazon_order_item_code' => $result['amazon_order_item_code'],
					'quantity' => $result['quantity'],
				);
			}
		}

		return $order;
	}
}
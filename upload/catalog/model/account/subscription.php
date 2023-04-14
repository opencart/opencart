<?php
namespace Opencart\Catalog\Model\Account;
class Subscription extends \Opencart\System\Engine\Model {
	public function getSubscription(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` `s` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->row;
	}

	public function getSubscriptionByOrderProductId(int $order_id, int $order_product_id): array {
		$subscription_data = [];

		$query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "subscription` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

		if ($query->num_rows) {
			$subscription_data = $query->row;

			$subscription_data['payment_method'] = ($query->row['payment_method'] ? json_decode($query->row['payment_method'], true) : '');
			$subscription_data['shipping_method'] = ($query->row['shipping_method'] ? json_decode($query->row['shipping_method'], true) : '');
		}

		return $subscription_data;
	}

	public function getSubscriptions(array $data = []): array {
        $sql = "SELECT * FROM `" . DB_PREFIX . "subscription`";

        $implode = [];

        $implode[] = "`customer_id` = '" . (int)$this->customer->getId() . "'";
		
		if (!empty($data['filter_subscription_id'])) {
            $implode[] = "`subscription_id` = '" . (int)$data['filter_subscription_id'] . "'";
        }

        if (!empty($data['filter_date_next'])) {
            $implode[] = "DATE(`date_next`) = DATE('" . $this->db->escape($data['filter_date_next']) . "')";
        }

        if (!empty($data['filter_subscription_status_id'])) {
            $implode[] = "`subscription_status_id` = '" . (int)$data['filter_subscription_status_id'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sql .= " ORDER BY `order_id` DESC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

	public function getTotalSubscriptions(array $data = []): int {
        $sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription`";

        $implode = [];

        $implode[] = "`customer_id` = '" . (int)$this->customer->getId() . "'";
		
		if (!empty($data['filter_subscription_id'])) {
            $implode[] = "`subscription_id` = '" . (int)$data['filter_subscription_id'] . "'";
        }

        if (!empty($data['filter_date_next'])) {
            $implode[] = "DATE(`date_next`) = DATE('" . $this->db->escape($data['filter_date_next']) . "')";
        }

        if (!empty($data['filter_subscription_status_id'])) {
            $implode[] = "`subscription_status_id` = '" . (int)$data['filter_subscription_status_id'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return (int)$query->row['total'];
    }

	public function getTotalSubscriptionByShippingAddressId(int $address_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `shipping_address_id` = '" . (int)$address_id . "'");

		return (int)$query->row['total'];
	}

	public function getTotalSubscriptionByPaymentAddressId(int $address_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `payment_address_id` = '" . (int)$address_id . "'");

		return (int)$query->row['total'];
	}
}

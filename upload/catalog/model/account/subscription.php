<?php
namespace Opencart\Catalog\Model\Account;
class Subscription extends \Opencart\System\Engine\Model {
	public function editSubscriptionStatus(int $subscription_id, bool $subscription_status_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (bool)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}
	
	public function editTrialRemaining(int $subscription_id, int $trial_remaining): void {
        $this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `trial_remaining` = '" . (int)$trial_remaining . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
    }

    public function editDateNext(int $subscription_id, string $date_next): void {
        $this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `date_next` = '" . $this->db->escape($date_next) . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
    }

	public function getSubscription(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` `s` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->row;
	}

	public function getSubscriptions(array $data): array {
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
	
	public function getSubscriptionByReference(string $reference): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` WHERE `reference` = '" . $this->db->escape($reference) . "'");

		return $query->row;
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

	public function getTransactions(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_transaction` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return $query->rows;
	}

	public function addTransaction(int $subscription_id, int $order_id, int $transaction_id, string $description, float $amount, int $type, string $payment_method, string $payment_code): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_transaction` SET `subscription_id` = '" . (int)$subscription_id . "', `order_id` = '" . (int)$order_id . "', `transaction_id` = '" . (int)$transaction_id . "', `description` = '" . $this->db->escape($description) . "', `amount` = '" . (float)$amount . "', `type` = '" . (int)$type . "', `payment_method` = '" . $this->db->escape($payment_method) . "', `payment_code` = '" . $this->db->escape($payment_code) . "', `date_added` = NOW()");
	}
}

<?php
namespace Opencart\Admin\Model\Sale;
/**
 * Class Returns
 *
 * Can be loaded using $this->load->model('sale/returns');
 *
 * @package Opencart\Admin\Model\Sale
 */
class Returns extends \Opencart\System\Engine\Model {
	/**
	 * Add Return
	 *
	 * Create a new return record in the database.
	 *
	 * @param array<string, mixed> $data array of data to be inserted
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $return_data = [
	 *   'order_id'         => 1,
	 *   'product_id'       => 1,
	 *   'customer_id'      => 1,
	 *   'firstname'        => 'John',
	 *   'lastname'         => 'Doe',
	 *   'email'            => 'demo@opencart.com'
	 *   'telephone'        => '1234567890',
	 *   'product'          => 'Product Name',
	 *   'model'            => 'Product Model',
	 *   'quantity'         => 1,
	 *   'opened'           => 1,
	 *   'return_reason_id' => 1,
	 *   'return_action_id' => 1,
	 *   'comment'          => 'Comment',
	 *   'date_ordered'     => '2021-01-01'
	 * ];
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $return_id = $this->model_sale_returns->addReturn($return_data);
	 */
	public function addReturn(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET `order_id` = '" . (int)$data['order_id'] . "', `product_id` = '" . (int)$data['product_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `product` = '" . $this->db->escape((string)$data['product']) . "', `model` = '" . $this->db->escape((string)$data['model']) . "', `quantity` = '" . (int)$data['quantity'] . "', `opened` = '" . (int)$data['opened'] . "', `return_reason_id` = '" . (int)$data['return_reason_id'] . "', `return_action_id` = '" . (int)$data['return_action_id'] . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "', `date_ordered` = '" . $this->db->escape((string)$data['date_ordered']) . "', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Edit Return
	 *
	 * Edit return record in the database.
	 *
	 * @param int                  $return_id primary key of the return record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $return_data = [
	 *   'order_id'         => 1,
	 *   'product_id'       => 1,
	 *   'customer_id'      => 1,
	 *   'firstname'        => 'John',
	 *   'lastname'         => 'Doe',
	 *   'email'            => 'demo@opencart.com'
	 *   'telephone'        => '1234567890',
	 *   'product'          => 'Product Name',
	 *   'model'            => 'Product Model',
	 *   'quantity'         => 1,
	 *   'opened'           => 1,
	 *   'return_reason_id' => 1,
	 *   'return_action_id' => 1,
	 *   'comment'          => 'Comment',
	 *   'date_ordered'     => '2021-01-01'
	 * ];
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $this->model_sale_returns->editReturn($return_id, $return_data);
	 */
	public function editReturn(int $return_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET `order_id` = '" . (int)$data['order_id'] . "', `product_id` = '" . (int)$data['product_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `product` = '" . $this->db->escape((string)$data['product']) . "', `model` = '" . $this->db->escape((string)$data['model']) . "', `quantity` = '" . (int)$data['quantity'] . "', `opened` = '" . (int)$data['opened'] . "', `return_reason_id` = '" . (int)$data['return_reason_id'] . "', `return_action_id` = '" . (int)$data['return_action_id'] . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "', `date_ordered` = '" . $this->db->escape((string)$data['date_ordered']) . "', `date_modified` = NOW() WHERE `return_id` = '" . (int)$return_id . "'");
	}

	/**
	 * Edit Return Status ID
	 *
	 * Edit return statuses by return_status record in the database.
	 *
	 * @param int $return_id        primary key of the return record
	 * @param int $return_status_id primary key of the return status record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $this->model_sale_returns->editReturnStatusId($return_id, $return_status_id);
	 */
	public function editReturnStatusId(int $return_id, int $return_status_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET `return_status_id` = '" . (int)$return_status_id . "', `date_modified` = NOW() WHERE `return_id` = '" . (int)$return_id . "'");
	}

	/**
	 * Delete Return
	 *
	 * Delete return record in the database.
	 *
	 * @param int $return_id primary key of the return record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $this->model_sale_returns->deleteReturn($return_id);
	 */
	public function deleteReturn(int $return_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE `return_id` = '" . (int)$return_id . "'");

		$this->deleteHistories($return_id);
	}

	/**
	 * Get Return
	 *
	 * Get the record of the return record in the database.
	 *
	 * @param int $return_id primary key of the return record
	 *
	 * @return array<string, mixed> return record that has return ID
	 *
	 * @example
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $return_info = $this->model_sale_returns->getReturn($return_id);
	 */
	public function getReturn(int $return_id): array {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) FROM `" . DB_PREFIX . "customer` `c` WHERE `c`.`customer_id` = `r`.`customer_id`) AS `customer`, (SELECT `c`.`language_id` FROM `" . DB_PREFIX . "customer` `c` WHERE `c`.`customer_id` = `r`.`customer_id`) AS `language_id`, (SELECT `rs`.`name` FROM `" . DB_PREFIX . "return_status` `rs` WHERE `rs`.`return_status_id` = `r`.`return_status_id` AND `rs`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `return_status` FROM `" . DB_PREFIX . "return` `r` WHERE `r`.`return_id` = '" . (int)$return_id . "'");

		return $query->row;
	}

	/**
	 * Get Returns
	 *
	 * Get the record of the return records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> return records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *   'filter_return_id'        => 1,
	 *   'filter_customer'         => 'John Doe',
	 *   'filter_order_id'         => 1,
	 *   'filter_product'          => 'Product Name',
	 *   'filter_model'            => 'Product Model',
	 *   'filter_return_status_id' => 1,
	 *   'filter_date_from'        => '2021-01-01',
	 *   'filter_date_to'          => '2021-01-31'
	 * ];
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $results = $this->model_sale_returns->getReturns();
	 */
	public function getReturns(array $data = []): array {
		$sql = "SELECT *, CONCAT(`r`.`firstname`, ' ', `r`.`lastname`) AS `customer`, (SELECT `rs`.`name` FROM `" . DB_PREFIX . "return_status` `rs` WHERE `rs`.`return_status_id` = `r`.`return_status_id` AND `rs`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `return_status` FROM `" . DB_PREFIX . "return` `r`";

		$implode = [];

		if (!empty($data['filter_return_id'])) {
			$implode[] = "`r`.`return_id` = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "`r`.`order_id` = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "LCASE(CONCAT(`r`.`firstname`, ' ', `r`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_customer']) . '%') . "'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "LCASE(`r`.`product` = '" . $this->db->escape(oc_strtolower($data['filter_product'])) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "LCASE(`r`.`model` = '" . $this->db->escape(oc_strtolower($data['filter_model'])) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "`r`.`return_status_id` = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`r`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`r`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			'return_id'     => 'r.return_id',
			'order_id'      => 'r.order_id',
			'customer'      => 'customer',
			'product'       => 'r.product',
			'model'         => 'r.model',
			'return_status' => 'return_status',
			'date_added'    => 'r.date_added',
			'date_modified' => 'r.date_modified'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `r`.`return_id`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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

	/**
	 * Get Total Returns
	 *
	 * Get the total number of total return records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of return records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *   'filter_return_id'        => 1,
	 *   'filter_customer'         => 'John Doe',
	 *   'filter_order_id'         => 1,
	 *   'filter_product'          => 'Product Name',
	 *   'filter_model'            => 'Product Model',
	 *   'filter_return_status_id' => 1,
	 *   'filter_date_from'        => '2021-01-01',
	 *   'filter_date_to'          => '2021-01-31'
	 * ];
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $total_returns = $this->model_sale_returns->getTotalReturns($filter_data);
	 */
	public function getTotalReturns(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return` `r`";

		$implode = [];

		if (!empty($data['filter_return_id'])) {
			$implode[] = "`r`.`return_id` = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "LCASE(CONCAT(`r`.`firstname`, ' ', `r`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_customer']) . '%') . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "`r`.`order_id` = '" . $this->db->escape((string)$data['filter_order_id']) . "'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "`r`.`product` = '" . $this->db->escape((string)$data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "`r`.`model` = '" . $this->db->escape((string)$data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "`r`.`return_status_id` = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`r`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`r`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Returns By Return Status ID
	 *
	 * Get the total number of total returns by return status records in the database.
	 *
	 * @param int $return_status_id primary key of the return status record
	 *
	 * @return int total number of return records that have return status ID
	 *
	 * @example
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $return_total = $this->model_sale_returns->getTotalReturnsByReturnStatusId($return_status_id);
	 */
	public function getTotalReturnsByReturnStatusId(int $return_status_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return` WHERE `return_status_id` = '" . (int)$return_status_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Returns By Return Reason ID
	 *
	 * Get the total number of total returns by return reason records in the database.
	 *
	 * @param int $return_reason_id primary key of the return reason record
	 *
	 * @return int total number of layout records that have return reason ID
	 *
	 * @example
	 *
	 * $this->load->model('sale/subscription');
	 *
	 * $return_total = $this->model_sale_returns->getTotalReturnsByReturnReasonId($return_reason_id);
	 */
	public function getTotalReturnsByReturnReasonId(int $return_reason_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return` WHERE `return_reason_id` = '" . (int)$return_reason_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Returns By Return Action ID
	 *
	 * Get the total number of total returns by return action records in the database.
	 *
	 * @param int $return_action_id primary key of the return action record
	 *
	 * @return int total number of return records that have return action ID
	 *
	 * @example
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $return_total = $this->model_sale_returns->getTotalReturnsByReturnActionId($return_action_id);
	 */
	public function getTotalReturnsByReturnActionId(int $return_action_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return` WHERE `return_action_id` = '" . (int)$return_action_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add History
	 *
	 * Create a new return history record in the database.
	 *
	 * @param int    $return_id        primary key of the return record
	 * @param int    $return_status_id primary key of the return status record
	 * @param string $comment
	 * @param bool   $notify
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('sale/returns');
	 *
	 * $this->model_sale_returns->addHistory($return_id, $return_status_id, $comment, $notify);
	 */
	public function addHistory(int $return_id, int $return_status_id, string $comment = '', bool $notify = false): void {
		$this->editReturnStatusId($return_id, $return_status_id);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "return_history` SET `return_id` = '" . (int)$return_id . "', `return_status_id` = '" . (int)$return_status_id . "', `notify` = '" . (int)$notify . "', `comment` = '" . $this->db->escape(strip_tags($comment)) . "', `date_added` = NOW()");
	}

	/**
	 * Delete Return Histories
	 *
	 * Delete return history records in the database.
	 *
	 * @param int $return_id primary key of the return record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('sale/subscription');
	 *
	 * $this->model_sale_returns->deleteHistories($return_id);
	 */
	public function deleteHistories(int $return_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_history` WHERE `return_id` = '" . (int)$return_id . "'");
	}

	/**
	 * Get Histories
	 *
	 * Get the record of the return history records in the database.
	 *
	 * @param int $return_id primary key of the return record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> history records that have return ID
	 *
	 * @example
	 *
	 * $this->load->model('sale/subscription');
	 *
	 * $results = $this->model_sale_returns->getHistories($return_id, $start, $limit);
	 */
	public function getHistories(int $return_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT *, (SELECT `rs`.`name` FROM `" . DB_PREFIX . "return_status` `rs` WHERE `rs`.`return_status_id` = `rh`.`return_status_id` AND `rs`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `return_status` FROM `" . DB_PREFIX . "return_history` `rh` WHERE `rh`.`return_id` = '" . (int)$return_id . "' ORDER BY `rh`.`date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Histories
	 *
	 * Get the total number of total return history records in the database.
	 *
	 * @param int $return_id primary key of the return record
	 *
	 * @return int total number of history records that have return ID
	 *
	 * @example
	 *
	 * $this->load->model('sale/subscription');
	 *
	 * $history_total = $this->model_sale_returns->getTotalHistories($return_id);
	 */
	public function getTotalHistories(int $return_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return_history` WHERE `return_id` = '" . (int)$return_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Histories By Return Status ID
	 *
	 * Get the total number of total return histories by return status records in the database.
	 *
	 * @param int $return_status_id primary key of the return status record
	 *
	 * @return int total number of history records that have return status ID
	 *
	 * @example
	 *
	 * $this->load->model('sale/subscription');
	 *
	 * $return_total = $this->model_sale_returns->getTotalHistoriesByReturnStatusId($return_status_id);
	 */
	public function getTotalHistoriesByReturnStatusId(int $return_status_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return_history` WHERE `return_status_id` = '" . (int)$return_status_id . "'");

		return (int)$query->row['total'];
	}
}

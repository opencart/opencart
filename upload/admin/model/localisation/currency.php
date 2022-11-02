<?php
namespace Opencart\Admin\Model\Localisation;
class Currency extends \Opencart\System\Engine\Model {
	public function addCurrency(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "currency` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `symbol_left` = '" . $this->db->escape((string)$data['symbol_left']) . "', `symbol_right` = '" . $this->db->escape((string)$data['symbol_right']) . "', `decimal_place` = '" . (int)$data['decimal_place'] . "', `value` = '" . (float)$data['value'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `date_modified` = NOW()");

		$currency_id = $this->db->getLastId();

		$this->cache->delete('currency');

		return $currency_id;
	}

	public function editCurrency(int $currency_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `symbol_left` = '" . $this->db->escape((string)$data['symbol_left']) . "', `symbol_right` = '" . $this->db->escape((string)$data['symbol_right']) . "', `decimal_place` = '" . (int)$data['decimal_place'] . "', `value` = '" . (float)$data['value'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `date_modified` = NOW() WHERE `currency_id` = '" . (int)$currency_id . "'");

		$this->cache->delete('currency');
	}

	public function editValueByCode(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `value` = '" . (float)$value . "', `date_modified` = NOW() WHERE `code` = '" . $this->db->escape((string)$code) . "'");

		$this->cache->delete('currency');
	}

	public function deleteCurrency(int $currency_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");

		$this->cache->delete('currency');
	}

	public function getCurrency(int $currency_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");

		return $query->row;
	}

	public function getCurrencyByCode(string $currency): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "'");

		return $query->row;
	}

	public function getCurrencies(array $data = []): array {
		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "currency`";

			$sort_data = [
				'title',
				'code',
				'value',
				'date_modified'
			];

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY `" . $data['sort'] . "`";
			} else {
				$sql .= " ORDER BY `title`";
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
		} else {
			$currency_data = $this->cache->get('currency');

			if (!$currency_data) {
				$currency_data = [];

				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency` ORDER BY `title` ASC");

				foreach ($query->rows as $result) {
					$currency_data[$result['code']] = [
						'currency_id'   => $result['currency_id'],
						'title'         => $result['title'],
						'code'          => $result['code'],
						'symbol_left'   => $result['symbol_left'],
						'symbol_right'  => $result['symbol_right'],
						'decimal_place' => $result['decimal_place'],
						'value'         => $result['value'],
						'status'        => $result['status'],
						'date_modified' => $result['date_modified']
					];
				}

				$this->cache->set('currency', $currency_data);
			}

			return $currency_data;
		}
	}

	public function getTotalCurrencies(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "currency`");

		return (int)$query->row['total'];
	}
}

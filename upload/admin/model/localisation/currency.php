<?php
namespace Opencart\Application\Model\Localisation;
class Currency extends \Opencart\System\Engine\Model {
	public function addCurrency($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "currency` SET `code` = '" . $this->db->escape((string)$data['code']) . "', `symbol_left` = '" . $this->db->escape((string)$data['symbol_left']) . "', `symbol_right` = '" . $this->db->escape((string)$data['symbol_right']) . "', `decimal_place` = '" . $this->db->escape((string)$data['decimal_place']) . "', `value` = '" . (float)$data['value'] . "', `status` = '" . (int)$data['status'] . "', `date_modified` = NOW()");

		$currency_id = $this->db->getLastId();
		
		if (isset($data['currency_description'])) {
			foreach ($data['currency_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "currency_description` SET `currency_id` = '" . (int)$currency_id . "', `title` = '" . $this->db->escape($value['title']) . "', `language_id` = '" . (int)$language_id . "', `country_id` = '" . (int)$value['country_id'] . "', `push` = '" . (int)$value['push'] . "'");
			}
		}

		$this->cache->delete('currency.' . $this->config->get('config_language_id'));
		
		return $currency_id;
	}

	public function editCurrency($currency_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `code` = '" . $this->db->escape((string)$data['code']) . "', `symbol_left` = '" . $this->db->escape((string)$data['symbol_left']) . "', `symbol_right` = '" . $this->db->escape((string)$data['symbol_right']) . "', `decimal_place` = '" . $this->db->escape((string)$data['decimal_place']) . "', `value` = '" . (float)$data['value'] . "', `status` = '" . (int)$data['status'] . "', `date_modified` = NOW() WHERE `currency_id` = '" . (int)$currency_id . "'");		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "currency_description`");
		
		if (isset($data['currency_description'])) {
			foreach ($data['currency_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "currency_description` SET `currency_id` = '" . (int)$currency_id . "', `title` = '" . $this->db->escape($value['title']) . "', `language_id` = '" . (int)$language_id . "', `country_id` = '" . (int)$value['country_id'] . "', `push` = '" . (int)$value['push'] . "'");
			}
		}

		$this->cache->delete('currency.' . $this->config->get('config_language_id'));
	}

	public function editValueByCode($code, $value) {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `value` = '" . (float)$value . "', `date_modified` = NOW() WHERE `code` = '" . $this->db->escape((string)$code) . "'");

		$this->cache->delete('currency');
	}

	public function deleteCurrency($currency_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "currency_description` WHERE `currency_id` = '" . (int)$currency_id . "'");
		
		$this->cache->delete('currency.' . $this->config->get('config_language_id'));
	}

	public function getCurrency($currency_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");

		return $query->row;
	}
	
	public function getCurrencyByCode($currency) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "'");

		return $query->row;
	}

	public function getCurrencies($data = []) {
		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "currency` c LEFT JOIN `" . DB_PREFIX . "currency_description` cd ON (c.`currency_id` = cd.`currency_id`) WHERE cd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND cd.`country_id` = '" . (int)$this->config->get('config_country_id') . "'";

			$sort_data = [
				'title',
				'code',
				'value',
				'date_modified'
			];
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY cd.`title`";
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
			$currency_data = $this->cache->get('currency.' . $this->config->get('config_language_id'));

			if (!$currency_data) {
				$currency_data = [];

				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency` c LEFT JOIN `" . DB_PREFIX . "currency_description` cd ON (c.`currency_id` = cd.`currency_id`) WHERE cd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND cd.`country_id` = '" . (int)$this->config->get('config_country_id') . "' ORDER BY cd.`title` ASC");

				foreach ($query->rows as $result) {
					$currency_data[$result['code']] = [
						'currency_id'   => $result['currency_id'],
						'country_id'	=> $result['country_id'],						
						'title'         => $result['title'],
						'push'			=> $result['push'],
						'code'          => $result['code'],
						'symbol_left'   => $result['symbol_left'],
						'symbol_right'  => $result['symbol_right'],
						'decimal_place' => $result['decimal_place'],
						'value'         => $result['value'],
						'status'        => $result['status'],
						'date_modified' => $result['date_modified']
					];
				}

				$this->cache->set('currency.' . $this->config->get('config_language_id'), $currency_data);
			}

			return $currency_data;
		}
	}
	
	public function getDescriptions($currency_id) {
		$currency_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency_description` WHERE `currency_id` = '" . (int)$currency_id . "'");

		foreach ($query->rows as $result) {
			$currency_description_data[$result['language_id']] = [
				'title'             => $result['title'],
				'country_id'        => $result['country_id'],				
				'push'              => $result['push']
			];
		}

		return $currency_description_data;
	}

	public function getTotalCurrencies() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "currency`");

		return $query->row['total'];
	}
}

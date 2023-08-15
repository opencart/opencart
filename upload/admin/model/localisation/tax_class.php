<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class TaxClass
 *
 * @package Opencart\Admin\Model\Localisation
 */
class TaxClass extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addTaxClass(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_class` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "', `date_added` = NOW()");

		$tax_class_id = $this->db->getLastId();

		if (isset($data['tax_rule'])) {
			foreach ($data['tax_rule'] as $tax_rule) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_rule` SET `tax_class_id` = '" . (int)$tax_class_id . "', `tax_rate_id` = '" . (int)$tax_rule['tax_rate_id'] . "', `based` = '" . $this->db->escape($tax_rule['based']) . "', `priority` = '" . (int)$tax_rule['priority'] . "'");
			}
		}

		$this->cache->delete('tax_class');

		return $tax_class_id;
	}

	/**
	 * @param int   $tax_class_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editTaxClass(int $tax_class_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "tax_class` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "', `date_modified` = NOW() WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rule` WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");

		if (isset($data['tax_rule'])) {
			foreach ($data['tax_rule'] as $tax_rule) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_rule` SET `tax_class_id` = '" . (int)$tax_class_id . "', `tax_rate_id` = '" . (int)$tax_rule['tax_rate_id'] . "', `based` = '" . $this->db->escape($tax_rule['based']) . "', `priority` = '" . (int)$tax_rule['priority'] . "'");
			}
		}

		$this->cache->delete('tax_class');
	}

	/**
	 * @param int $tax_class_id
	 *
	 * @return void
	 */
	public function deleteTaxClass(int $tax_class_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_class` WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rule` WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");

		$this->cache->delete('tax_class');
	}

	/**
	 * @param int $tax_class_id
	 *
	 * @return array
	 */
	public function getTaxClass(int $tax_class_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tax_class` WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getTaxClasses(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "tax_class` ORDER BY `title`";

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

		$tax_class_data = $this->cache->get('tax_class.'. md5($sql));

		if (!$tax_class_data) {
			$query = $this->db->query($sql);

			$tax_class_data = $query->rows;

			$this->cache->set('tax_class.'. md5($sql), $tax_class_data);
		}

		return $tax_class_data;
	}

	/**
	 * @return int
	 */
	public function getTotalTaxClasses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "tax_class`");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $tax_class_id
	 *
	 * @return array
	 */
	public function getTaxRules(int $tax_class_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tax_rule` WHERE `tax_class_id` = '" . (int)$tax_class_id . "' ORDER BY `priority` ASC");

		return $query->rows;
	}

	/**
	 * @param int $tax_rate_id
	 *
	 * @return int
	 */
	public function getTotalTaxRulesByTaxRateId(int $tax_rate_id): int {
		$query = $this->db->query("SELECT COUNT(DISTINCT `tax_class_id`) AS `total` FROM `" . DB_PREFIX . "tax_rule` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		return (int)$query->row['total'];
	}
}

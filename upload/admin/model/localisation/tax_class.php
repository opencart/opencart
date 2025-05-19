<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Tax Class
 *
 * Can be loaded using $this->load->model('localisation/tax_class');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class TaxClass extends \Opencart\System\Engine\Model {
	/**
	 * Add Tax Class
	 *
	 * Create a new tax class record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new tax class record
	 *
	 * @example
	 *
	 * $tax_class_data = [
	 *     'title'       => 'Tax Class Title',
	 *     'description' => 'Tax Class Description'
	 * ];
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $tax_class_id = $this->model_localisation_tax_class->addTaxClass($tax_class_data);
	 */
	public function addTaxClass(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_class` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "'");

		$tax_class_id = $this->db->getLastId();

		if (isset($data['tax_rule'])) {
			foreach ($data['tax_rule'] as $tax_rule) {
				$this->addTaxRule($tax_class_id, $tax_rule);
			}
		}

		$this->cache->delete('tax_class');

		return $tax_class_id;
	}

	/**
	 * Edit Tax Class
	 *
	 * Edit tax class record in the database.
	 *
	 * @param int                  $tax_class_id primary key of the tax class record
	 * @param array<string, mixed> $data         array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $tax_class_data = [
	 *     'title'       => 'Tax Class Title',
	 *     'description' => 'Tax Class Description'
	 * ];
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $this->model_localisation_tax_class->editTaxClass($tax_class_id, $tax_class_data);
	 */
	public function editTaxClass(int $tax_class_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "tax_class` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "' WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");

		$this->deleteTaxRules($tax_class_id);

		if (isset($data['tax_rule'])) {
			foreach ($data['tax_rule'] as $tax_rule) {
				$this->addTaxRule($tax_class_id, $tax_rule);
			}
		}

		$this->cache->delete('tax_class');
	}

	/**
	 * Delete Tax Class
	 *
	 * Delete tax class record in the database.
	 *
	 * @param int $tax_class_id primary key of the tax class record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $this->model_localisation_tax_class->deleteTaxClass($tax_class_id);
	 */
	public function deleteTaxClass(int $tax_class_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_class` WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");

		$this->deleteTaxRules($tax_class_id);

		$this->cache->delete('tax_class');
	}

	/**
	 * Get Tax Class
	 *
	 * Get the record of the tax class record in the database.
	 *
	 * @param int $tax_class_id primary key of the tax class record
	 *
	 * @return array<string, mixed> tax class record that has tax class ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $tax_class_info = $this->model_localisation_tax_class->getTaxClass($tax_class_id);
	 */
	public function getTaxClass(int $tax_class_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tax_class` WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");

		return $query->row;
	}

	/**
	 * Get Tax Classes
	 *
	 * Get the record of the tax class records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> tax class records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'title',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $tax_classes = $this->model_localisation_tax_class->getTaxClasses($filter_data);
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

		$key = md5($sql);

		$tax_class_data = $this->cache->get('tax_class.' . $key);

		if (!$tax_class_data) {
			$query = $this->db->query($sql);

			$tax_class_data = $query->rows;

			$this->cache->set('tax_class.' . $key, $tax_class_data);
		}

		return $tax_class_data;
	}

	/**
	 * Get Total Tax Classes
	 *
	 * Get the total number of tax class records in the database.
	 *
	 * @return int total number of tax class records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $tax_class_total = $this->model_localisation_tax_class->getTotalTaxClasses();
	 */
	public function getTotalTaxClasses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "tax_class`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Tax Rule
	 *
	 * Create a new tax rule record in the database.
	 *
	 * @param int                  $tax_class_id primary key of the tax class record
	 * @param array<string, mixed> $data         array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $tax_class_data['tax_rule'] = [
	 *     'tax_rate_id' => 1,
	 *     'based'       => '',
	 *     'priority'    => 0
	 * ];
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $this->model_localisation_tax_class->addTaxRule($tax_class_id, $tax_class_data);
	 */
	public function addTaxRule(int $tax_class_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_rule` SET `tax_class_id` = '" . (int)$tax_class_id . "', `tax_rate_id` = '" . (int)$data['tax_rate_id'] . "', `based` = '" . $this->db->escape($data['based']) . "', `priority` = '" . (int)$data['priority'] . "'");
	}

	/**
	 * Delete Tax Rules
	 *
	 * Delete tax rule records in the database.
	 *
	 * @param int $tax_class_id primary key of the tax class record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $this->model_localisation_tax_class->deleteTaxRules($tax_class_id);
	 */
	public function deleteTaxRules(int $tax_class_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rule` WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");
	}

	/**
	 * Get Tax Rules
	 *
	 * Get the record of the tax rule records in the database.
	 *
	 * @param int $tax_class_id primary key of the tax class record
	 *
	 * @return array<int, array<string, mixed>> tax rule records that have tax class ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $tax_rules = $this->model_localisation_tax_class->getTaxRules($tax_class_id);
	 */
	public function getTaxRules(int $tax_class_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tax_rule` WHERE `tax_class_id` = '" . (int)$tax_class_id . "' ORDER BY `priority` ASC");

		return $query->rows;
	}

	/**
	 * Get Total Tax Rules By Tax Rate ID
	 *
	 * Get the total number of tax rule records in the database.
	 *
	 * @param int $tax_rate_id primary key of the tax rate record
	 *
	 * @return int total number of tax rule records that have tax rate ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_class');
	 *
	 * $tax_rule_total = $this->model_localisation_tax_class->getTotalTaxRulesByTaxRateId($tax_rate_id);
	 */
	public function getTotalTaxRulesByTaxRateId(int $tax_rate_id): int {
		$query = $this->db->query("SELECT COUNT(DISTINCT `tax_class_id`) AS `total` FROM `" . DB_PREFIX . "tax_rule` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		return (int)$query->row['total'];
	}
}

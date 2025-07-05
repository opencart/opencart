<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Identifier
 *
 * Can be loaded using $this->load->model('localisation/identifier');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Identifier extends \Opencart\System\Engine\Model {
	/**
	 * Add Identifier
	 *
	 * Create a new identifier record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new identifier record
	 *
	 * @example
	 *
	 * $identifier_data = [
	 *     'author'     => 'Author Name',
	 *     'product_id' => 1,
	 *     'text'       => 'identifier Text',
	 *     'rating'     => 4,
	 *     'status'     => 0,
	 * ];
	 *
	 * $this->load->model('localisation/identifier');
	 *
	 * $identifier_id = $this->model_localisation_identifier->addIdentifier($identifier_data);
	 */
	public function addIdentifier(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "identifier` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$identifier_id = $this->db->getLastId();

		$this->cache->delete('identifier');

		return $identifier_id;
	}

	/**
	 * Edit Identifier
	 *
	 * Edit identifier record in the database.
	 *
	 * @param int                  $identifier_id primary key of the identifier record
	 * @param array<string, mixed> $data          array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $identifier_data = [
	 *     'author'     => 'Author Name',
	 *     'product_id' => 1,
	 *     'text'       => 'Identifier Text',
	 *     'rating'     => 4,
	 *     'status'     => 1,
	 * ];
	 *
	 * $this->load->model('localisation/identifier');
	 *
	 * $this->model_localisation_identifier->editIdentifier($identifier_id, $identifier_data);
	 */
	public function editIdentifier(int $identifier_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "identifier` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `identifier_id` = '" . (int)$identifier_id . "'");

		$this->cache->delete('identifier');
	}

	/**
	 * Delete Identifier
	 *
	 * Delete identifier record in the database.
	 *
	 * @param int $identifier_id primary key of the identifier record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/identifier');
	 *
	 * $this->model_localisation_identifier->deleteIdentifier($identifier_id);
	 */
	public function deleteIdentifier(int $identifier_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "identifier` WHERE `identifier_id` = '" . (int)$identifier_id . "'");

		$this->cache->delete('identifier');
	}

	/**
	 * Get Identifier
	 *
	 * Get the record of the identifier record in the database.
	 *
	 * @param int $identifier_id primary key of the identifier record
	 *
	 * @return array<string, mixed> identifier record that has identifier ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/identifier');
	 *
	 * $identifier_info = $this->model_localisation_identifier->getIdentifier($identifier_id);
	 */
	public function getIdentifier(int $identifier_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "identifier` WHERE `identifier_id` = '" . (int)$identifier_id . "'");

		return $query->row;
	}

	/**
	 * Get Identifier By Code
	 *
	 * Get the record of the identifier record in the database.
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getIdentifierByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "identifier` WHERE `code` = '" . $this->db->escape((string)$code) . "'");

		return $query->row;
	}

	/**
	 * Get Identifiers
	 *
	 * Get the record of the identifier records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> identifier records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/identifier');
	 *
	 * $results = $this->model_localisation_identifier->getIdentifiers($filter_data);
	 */
	public function getIdentifiers(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "identifier` ORDER BY `name` ASC";

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
	 * Get Total Identifiers
	 *
	 * Get the total number of identifier records in the database.
	 *
	 * @return int total number of identifier records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/identifier');
	 *
	 * $identifier_total = $this->model_localisation_identifier->getTotalIdentifiers();
	 */
	public function getTotalIdentifiers(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "identifier`");

		return (int)$query->row['total'];
	}
}

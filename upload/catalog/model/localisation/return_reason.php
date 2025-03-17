<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Return Reason
 *
 * Can be called using $this->load->model('localisation/return_reason');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class ReturnReason extends \Opencart\System\Engine\Model {
	/**
	 * Get Return Reasons
	 *
	 * Get the record of the return reason records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> return reason records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $return_reasons = $this->model_localisation_return_reason->getReturnReasons();
	 */
	public function getReturnReasons(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

		if (isset($data['return']) && ($data['return'] == 'DESC')) {
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

		$return_reason_data = $this->cache->get('return_reason.' . $key);

		if (!$return_reason_data) {
			$query = $this->db->query($sql);

			$return_reason_data = $query->rows;

			$this->cache->set('return_reason.' . $key, $return_reason_data);
		}

		return $return_reason_data;
	}
}

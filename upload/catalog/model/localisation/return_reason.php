<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class ReturnReason
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class ReturnReason extends \Opencart\System\Engine\Model {
	/**
	 * Get Return Reasons
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
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

<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Address Format
 *
 * @example $address_format_model = $this->model_localisation_address_format;
 *
 * Can be called using $this->load->model('localisation/address_format');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class AddressFormat extends \Opencart\System\Engine\Model {
	/**
	 * Get Address Format
	 *
	 * @param int $address_format_id primary key of the address format record
	 *
	 * @return array<string, mixed> address format record that has address format ID
	 */
	public function getAddressFormat(int $address_format_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "address_format` WHERE `address_format_id` = '" . (int)$address_format_id . "'");

		return $query->row;
	}
}

<?php
class ModelExtensionCurrencyEcb extends Model {

	public function editValueByCode($code, $value) {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `value` = '" . (float)$value . "', `date_modified` = NOW() WHERE `code` = '" . $this->db->escape((string)$code) . "'");
		$this->cache->delete('currency');
	}
}

<?php
class ModelCheckoutExtension extends Model {
	function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM extension WHERE `type` = '" . $this->db->escape($type) . "'");

		return $query->rows;
	}
}
?>
<?php
class ModelLocalisationCurrency extends Model {
	public function getCurrencies() {
		$query = $this->db->query("SELECT * FROM currency WHERE status = '1' ORDER BY title");
		
		return $query->rows;
	}
}
?>
<?php
class ModelUpgrade1007 extends Model {
	public function upgrade() {
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "download` CHANGE `filename` `filename` varchar(140) NOT NULL");
	}
}
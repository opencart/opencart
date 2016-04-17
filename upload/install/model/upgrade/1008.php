<?php
class ModelUpgrade1008 extends Model {
	public function upgrade() {
		//  Option
		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = 'radio' WHERE `type` = 'image'");
	}
}

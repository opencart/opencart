<?php
class ControllerUpgrade2100 extends Controller {
	public function index() {
		
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = 'oc_affiliate_activity'");
		
		print_r($query->rows);
		
		//$this->db->query("SELECT * FROM `" . DB_PREFIX . "oc_affiliate_activity` WHERE `parent_id` = '" . (int)$parent_id . "'");
		//$this->db->query("SELECT * FROM `" . DB_PREFIX . "oc_customer_activity` WHERE `parent_id` = '" . (int)$parent_id . "'");
	
	
	}
}
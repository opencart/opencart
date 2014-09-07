<?php
class ControllerCommonStats extends Controller {
	public function index() {
		$query = $this->db->query("SHOW GLOBAL STATUS");
		
		//print_r($query);
	}
}
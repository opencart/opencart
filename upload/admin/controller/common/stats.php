<?php
class ControllerCommonStats extends Controller {
	public function index() {
		//$query = $this->db->query("SHOW STATUS");
		
		//print_r($query);
		//$query = $this->db->query("SELECT node_id, memory_type, total FROM ndbinfo.memoryusage;");
		
		//print_r($query);

//SELECT @DPAGE * SUM(used) as 'Total DataMemory Used',
//SELECT @DPAGE * SUM(max) as 'Total DataMemory Available',
	
			
		//echo $this->request->server['REQUEST_TIME'] . ' ' . time();
		
		//print_r(phpinfo());
		
		$data = array();
		
		return $this->load->view('common/stats.tpl', $data);
	}
}
<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
		if (isset($this->request->get['_route_'])) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE alias = '" . $this->db->escape($this->request->get['_route_']) . "'");
		
			if ($query->num_rows) {
				$data = array();
				
				unset($this->request->get['_route_']);
				
				parse_str($query->row['query'], $data);
				
				$this->request->get = array_merge($this->request->get, $data);
				
				return $this->forward($this->request->get['route']);
			}
		}
	}
}
?>
<?php
class ControllerStartupSession extends Controller {
	public function index() {
		if (isset($this->request->get['route']) && (substr($this->request->get['route'], 0, 4) == 'api/') && isset($this->request->get['api_username']) && isset($this->request->get['api_password'])) {
			$api_query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "api` `a` LEFT JOIN `" . DB_PREFIX . "api_ip` `ai` ON (a.api_id = ai.api_id) WHERE a.status = '1' AND a.api_username = '" . $this->db->escape($this->request->get['api_username']) . "' AND a.api_key = '" . $this->db->escape($this->request->get['api_key']) . "' AND ai.ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
		
			if (!$api_query->num_rows) {
				$this->session->start('api');
				
				$api_session_query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "api_session` `a` LEFT JOIN `" . DB_PREFIX . "api_ip` `ai` ON (a.api_id = ai.api_id) WHERE a.status = '1' AND a.api_id = '" . (int)$api_query->row['api_id'] . "' AND ai.ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
				
				if (!$api_session_query->num_rows) {
					// keep the session alive
					$this->db->query("INSERT `" . DB_PREFIX . "api_session` SET `api_id` = '" . (int)$api_query->row['api_id'] . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW(), date_modified = NOW()");
				} else {
					// keep the session alive
					$this->db->query("UPDATE `" . DB_PREFIX . "api_session` SET date_modified = NOW() WHERE api_session_id = '" . (int)$query->row['api_session_id'] . "'");
				}
			}
		} else {
			$this->session->start();
		}
	}
}
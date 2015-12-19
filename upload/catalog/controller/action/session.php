<?php
class ControllerEventSession extends Controller {
	public function index() {
		
		if (isset($request->get['token']) && isset($request->get['route']) && substr($request->get['route'], 0, 4) == 'api/') {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "api_session` WHERE TIMESTAMPADD(HOUR, 1, date_modified) < NOW()");
		
			$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "api` `a` LEFT JOIN `" . DB_PREFIX . "api_session` `as` ON (a.api_id = as.api_id) LEFT JOIN " . DB_PREFIX . "api_ip `ai` ON (as.api_id = ai.api_id) WHERE a.status = '1' AND as.token = '" . $db->escape($request->get['token']) . "' AND ai.ip = '" . $db->escape($request->server['REMOTE_ADDR']) . "'");
		
			if ($this->query->num_rows) {
				$this->session->start($query->row['session_id'], $query->row['session_name']);
				
				$registry->set('session', $session);
		
				// keep the session alive
				$db->query("UPDATE `" . DB_PREFIX . "api_session` SET date_modified = NOW() WHERE api_session_id = '" . (int)$query->row['api_session_id'] . "'");
			}
		} else {
			  $this->session->start();
		}
	}
}
<?php
class ControllerStartupSession extends Controller {
	public function index() {

		//print_r($this->request->post);
		//print_r($this->request->cookie);

		if (isset($this->request->get['route']) && substr((string)$this->request->get['route'], 0, 4) == 'api/') {
			$this->load->model('setting/api');

			$this->model_setting_api->cleanApiSessions();

			$query = $this->db->query("SELECT NOW()");
			//$query = $this->db->query("SELECT @@system_time_zone");
			//$query = $this->db->query("SELECT @@session.time_zone");

			//$query->row;

			//echo date('P');

			// Make sure the IP is allowed
			$api_info = $this->model_setting_api->getApiByToken($this->request->get['api_token']);

			if ($api_info) {
				$this->session->start($this->request->get['api_token']);

				$this->model_setting_api->updateApiSession($api_info['api_session_id']);
			}
		} else {
			if (isset($this->request->cookie[$this->config->get('session_name')])) {
				$session_id = $this->request->cookie[$this->config->get('session_name')];
			} else {
				$session_id = '';
			}
			
			$this->session->start($session_id);
			
			setcookie($this->config->get('session_name'), $this->session->getId(), ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));	
		}
	}
}
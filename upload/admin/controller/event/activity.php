<?php
class ControllerEventActivity extends Controller {
	// admin/controller/common/logout/before
	public function logout(&$route, &$args) {
		$this->load->model('user/api');

		$session = new Session($this->config->get('session_engine'), $this->registry);
				
		$session->start();
						
		$this->model_user_api->deleteApiSessionBySessonId($session->getId());
	}
	
	// admin/controller/common/header/after
	public function deleteApiSessions(&$route, &$args, &$output) {
		$this->load->model('user/api');

		$this->model_user_api->deleteApiSessions();
	}
}

<?php
class ControllerCommonLogout extends Controller {
	public function index() {
		$this->user->logout();

		unset($this->session->data['user_token']);

		// Cleanup session
		$this->session->destroy();
		$this->session->forget();

		$this->response->redirect($this->url->link('common/login'));
	}
}
<?php
class ControllerCommonLogout extends \Engine\Controller {
	public function index() {
		$this->user->logout();

		unset($this->session->data['token']);

		$this->response->redirect($this->url->link('common/login', '', 'SSL'));
	}
}
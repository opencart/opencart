<?php
namespace Opencart\Application\Controller\Common;
class Logout extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->user->logout();

		unset($this->session->data['user_token']);

		$this->response->redirect($this->url->link('common/login'));
	}
}
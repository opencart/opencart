<?php
class ControllerStartupMaintenance extends Controller {
	public function index() {
		if ($this->config->get('config_maintenance')) {
			$route = '';

			if (isset($this->request->get['route'])) {
				$part = explode('/', $this->request->get['route']);

				if (isset($part[0])) {
					$route .= $part[0];
				}
			}

			// Show site if logged in as admin
			$this->user = new Cart\User($this->registry);

			if (($route != 'payment' && $route != 'api') && !$this->user->isLogged()) {
				return new Action('common/maintenance');
			}
		}
	}
}

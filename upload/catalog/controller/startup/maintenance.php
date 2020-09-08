<?php
namespace Opencart\Application\Controller\Startup;
class Maintenance extends \Opencart\System\Engine\Controller {
	public function index() {
		if ($this->config->get('config_maintenance')) {
			// Route
			if (isset($this->request->get['route'])) {
				$route = $this->request->get['route'];
			} else {
				$route = $this->config->get('action_default');
			}

			$ignore = [
				'common/language/language',
				'common/currency/currency'
			];

			// Show site if logged in as admin
			$this->user = new \Opencart\System\Library\Cart\User($this->registry);

			if ((substr($route, 0, 17) != 'extension/payment' && substr($route, 0, 3) != 'api') && !in_array($route, $ignore) && !$this->user->isLogged()) {
				return new \Opencart\System\Engine\Action('common/maintenance');
			}
		}
	}
}

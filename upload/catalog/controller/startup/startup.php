<?php
namespace Opencart\Application\Controller\Startup;
class Startup extends \Opencart\System\Engine\Controller {
	public function index() {
		// Load start up actions
		$this->load->model('setting/startup');

		$results = $this->model_setting_startup->getStartups();

		foreach ($results as $result) {
			if ($result['status']) {
				$this->controller->load($result['action']);
			}
		}
	}
}
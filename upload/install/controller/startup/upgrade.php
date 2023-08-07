<?php
namespace Opencart\Install\Controller\Startup;
/**
 * Class Upgrade
 *
 * @package Opencart\Install\Controller\Startup
 */
class Upgrade extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$upgrade = false;

		if (is_file(DIR_OPENCART . 'config.php') && filesize(DIR_OPENCART . 'config.php') > 0) {
			$upgrade = true;
		}

		if (isset($this->request->get['route']) && ((substr($this->request->get['route'], 0, 8) == 'upgrade/') || (substr($this->request->get['route'], 0, 14) == 'install/step_4'))) {
			$upgrade = false;
		}

		if ($upgrade) {
			$this->response->redirect($this->url->link('upgrade/upgrade'));
		}
	}
}
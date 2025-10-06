<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Extension
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Extension extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		// Add extension paths from the DB
		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getInstalls();

		foreach ($results as $result) {
			$extension = str_replace(['_', '/'], ['', '\\'], ucwords($result['code'], '_/'));

			// Register controllers, models and system extension folders
			$this->autoloader->register('Opencart\Catalog\Controller\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/catalog/controller/');
			$this->autoloader->register('Opencart\Catalog\Model\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/catalog/model/');
			$this->autoloader->register('Opencart\System\Library\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/system/library/');

			// Template directory
			$this->template->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/catalog/view/template/');

			// Language directory
			$this->language->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/catalog/language/');

			// Config directory
			$this->config->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/system/config/');
		}
	}
}

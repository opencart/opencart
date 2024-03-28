<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Extension
 *
 * @package Opencart\Admin\Controller\Startup
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
			$this->autoloader->register('Opencart\Admin\Controller\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/admin/controller/');
			$this->autoloader->register('Opencart\Admin\Model\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/admin/model/');
			$this->autoloader->register('Opencart\System\Library\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/system/library/');

			// Template directory
			$this->template->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/admin/view/template/');

			// Language directory
			$this->language->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/admin/language/');

			// Config directory
			$this->config->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/system/config/');
		}

		// Register OCMOD
		$this->autoloader->register('Opencart\Admin\Controller\Extension\Ocmod', DIR_EXTENSION . 'ocmod/admin/controller/');
		$this->autoloader->register('Opencart\Admin\Model\Extension\Ocmod', DIR_EXTENSION . 'ocmod/admin/model/');
		$this->autoloader->register('Opencart\System\Library\Extension\Ocmod', DIR_EXTENSION . 'ocmod/system/library/');

		$this->template->addPath('extension/ocmod', DIR_EXTENSION . 'ocmod/admin/view/template/');
		$this->language->addPath('extension/ocmod', DIR_EXTENSION . 'ocmod/admin/language/');
		$this->config->addPath('extension/ocmod', DIR_EXTENSION . 'ocmod/system/config/');

		foreach ($results as $result) {
			$extension = str_replace(['_', '/'], ['', '\\'], ucwords($result['code'], '_/'));

			// Register OCMOD extension folders
			$this->autoloader->register('Opencart\Admin\Controller\Extension\Ocmod\Extension\\' . $extension, DIR_EXTENSION . 'ocmod/extension/' . $result['code'] . '/admin/controller/');
			$this->autoloader->register('Opencart\Admin\Model\Extension\Ocmod\Extension\\' . $extension, DIR_EXTENSION . 'ocmod/extension/' . $result['code'] . '/admin/model/');
			$this->autoloader->register('Opencart\System\Library\Extension\Ocmod\Extension\\' . $extension, DIR_EXTENSION . 'ocmod/extension/' . $result['code'] . '/system/library/');

			// Template directory
			$this->template->addPath('extension/ocmod/extension/' . $result['code'], DIR_EXTENSION . 'ocmod/extension/' . $result['code'] . '/admin/view/template/');

			// Language directory
			$this->language->addPath('extension/ocmod/extension/' . $result['code'], DIR_EXTENSION . 'ocmod/extension/' . $result['code'] . '/admin/language/');

			// Config directory
			$this->config->addPath('extension/ocmod/extension/' . $result['code'], DIR_EXTENSION . 'ocmod/extension/' . $result['code'] . '/system/config/');
		}
	}
}

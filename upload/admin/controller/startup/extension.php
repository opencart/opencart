<?php
namespace Opencart\Admin\Controller\Startup;
class Extension extends \Opencart\System\Engine\Controller {
	public function index(): void {
		// Add extension paths from the DB
		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getInstalls();

		foreach ($results as $result) {
			$extension = str_replace(['_', '/'], ['', '\\'], ucwords($result['codename'], '_/'));

			// Register controllers, models and system extension folders
			$this->autoloader->register('Opencart\Admin\Controller\Extension\\' . $extension, DIR_EXTENSION . $result['codename'] . '/admin/controller/');
			$this->autoloader->register('Opencart\Admin\Model\Extension\\' . $extension, DIR_EXTENSION . $result['codename'] . '/admin/model/');
			$this->autoloader->register('Opencart\System\Extension\\' . $extension, DIR_EXTENSION . $result['codename'] . '/system/');


			// Template directory
			$this->template->addPath('extension/' . $result['codename'], DIR_EXTENSION . $result['codename'] . '/admin/view/template/');

			// Language directory
			$this->language->addPath('extension/' . $result['codename'], DIR_EXTENSION . $result['codename'] . '/admin/language/');

			// Config directory
			$this->config->addPath('extension/' . $result['codename'], DIR_EXTENSION . $result['codename'] . '/system/config/');
		}
	}
}
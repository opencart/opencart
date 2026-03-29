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

		$results = $this->model_setting_extension->getExtensions();

		foreach ($results as $result) {
			$extension = str_replace(['_', '/'], ['', '\\'], ucwords($result['extension'], '_/'));

			// Register controllers, models and system extension folders
			$this->autoloader->register('Opencart\Catalog\Controller\Extension\\' . $extension, DIR_EXTENSION . $result['extension'] . '/catalog/controller/');
			$this->autoloader->register('Opencart\Catalog\Model\Extension\\' . $extension, DIR_EXTENSION . $result['extension'] . '/catalog/model/');
			$this->autoloader->register('Opencart\System\Library\Extension\\' . $extension, DIR_EXTENSION . $result['extension'] . '/system/library/');

			// Template directory
			$this->template->addPath('extension/' . $result['extension'], DIR_EXTENSION . $result['extension'] . '/catalog/view/template/');

			// Language directory
			$this->language->addPath('extension/' . $result['extension'], DIR_EXTENSION . $result['extension'] . '/catalog/language/');

			// Config directory
			$this->config->addPath('extension/' . $result['extension'], DIR_EXTENSION . $result['extension'] . '/system/config/');
		}

		// Register OCMOD
		$this->autoloader->register('Opencart\Catalog\Controller\Extension\Ocmod', DIR_EXTENSION . 'ocmod/catalog/controller/');
		$this->autoloader->register('Opencart\Catalog\Model\Extension\Ocmod', DIR_EXTENSION . 'ocmod/catalog/model/');
		$this->autoloader->register('Opencart\System\Library\Extension\Ocmod', DIR_EXTENSION . 'ocmod/system/library/');
		$this->template->addPath('extension/ocmod', DIR_EXTENSION . 'ocmod/catalog/view/template/');
		$this->language->addPath('extension/ocmod', DIR_EXTENSION . 'ocmod/catalog/language/');
		$this->config->addPath('extension/ocmod', DIR_EXTENSION . 'ocmod/system/config/');
	}
}

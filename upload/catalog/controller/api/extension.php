<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Extension
 *
 * @package Opencart\Catalog\Controller\Api
 */
class Extension extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			if ($extension['code'] != $this->request->get['route']) {
				$this->load->controller('extension/' . $extension['extension'] . '/total/' . $extension['code'] . '.api');
			}
		}
	}
}
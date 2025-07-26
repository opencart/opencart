<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Task\System
 */
class Mail extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 *
	 */
	public function index(): void {
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('currency', $this->config->get('config_currency_engine'));

		if ($extension_info) {
			$this->load->controller('extension/' . $extension_info['extension'] . '/currency/' . $extension_info['code'] . '.currency', $this->config->get('config_currency'));
		}

		$task_data = [
			'code'   => 'mail',
			'action' => 'catalog/cli/data/currency',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

	}
}

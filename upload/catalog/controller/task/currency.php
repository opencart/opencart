<?php
namespace Opencart\Catalog\Controller\task;
/**
 * Class Currency
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param int    $cron_id
	 * @param string $code
	 * @param string $cycle
	 * @param string $date_added
	 * @param string $date_modified
	 *
	 * @return void
	 */
	public function index(array $args = []): void {
		//$this->config->get('config_auto_update')

		$task_data = [
			'code'   => 'currency',
			'action' => 'catalog/currency.update',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('currency', $this->config->get('config_currency_engine'));

		if ($extension_info) {
			$this->load->controller('extension/' . $extension_info['extension'] . '/currency/' . $extension_info['code'] . '.currency', $this->config->get('config_currency'));
		}
	}
}

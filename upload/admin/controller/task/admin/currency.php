<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Task
 */
class Currency extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('task/admin/currency');

		//config_currency_auto

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/currency');

		$currencies = $this->model_localisation_currency->getCurrencies();

		foreach ($languages as $language) {
			$base = DIR_APPLICATION . 'view/data/';
			$directory = $language['code'] . '/localisation/';
			$filename = 'currency.json';

			if (!oc_directory_create($base . $directory, 0777)) {
				return sprintf($this->language->get('error_directory'), $directory);
			}

			$file = $base . $directory . $filename;

			if (!file_put_contents($file, json_encode($currencies))) {
				return sprintf($this->language->get('error_file'), $directory . $filename);
			}
		}

		return $this->language->get('text_success');
	}

	public function update() {
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('currency', $this->config->get('config_currency_engine'));

		if ($extension_info) {
			$this->load->controller('extension/' . $extension_info['extension'] . '/currency/' . $extension_info['code'] . '.currency', $currency);

			// Add a task for generating the country info data
			$task_data = [
				'code'   => 'currency',
				'action' => 'admin/currency'
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}

	public function clear(): void {
		$this->load->language('task/admin/currency');

		$json = [];

		if (!$this->user->hasPermission('modify', 'task/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/localisation/currency.json';

				if (is_file($file)) {
					unlink($file);
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

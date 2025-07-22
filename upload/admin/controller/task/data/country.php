<?php
namespace Opencart\Admin\Controller\Task\Data;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Task\Data
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *`12
	 * Generates the country list JSON files by language.
	 *
	 * @return void
	 */
	public function index($args = []): string {
		$this->load->language('task/data/country');

		// Get all languages so we don't need to keep querying the DB
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries();

		foreach ($languages as $language) {
			$country_data = [];

			foreach ($countries as $country) {
				$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language['language_id']);

				if ($description_info) {
					$country_data[$country['country_id']] = $description_info + $country;
				}
			}

			$sort_order = [];

			foreach ($country_data as $key => $value) {
				$sort_order[$key] = $value['name'];
			}

			array_multisort($sort_order, SORT_ASC, $country_data);

			$base = DIR_APPLICATION . 'view/data/';
			$directory = $language['code'] . '/localisation/';
			$filename = 'country.json';

			if (!oc_directory_create($base . $directory, 0777)) {
				$json['error'] = sprintf($this->language->get('error_directory'), $directory);

				break;
			}

			if (!file_put_contents($base . $directory . $filename, json_encode($country_data))) {
				$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

				break;
			}
		}

		$task_data = [
			'code'   => 'code',
			'action' => 'data/country.info',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);



		//$output = shell_exec('php ' . DIR_APPLICATION . 'index.php admin/zone --page 1');

		//echo $output;

		return 'SUCCESS';
	}

	public function clear(): void {
		$this->load->language('ssr/admin/language');

		$json = [];

		//if (!$this->user->hasPermission('modify', 'ssr/admin/language')) {
		//	$json['error'] = $this->language->get('error_permission');
		//}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				$base = DIR_APPLICATION . 'view/data/';
				$directory = $language['code'] . '/localisation/';

				$file = $base . $directory . 'country.json';

				if (is_file($file)) {
					unlink($file);
				}

				$files = oc_directory_read($base . $directory, false, '/country\-.+\.json$/');

				foreach ($files as $file) {
					unlink($file);
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

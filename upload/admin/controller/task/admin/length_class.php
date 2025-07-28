<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Length Class
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class LengthClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the country list JSON files by language.
	 *
	 * @return void
	 */
	public function index(): mixed {
		$this->load->language('task/length_class');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/length_class');

		$length_classes = $this->model_localisation_length_class->getLengthClasses();

		foreach ($languages as $language) {
			$customer_group_data = [];

			foreach ($length_classes as $customer_group) {
				$description_info = $this->model_localisation_length_class->getDescription($customer_group['customer_group_id'], $language['language_id']);

				if ($description_info) {
					$customer_group_data[$customer_group['customer_group_id']] = $description_info + $customer_group;
				}
			}

			$base = DIR_APPLICATION . 'view/data/';
			$directory = $language['code'] . '/customer/';
			$filename = 'customer_group.json';

			if (!oc_directory_create($base . $directory, 0777)) {
				return sprintf($this->language->get('error_directory'), $directory);
			}

			$file = $base . $directory . $filename;

			if (!file_put_contents($file, json_encode($customer_group_data))) {
				return sprintf($this->language->get('error_file'), $directory . $filename);
			}
		}

		return $this->language->get('text_success');
	}

	public function clear(): void {
		$this->load->language('task/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'task/custom_field')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Custom Field
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class CustomField extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates customer field task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/custom_field');

		$this->load->model('setting/task');

		$this->load->model('customer/customer_group');

		$customer_groups = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			foreach ($customer_groups as $customer_group) {
				$task_data = [
					'code'   => 'custom_field',
					'action' => 'task/admin/custom_field.list',
					'args'   => [
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates the custom field list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/custom_field');

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$custom_field_data = [];

		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields();

		foreach ($custom_fields as $custom_field) {


			$description_info = $this->model_customer_custom_field->getDescription($custom_field['custom_field_id'], $language_info['language_id']);

			if ($description_info) {



				$custom_field_data[$custom_field['custom_field_id']] = $description_info + $custom_field;


			}



		}

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/customer/';
		$filename = 'custom_field.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($custom_field_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}

	public function clear(array $args = []): array {
		$this->load->language('task/admin/custom_field');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$files = oc_directory_read(DIR_APPLICATION . 'view/data/' . $language['code'] . '/customer/', false, '/custom_field\-.+\.json$/');

			foreach ($files as $file) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}

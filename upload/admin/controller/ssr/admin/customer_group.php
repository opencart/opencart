<?php
namespace Opencart\Admin\Controller\Ssr\Admin;
/**
 * Class Customer Group
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class CustomerGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index() {
		$this->load->language('ssr/admin/customer_group');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/admin/customer_group')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$this->load->model('customer/customer_group');

			$customer_groups = $this->model_customer_customer_group->getCustomerGroups();

			foreach ($languages as $language) {
				$customer_group_data = [];

				foreach ($customer_groups as $customer_group) {
					$description_info = $this->model_customer_customer_group->getDescription($customer_group['customer_group_id'], $language['language_id']);

					if ($description_info) {
						$customer_group_data[$customer_group['customer_group_id']] = $description_info + $customer_group;
					}
				}

				$base = DIR_APPLICATION . 'view/data/';
				$directory = $language['code'] . '/customer/';
				$filename = 'customer_group.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					$json['error'] = sprintf($this->language->get('error_directory'), $directory);

					break;
				}

				$file = $base . $directory . $filename;

				if (!file_put_contents($file, json_encode($customer_group_data))) {
					$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

					break;
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('ssr/admin/customer_group');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/admin/customer_group')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/customer/customer_group.json';

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
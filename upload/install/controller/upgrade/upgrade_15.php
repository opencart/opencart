<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade15
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade15 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// Upgrade
			$this->load->model('upgrade/upgrade');

			$languages = $this->model_upgrade_upgrade->getRecords('language');

			if ($this->model_upgrade_upgrade->hasField('manufacturer', 'name')) {
				$manufacturers = $this->model_upgrade_upgrade->getRecords('manufacturer');

				foreach ($manufacturers as $manufacturer) {
					foreach ($languages as $language) {
						$manufacturer_description_data = [
							'manufacturer_id' => $manufacturer['manufacturer_id'],
							'language_id'     => $language['language_id'],
							'name'            => $manufacturer['name']
						];

						$this->model_upgrade_upgrade->addRecord('manufacturer_description', $manufacturer_description_data);
					}
				}
			}

			// manufacturer
			$remove[] = [
				'table' => 'manufacturer',
				'field' => 'name'
			];

			foreach ($remove as $result) {
				$this->model_upgrade_upgrade->dropField($result['table'], $result['field']);
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['redirect'] = $this->url->link('install/step_4', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

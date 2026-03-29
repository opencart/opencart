<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade11
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade11 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			$this->load->model('upgrade/upgrade');

			$languages = $this->model_upgrade_upgrade->getRecords('language');

			if ($this->model_upgrade_upgrade->hasField('country', 'name')) {
				$countries = $this->model_upgrade_upgrade->getRecords('country');

				foreach ($countries as $country) {
					foreach ($languages as $language) {
						$country_description_data = [
							'country_id'  => $country['country_id'],
							'language_id' => $language['language_id'],
							'name'        => $country['name']
						];

						$this->model_upgrade_upgrade->addRecord('country_description', $country_description_data);
					}
				}
			}

			if ($this->model_upgrade_upgrade->hasField('zone', 'name')) {
				$zones = $this->model_upgrade_upgrade->getRecords('zone');

				foreach ($zones as $zone) {
					foreach ($languages as $language) {
						$zone_description_data = [
							'zone_id'     => $zone['zone_id'],
							'language_id' => $language['language_id'],
							'name'        => $zone['name']
						];

						$this->model_upgrade_upgrade->addRecord('zone_description', $zone_description_data);
					}
				}
			}

			// country
			$remove[] = [
				'table' => 'country',
				'field' => 'name'
			];

			// zone
			$remove[] = [
				'table' => 'zone',
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

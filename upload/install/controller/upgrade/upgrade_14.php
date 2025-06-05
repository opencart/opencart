<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade14
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade14 extends \Opencart\System\Engine\Controller {
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

			if (!$this->model_upgrade_upgrade->hasField('country', 'address_format_id')) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "country` ADD COLUMN `address_format_id` int(11) NOT NULL AFTER `address_format`");


				$this->db->query("ALTER TABLE `" . DB_PREFIX . "country` DROP COLUMN `address_format`");
			}

			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "address_format'");

			if ($query->num_rows) {
				$address_format_total = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address_format`");

				if (!$address_format_total->row['total']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "address_format` SET `name` = 'Address Format', `address_format` = '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city}, {zone} {postcode}\r\n{country}'");
				}
			}

			// Country
			$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `address_format_id` = '1' WHERE `address_format_id` = '0'");

			$languages = $this->model_upgrade_upgrade->getRecords('language');

			$countries = $this->model_upgrade_upgrade->getRecords('country');

			if ($this->model_upgrade_upgrade->hasField('country', 'name')) {
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

				$this->model_upgrade_upgrade->dropField('country', 'name');
			}

			// Populate countries store table if empty
			$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "country_to_store`");

			if (!$query->row['total']) {
				$stores = $this->model_upgrade_upgrade->getRecords('store');

				foreach ($countries as $country) {
					$country_store_data = [
						'country_id' => $country['country_id'],
						'store_id'   => 0
					];

					$this->model_upgrade_upgrade->addRecord('country_to_store', $country_store_data);

					foreach ($stores as $store) {
						$country_store_data = [
							'country_id' => $country['country_id'],
							'store_id'   => $store['store_id']
						];

						$this->model_upgrade_upgrade->addRecord('country_to_store', $country_store_data);
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

				$this->model_upgrade_upgrade->dropField('zone', 'name');
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 14, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_15', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

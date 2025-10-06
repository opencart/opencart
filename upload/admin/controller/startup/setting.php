<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Setting
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Setting extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		// Setting
		$this->load->model('setting/setting');

		$results = $this->model_setting_setting->getSettings(0);

		foreach ($results as $result) {
			if (!$result['serialized']) {
				$this->config->set($result['key'], $result['value']);
			} else {
				$this->config->set($result['key'], json_decode($result['value'], true));
			}
		}

		// Set time zone
		if ($this->config->get('config_timezone')) {
			date_default_timezone_set($this->config->get('config_timezone'));

			// Sync PHP and DB time zones.
			$this->db->query("SET `time_zone` = '" . $this->db->escape(date('P')) . "'");
		}
	}
}

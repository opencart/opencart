<?php
namespace Opencart\Catalog\Controller\Cron;
/**
 * Class Cron
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Cron extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$time = time();

		// Crons
		$this->load->model('setting/cron');

		$results = $this->model_setting_cron->getCrons();

		$this->load->controller('cron/subscription', 3, 'subscription', 1, '', '');

		foreach ($results as $result) {
			if ($result['status'] && (strtotime('+1 ' . $result['cycle'], strtotime($result['date_modified'])) < ($time + 10))) {
				$this->load->controller($result['action'], $result['cron_id'], $result['code'], $result['cycle'], $result['date_added'], $result['date_modified']);

				$this->model_setting_cron->editCron($result['cron_id']);
			}
		}
	}
}

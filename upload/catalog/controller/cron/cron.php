<?php
namespace Opencart\Catalog\Controller\Cron;
class Cron extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$time = time();

		$this->load->model('setting/cron');

		$results = $this->model_setting_cron->getCrons();

		foreach ($results as $result) {
			if ($result['status'] && (strtotime('+1 ' . $result['cycle'], strtotime($result['date_modified'])) < ($time + 10))) {
				$this->load->controller($result['action'], $result['cron_id'], $result['code'], $result['cycle'], $result['date_added'], $result['date_modified']);

				$this->model_setting_cron->editCron($result['cron_id']);
			}
		}
	}

	public function run(): void {
		$this->load->language('marketplace/cron');

		$json = [];

		if (isset($this->request->get['cron_id'])) {
			$cron_id = (int)$this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/cron')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/cron');

			$cron_info = $this->model_setting_cron->getCron($cron_id);

			if ($cron_info) {
				$this->load->controller($cron_info['action'], $cron_id, $cron_info['code'], $cron_info['cycle'], $cron_info['date_added'], $cron_info['date_modified']);

				$this->model_setting_cron->editCron($cron_info['cron_id']);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}
<?php
class ControllerCommonCron extends Controller {
	public function index() {
		$this->load->model('setting/cron');

		$results = $this->model_setting_cron->getCrons();

		foreach ($results as $result) {
			if ($result['status'] && (strtotime('+1 ' . $result['cycle'], strtotime($result['date_modified'])) < time())) {
				$this->load->controller($result['action'], $result['cron_id'], $result['code'], $result['cycle'], $result['date_added'], $result['date_modified']);

				$this->model_setting_cron->editCron($result['cron_id']);
			}
		}
	}
}
<?php
class ControllerCommonCron extends Controller {
	public function index() {
		$this->load->model('setting/cron');

		$results = $this->model_setting_cron->getCrons();

		foreach ($results as $result) {
			if ($result['status'] && (strtotime('+1 ' . $result['cycle'], strtotime($result['date_modified'])) < time())) {
				$this->load->controller($result['action'], );

				$this->model_setting_cron->editCron($result['cron_id']);
			}
		}
	}
}
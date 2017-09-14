<?php
class ControllerCronCron extends Controller {
	public function index() {
		$this->load->model('setting/cron');

		$results = $this->model_setting_cron->getCrons();

		foreach ($results as $result) {
			$this->load->controller('cron/' . $result['code']);
		}
	}
}
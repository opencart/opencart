<?php
namespace Opencart\Admin\Controller\Cron;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		$this->load->model('setting/extension');

		$time = time();

		$filter_data = [
			'filter_subscription_status_id' => $this->config->get('config_subscription_status_id'),
			'filter_date_added'             => date('Y-m-d H:i:s', $time)
		];

		$results = $this->model_sale_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {

			if ($result['status'] && (strtotime('+' . $result['trial_duration'] . ' ' . $result['trial_frequency'], strtotime($result['date_modified'])) < ($time + 10))) {



			}

			if ($result['trial_status']) {
				$data['trial_price'] = $result['trial_price'];

				switch ($result['trial_frequency']) {
					case 'day':
						$time = $result['trial_frequency'];
						break;

				}

				if ($time > $result['date_modified']) {

				}

				$data['trial_frequency'] = $result['trial_frequency'];

				$data['trial_duration'] = $result['trial_duration'];
				$data['trial_cycle'] = $result['trial_cycle'];
				$data['trial_status'] = $result['trial_status'];
			}

			//$data['price'] = $subscription_info['price'];


			$data['frequency'] = $result['frequency'];


			$data['duration'] = $result['duration'];


			$data['cycle'] = $result['cycle'];

			//if () {


			//}


			//$result
		}
	}
}

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

		$this->load->model('sale/subscription');

		$results = $this->model_sale_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			if ($result['status'] && $result) {

			}

			$time = strtotime('+' . $result['trial_duration'] . ' ' . $result['trial_frequency'], strtotime($result['payment_date']));

			echo $time ."\n";





			//< ($time + 10
			//strtotime('+' . $result['trial_duration'] . ' ' . $result['trial_frequency'], strtotime($result['date_modified']));

			// Expired
			//if ($time > ) {


			//}



			// Payment

			// Active






			if ($result['trial_status']) {
				$trial_price = $result['trial_price'];

				$tim = match($result['trial_frequency']) {
					'Status::draft'     => 'grey',
					'Status::published' => 'green',
					'Status::archived'  => 'red',
				};

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

<?php
namespace Opencart\Admin\Controller\Cron;
/**
 * Class Subscription
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Subscription extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param int    $cron_id
	 * @param string $code
	 * @param string $cycle
	 * @param string $date_added
	 * @param string $date_modified
	 *
	 * @return void
	 */
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		// Check if there is an order, the order status is complete and subscription status is active
		$filter_data = [
			'filter_date_next'              => date('Y-m-d H:i:s'),
			'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id'),
			'start'                         => 0,
			'limit'                         => 10
		];

		$this->load->model('checkout/subscription');

		$results = $this->model_checkout_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			if (($result['trial_status'] && $result['trial_remaining']) || (!$result['duration'] && $result['remaining'])) {
				$task_data = [
					'code'   => 'subscription',
					'action' => 'task/admin/subscription',
					'args'   => []
				];

				$this->load->model('setting/task');

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}

<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Subscription Status
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class SubscriptionStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new subscription status list
	 *
	 * model/localisation/subscription_status/addSubscriptionStatus/after
	 * model/localisation/subscription_status/editSubscriptionStatus/after
	 * model/localisation/subscription_status/deleteSubscriptionStatus/after
	 *
	 * @param string $route
	 * @param array<int, mixed> $args
	 * @param mixed $output
	 *
	 * @return void
	 */
	public function index(): void {
		$task_data = [
			'code'   => 'subscription_status',
			'action' => 'task/admin/subscription_status',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}

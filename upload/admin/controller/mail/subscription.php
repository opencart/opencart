<?php
namespace Opencart\Admin\Controller\Mail;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(string &$route, array &$args, mixed &$output): void {
		if (isset($args[0])) {
			$subscription_id = $args[0];
		} else {
			$subscription_id = 0;
		}

		if (isset($args[1])) {
			$subscription_status_id = $args[1];
		} else {
			$subscription_status_id = 0;
		}

		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}

		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}

		// We need to grab the old order status ID
		$subscription_info = $this->model_checkout_subscription->getSubscription($subscription_id);

		if ($subscription_info) {
			// If order status is 0 then becomes greater than 0 send main html email
			if (!$subscription_info['subscription_status_id'] && $subscription_status_id) {
				$this->add($subscription_info, $subscription_status_id, $comment, $notify);
			}

			// If order status is not 0 then send update text email
			if ($subscription_info['subscription_status_id'] && $subscription_status_id && $notify) {
				$this->edit($subscription_info, $subscription_status_id, $comment, $notify);
			}
		}
	}

	public function history(string &$route, array &$args, mixed &$output): void {

	}

	public function transaction(string &$route, array &$args, mixed &$output): void {

	}

	public function cancel(string &$route, array &$args, mixed &$output): void {


	}
}

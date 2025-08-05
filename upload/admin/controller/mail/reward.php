<?php
namespace Opencart\Admin\Controller\Mail;
/**
 * Class Reward
 *
 * @package Opencart\Admin\Controller\Mail
 */
class Reward extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * admin/model/customer/customer.addReward/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function index(string $route, array $args, $output): void {
		if (isset($args[0])) {
			$customer_id = (int)$args[0];
		} else {
			$customer_id = 0;
		}

		if (isset($args[1])) {
			$description = (string)$args[1];
		} else {
			$description = '';
		}

		if (isset($args[2])) {
			$points = (int)$args[2];
		} else {
			$points = 0;
		}

		if (isset($args[3])) {
			$order_id = (int)$args[3];
		} else {
			$order_id = 0;
		}

		// Customer
		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if (!$customer_info) {
			return;
		}

		$this->load->language('mail/reward');

		// Setting
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

		if ($store_info) {
			$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
			$store_url = $store_info['url'];
		} else {
			$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
			$store_url = HTTP_CATALOG;
		}

		// Send the email in the correct language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($customer_info['language_id']);

		if ($language_info) {
			$language_code = $language_info['code'];
		} else {
			$language_code = $this->config->get('config_language');
		}

		$this->load->language('default', 'mail', $language_code);
		$this->load->language('mail/reward', 'mail', $language_code);

		$subject = sprintf($this->language->get('mail_text_subject'), $store_name);

		$data['text_received'] = sprintf($this->language->get('mail_text_received'), $points);
		$data['text_total'] = sprintf($this->language->get('mail_text_total'), $this->model_customer_customer->getRewardTotal($customer_id));

		$data['store'] = $store_name;
		$data['store_url'] = $store_url;

		$task_data = [
			'code'   => 'mail_reward',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $customer_info['email'],
				'from'    => $this->config->get('config_email'),
				'sender'  => $store_name,
				'subject' => $subject,
				'content' => $this->load->view('mail/reward', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}

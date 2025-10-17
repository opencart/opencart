<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Affiliate
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Affiliate extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		// Customer Group
		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());

		if (!$customer_group_info) {
			return;
		}

		$this->load->language('mail/affiliate');

		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$data['text_welcome'] = sprintf($this->language->get('text_welcome'), $store_name);

		$data['approval'] = ($this->config->get('config_affiliate_approval') || $customer_group_info['approval']);
		$data['login'] = $this->url->link('account/affiliate', 'language=' . $this->config->get('config_language'), true);

		$data['store'] = $store_name;
		$data['store_url'] = $this->config->get('config_url');

		$task_data = [
			'code'   => 'mail_affiliate',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $this->customer->getEmail(),
				'from'    => $this->config->get('config_email'),
				'sender'  => $store_name,
				'subject' => sprintf($this->language->get('text_subject'), $store_name),
				'content' => $this->load->view('mail/affiliate', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Alert
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function alert(string &$route, array &$args, &$output): void {
		// Send to main admin email if new affiliate email is enabled
		if (!in_array('affiliate', (array)$this->config->get('config_mail_alert'))) {
			return;
		}

		if (!$this->customer->isLogged()) {
			return;
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());

		if (!$customer_group_info) {
			return;
		}

		$this->load->language('mail/affiliate');

		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$data['firstname'] = $this->customer->getFirstName();
		$data['lastname'] = $this->customer->getLastName();
		$data['email'] = $this->customer->getEmail();
		$data['telephone'] = $this->customer->getTelephone();
		$data['company'] = $args[1]['company'];
		$data['website'] = html_entity_decode($args[1]['website'], ENT_QUOTES, 'UTF-8');

		$data['store'] = $store_name;
		$data['store_url'] = $this->config->get('config_url');

		$emails = [];

		$emails[] = $this->config->get('config_email');

		$tos = explode(',', (string)$this->config->get('config_mail_alert_email'));

		foreach ($tos as $to) {
			$to = trim($to);

			if (oc_validate_email($to)) {
				$emails[] = $to;
			}
		}

		$this->load->model('setting/task');

		foreach ($emails as $email) {
			$task_data = [
				'code'   => 'mail_alert',
				'action' => 'task/system/mail',
				'args'   => [
					'to'      => $email,
					'from'    => $this->config->get('config_email'),
					'sender'  => $store_name,
					'subject' => $this->language->get('text_new_affiliate'),
					'content' => $this->load->view('mail/affiliate_alert', $data)
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}

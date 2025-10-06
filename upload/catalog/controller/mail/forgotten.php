<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Forgotten
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Forgotten extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * catalog/model/account/customer.addToken/after
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
		if (!isset($args[0])) {
			return;
		}

		if (!isset($args[1]) || $args[1] != 'password') {
			return;
		}

		if (!isset($args[2])) {
			return;
		}

		// Customer
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomer((int)$args[0]);

		if (!$customer_info) {
			return;
		}

		$this->load->language('mail/forgotten');

		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$data['text_greeting'] = sprintf($this->language->get('text_greeting'), $store_name);

		$data['reset'] = $this->url->link('account/forgotten.reset', 'language=' . $this->config->get('config_language') . '&email=' . urlencode($customer_info['email']) . '&code=' . (string)$args[2], true);
		$data['ip'] = oc_get_ip();

		$data['store'] = $store_name;
		$data['store_url'] = $this->config->get('config_url');

		$task_data = [
			'code'   => 'mail_forgotten',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $customer_info['email'],
				'from'    => $this->config->get('config_email'),
				'sender'  => $store_name,
				'subject' => sprintf($this->language->get('text_subject'), $store_name),
				'content' => $this->load->view('mail/forgotten', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}

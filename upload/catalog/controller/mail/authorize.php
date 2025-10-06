<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Authorize
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Authorize extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * catalog/controller/account/authorize.send/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param array<mixed>      $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, mixed &$output): void {
		if (!isset($this->session->data['code'])) {
			return;
		}

		// Customer
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

		if (!$customer_info) {
			return;
		}

		$this->load->language('mail/authorize');

		$data['code'] = (string)$this->session->data['code'];
		$data['ip'] = oc_get_ip();
		$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$task_data = [
			'code'   => 'mail_authorize',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $this->customer->getEmail(),
				'from'    => $this->config->get('config_email'),
				'sender'  => $this->config->get('config_name'),
				'subject' => $this->language->get('text_subject'),
				'content' => $this->load->view('mail/authorize', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Reset
	 *
	 * catalog/model/account/customer.addToken/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param array<mixed>      $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function reset(string &$route, array &$args, mixed &$output): void {
		if (!isset($args[0])) {
			return;
		}

		if (!isset($args[1]) || $args[1] != 'authorize') {
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

		$this->load->language('mail/authorize_reset');

		$data['reset'] = $this->url->link('account/authorize.unlock', 'email=' . $customer_info['email'] . '&code=' . (string)$args[2], true);
		$data['ip'] = oc_get_ip();
		$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$task_data = [
			'code'   => 'mail_authorize',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $customer_info['email'],
				'from'    => $this->config->get('config_email'),
				'sender'  => $this->config->get('config_name'),
				'subject' => $this->language->get('text_subject'),
				'content' => $this->load->view('mail/authorize_reset', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}

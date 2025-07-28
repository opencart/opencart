<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Register
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Register extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * catalog/model/account/customer.addCustomer/after
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
		$this->load->language('mail/register');

		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$subject = sprintf($this->language->get('text_subject'), $store_name);

		$data['text_welcome'] = sprintf($this->language->get('text_welcome'), $store_name);

		// Customer Group
		$this->load->model('account/customer_group');

		if (isset($args[0]['customer_group_id'])) {
			$customer_group_id = (int)$args[0]['customer_group_id'];
		} else {
			$customer_group_id = (int)$this->config->get('config_customer_group_id');
		}

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		if ($customer_group_info) {
			$data['approval'] = $customer_group_info['approval'];
		} else {
			$data['approval'] = '';
		}

		$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);

		$data['store'] = $store_name;
		$data['store_url'] = $this->config->get('config_url');

		$task_data = [
			'code'   => 'mail_register',
			'action' => 'admin/mail',
			'args'   => [
				'to'      => $args[0]['email'],
				'from'    => $this->config->get('config_email'),
				'sender'  => $store_name,
				'subject' => $subject,
				'content' => $this->load->view('mail/register', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Alert
	 *
	 * catalog/model/account/customer.addCustomer/after
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
		// Send to main admin email if new account email is enabled
		if (in_array('account', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/register');

			$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$subject = $this->language->get('text_new_customer');

			$data['firstname'] = $args[0]['firstname'];
			$data['lastname'] = $args[0]['lastname'];

			$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);

			// Customer Group
			$this->load->model('account/customer_group');

			if (isset($args[0]['customer_group_id'])) {
				$customer_group_id = (int)$args[0]['customer_group_id'];
			} else {
				$customer_group_id = (int)$this->config->get('config_customer_group_id');
			}

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $args[0]['email'];
			$data['telephone'] = $args[0]['telephone'];

			$data['store'] = $store_name;
			$data['store_url'] = $this->config->get('config_url');

			$task_data = [
				'code'   => 'mail_alert',
				'action' => 'admin/mail',
				'args'   => [
					'to'      => $this->config->get('config_email') .', ' . (string)$this->config->get('config_mail_alert_email'),
					'from'    => $this->config->get('config_email'),
					'sender'  => $store_name,
					'subject' => $subject,
					'content' => $this->load->view('mail/register_alert', $data)
				]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}
}

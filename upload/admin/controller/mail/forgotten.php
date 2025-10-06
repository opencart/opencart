<?php
namespace Opencart\Admin\Controller\Mail;
/**
 * Class Forgotten
 *
 * @package Opencart\Admin\Controller\Mail
 */
class Forgotten extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * admin/model/user/user.addToken/after
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

		if (!isset($args[1]) || (string)$args[1] != 'password') {
			return;
		}

		if (!isset($args[2])) {
			return;
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUser((int)$args[0]);

		if (!$user_info) {
			return;
		}

		$this->load->language('mail/forgotten');

		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$subject = sprintf($this->language->get('text_subject'), $store_name);

		$data['text_greeting'] = sprintf($this->language->get('text_greeting'), $store_name);

		$data['reset'] = $this->url->link('common/forgotten.reset', 'email=' . $user_info['email'] . '&code=' . (string)$args[2], true);
		$data['ip'] = oc_get_ip();

		$data['store'] = $store_name;
		$data['store_url'] = $this->config->get('config_store_url');

		$task_data = [
			'code'   => 'mail_forgotten',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $user_info['email'],
				'from'    => $this->config->get('config_email'),
				'sender'  => $store_name,
				'subject' => $subject,
				'content' => $this->load->view('mail/forgotten', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}

<?php
namespace Opencart\Admin\Controller\Mail;
/**
 * Class Authorize
 *
 * @package Opencart\Admin\Controller\Mail
 */
class Authorize extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * admin/controller/common/authorize.send/after
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
		if (isset($this->session->data['code'])) {
			$code = (string)$this->session->data['code'];
		} else {
			return;
		}

		// User
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUser($this->user->getId());

		if (!$user_info) {
			return;
		}

		if ($code) {
			$this->load->language('mail/authorize');

			$data['username'] = $this->user->getUsername();
			$data['code'] = $code;
			$data['ip'] = oc_get_ip();
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$task_data = [
				'code'   => 'mail_authorize',
				'action' => 'task/system/mail',
				'args'   => [
					'to'      => $this->user->getEmail(),
					'from'    => $this->config->get('config_email'),
					'sender'  => $this->config->get('config_name'),
					'subject' => $this->language->get('text_subject'),
					'content' => $this->load->view('mail/authorize', $data)
				]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Reset
	 *
	 * admin/model/user/user.addToken/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param array<mixed>      $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function reset(&$route, &$args, &$output): void {
		if (isset($args[0])) {
			$user_id = (int)$args[0];
		} else {
			$user_id = 0;
		}

		if (isset($args[1])) {
			$type = (string)$args[1];
		} else {
			$type = '';
		}

		if (isset($args[2])) {
			$code = (string)$args[2];
		} else {
			$code = '';
		}

		// Authorize
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUser($user_id);

		if (!$user_info) {
			return;
		}

		if ($type == 'authorize') {
			$this->load->language('mail/authorize_reset');

			$data['username'] = $this->user->getUsername();
			$data['reset'] = $this->url->link('common/authorize.unlock', 'email=' . $user_info['email'] . '&code=' . $code, true);
			$data['ip'] = oc_get_ip();
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$task_data = [
				'code'   => 'mail_authorize',
				'action' => 'task/system/mail',
				'args'   => [
					'to'      => $user_info['email'],
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
}

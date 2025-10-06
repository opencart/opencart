<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Gdpr
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Gdpr extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * catalog/model/account/gdpr/addGdpr
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
		// $args[0] $code
		// $args[1] $email
		// $args[2] $action

		if (!isset($args[0])) {
			return;
		}

		if (!isset($args[1])) {
			return;
		}

		if (!isset($args[2])) {
			return;
		}

		$this->load->language('mail/gdpr');

		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		if ($this->config->get('config_logo')) {
			$data['logo'] = $this->config->get('config_url') . 'image/' . html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
		} else {
			$data['logo'] = '';
		}

		$data['text_request'] = $this->language->get('text_' . $args[2]);

		$data['button_confirm'] = $this->language->get('button_' . $args[2]);

		$data['confirm'] = $this->url->link('information/gdpr.success', 'language=' . $this->config->get('config_language') . '&code=' . $args[0], true);

		$data['ip'] = oc_get_ip();

		$data['store_name'] = $store_name;
		$data['store_url'] = $this->config->get('config_url');

		$task_data = [
			'code'   => 'mail_gdpr',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $args[1],
				'from'    => $this->config->get('config_email'),
				'sender'  => $store_name,
				'subject' => sprintf($this->language->get('text_subject'), $store_name),
				'content' => $this->load->view('mail/gdpr', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Remove
	 *
	 * catalog/model/account/gdpr/editStatus/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function remove(string &$route, array &$args, &$output): void {
		if (!isset($args[0])) {
			return;
		}

		if (!isset($args[1])) {
			return;
		}

		// GDPR
		$this->load->model('account/gdpr');

		$gdpr_info = $this->model_account_gdpr->getGdpr($args[0]);

		if (!$gdpr_info || $gdpr_info['action'] != 'remove' || $args[1] != 3) {
			return;
		}

		// Setting
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($gdpr_info['store_id']);

		if (!$store_info) {
			return;
		}

		// Send the email in the correct language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($gdpr_info['language_id']);

		if (!$language_info) {
			return;
		}

		$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');

		// Load the language for any mails using a different country code and prefixing it so that it does not pollute the main data pool.
		$this->load->language('default', 'mail', $language_info['code']);
		$this->load->language('mail/gdpr_delete', 'mail', $language_info['code']);

		// Add language vars to the template folder
		$results = $this->language->all('mail');

		foreach ($results as $key => $value) {
			$data[$key] = $value;
		}

		// Image
		$this->load->model('setting/setting');

		$store_logo = html_entity_decode($this->model_setting_setting->getValue('config_logo', $store_info['store_id']), ENT_QUOTES, 'UTF-8');

		$this->load->model('tool/image');

		if (is_file(DIR_IMAGE . $store_logo)) {
			$data['logo'] = $store_info['store_url'] . 'image/' . $store_logo;
		} else {
			$data['logo'] = '';
		}

		// Customer
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomerByEmail($gdpr_info['email']);

		if ($customer_info) {
			$data['text_hello'] = sprintf($this->language->get('mail_text_hello'), html_entity_decode($customer_info['firstname'], ENT_QUOTES, 'UTF-8'));
		} else {
			$data['text_hello'] = sprintf($this->language->get('mail_text_hello'), $this->language->get('mail_text_user'));
		}

		$data['store_name'] = $store_name;
		$data['store_url'] = $store_info['store_url'];
		$data['contact'] = $store_info['store_url'] . 'index.php?route=information/contact';

		$task_data = [
			'code'   => 'mail_gdpr',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $gdpr_info['email'],
				'from'    => $this->config->get('config_email'),
				'sender'  => $store_name,
				'subject' => sprintf($this->language->get('mail_text_subject'), $store_name),
				'content' => $this->load->view('mail/gdpr_delete', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}

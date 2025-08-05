<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Transaction
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Transaction extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * catalog/model/account/customer.addTransaction/after
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
		$this->load->language('mail/transaction');

		// Customer
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomer($args[0]);

		if (!$customer_info) {
			return;
		}

		// Send the email in the correct language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($customer_info['language_id']);

		if (!$language_info) {
			return;
		}

		// Setting
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

		if (!$store_info) {
			return;
		}

		$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');

		// Load the language for any mails using a different country code and prefixing it so it does not pollute the main data pool.
		$this->load->language('default', 'mail', $language_info['code']);
		$this->load->language('mail/transaction', 'mail', $language_info['code']);

		// Add language vars to the template folder
		$results = $this->language->all('mail');

		foreach ($results as $key => $value) {
			$data[$key] = $value;
		}

		$data['text_received'] = sprintf($this->language->get('mail_text_received'), $store_name);

		$data['amount'] = $this->currency->format($args[2], $this->config->get('config_currency'));

		// Transaction
		$this->load->model('account/transaction');

		$data['total'] = $this->currency->format($this->model_account_transaction->getTransactionTotal($args[0]), $this->config->get('config_currency'));

		$data['store'] = $store_name;
		$data['store_url'] = $store_info['store_url'];

		$task_data = [
			'code'   => 'mail_transaction',
			'action' => 'task/system/mail',
			'args'   => [
				'to'      => $customer_info['email'],
				'from'    => $this->config->get('config_email'),
				'sender'  => $store_name,
				'subject' => sprintf($this->language->get('mail_text_subject'), $store_name),
				'content' => $this->load->view('mail/transaction', $data)
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}

<?php
namespace Opencart\Admin\Controller\Marketing;
/**
 * Class Contact
 *
 * @package Opencart\Admin\Controller\Marketing
 */
class Contact extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketing/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/contact', 'user_token=' . $this->session->data['user_token'])
		];

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/contact', $data));
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public function send(): void {
		$this->load->language('marketing/contact');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketing/contact')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['subject']) {
			$json['error']['subject'] = $this->language->get('error_subject');
		}

		if (!$this->request->post['message']) {
			$json['error']['message'] = $this->language->get('error_message');
		}

		if (!$json) {
			$this->load->model('setting/store');
			$this->load->model('setting/setting');
			$this->load->model('customer/customer');
			$this->load->model('sale/order');

			$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}

			$setting = $this->model_setting_setting->getSetting('config', $this->request->post['store_id']);

			$store_email = isset($setting['config_email']) ? $setting['config_email'] : $this->config->get('config_email');

			if (isset($this->request->get['page'])) {
				$page = (int)$this->request->get['page'];
			} else {
				$page = 1;
			}

			$limit = 10;

			$email_total = 0;

			$emails = [];

			switch ($this->request->post['to']) {
				case 'newsletter':
					$customer_data = [
						'filter_newsletter' => 1,
						'start'             => ($page - 1) * $limit,
						'limit'             => $limit
					];

					$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

					$results = $this->model_customer_customer->getCustomers($customer_data);

					foreach ($results as $result) {
						$emails[] = $result['email'];
					}
					break;
				case 'customer_all':
					$customer_data = [
						'start' => ($page - 1) * $limit,
						'limit' => $limit
					];

					$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

					$results = $this->model_customer_customer->getCustomers($customer_data);

					foreach ($results as $result) {
						$emails[] = $result['email'];
					}
					break;
				case 'customer_group':
					$customer_data = [
						'filter_customer_group_id' => $this->request->post['customer_group_id'],
						'start'                    => ($page - 1) * $limit,
						'limit'                    => $limit
					];

					$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

					$results = $this->model_customer_customer->getCustomers($customer_data);

					foreach ($results as $result) {
						$emails[$result['customer_id']] = $result['email'];
					}
					break;
				case 'customer':
					if (!empty($this->request->post['customer'])) {
						$email_total = count($this->request->post['customer']);

						$customers = array_slice($this->request->post['customer'], ($page - 1) * $limit, $limit);

						foreach ($customers as $customer_id) {
							$customer_info = $this->model_customer_customer->getCustomer($customer_id);

							if ($customer_info) {
								$emails[] = $customer_info['email'];
							}
						}
					}
					break;
				case 'affiliate_all':
					$affiliate_data = [
						'filter_affiliate' => 1,
						'start'            => ($page - 1) * $limit,
						'limit'            => $limit
					];

					$email_total = $this->model_customer_customer->getTotalCustomers($affiliate_data);

					$results = $this->model_customer_customer->getCustomers($affiliate_data);

					foreach ($results as $result) {
						$emails[] = $result['email'];
					}
					break;
				case 'affiliate':
					if (!empty($this->request->post['affiliate'])) {
						$affiliates = array_slice($this->request->post['affiliate'], ($page - 1) * $limit, $limit);

						foreach ($affiliates as $affiliate_id) {
							$affiliate_info = $this->model_customer_customer->getCustomer($affiliate_id);

							if ($affiliate_info) {
								$emails[] = $affiliate_info['email'];
							}
						}

						$email_total = count($this->request->post['affiliate']);
					}
					break;
				case 'product':
					if (isset($this->request->post['product'])) {
						$email_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->post['product']);

						$results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->post['product'], ($page - 1) * $limit, $limit);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
					}
					break;
			}

			if ($emails) {
				$start = ($page - 1) * $limit;
				$end = $start + $limit;

				if ($end < $email_total) {
					$json['text'] = sprintf($this->language->get('text_sent'), $start ? $start : 1, $email_total);

					$json['next'] = $this->url->link('marketing/contact.send', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
				} else {
					$json['success'] = $this->language->get('text_success');

					$json['next'] = '';
				}

				$message  = '<html dir="ltr" lang="' . $this->language->get('code') . '">' . "\n";
				$message .= '  <head>' . "\n";
				$message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
				$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '  </head>' . "\n";
				$message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
				$message .= '</html>' . "\n";

				if ($this->config->get('config_mail_engine')) {
					$mail_option = [
						'parameter' => $this->config->get('config_mail_parameter'),
						'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
						'smtp_username' => $this->config->get('config_mail_smtp_username'),
						'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
						'smtp_port' => $this->config->get('config_mail_smtp_port'),
						'smtp_timeout' => $this->config->get('config_mail_smtp_timeout')
					];

					$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);

					foreach ($emails as $email) {
						if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$mail->setTo(trim($email));
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
				}
			} else {
				$json['error']['warning'] = $this->language->get('error_email');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

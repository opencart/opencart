<?php
class ControllerMailGdpr extends Controller {
	// admin/model/customer/gdpr/editStatus
	public function index(&$route, &$args, &$output) {
		$gdpr_info = $this->model_customer_gdpr->getGdpr($args[0]);

		if ($gdpr_info) {
			// If action equals remove than we send approve email
			if (($gdpr_info['action'] == 'remove') && $args[1] == '1') {
				$this->approve();
			}elseif (($gdpr_info['action'] == 'export') && $args[1] == '2') {
				$this->export();
			}

			if ($args[1] == '-1') {
				$this->deny();
			}
		}
	}

	// admin/model/customer/gdpr/approveGdpr
	public function approve(&$route, &$args, &$output) {
		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($args[0]);

		if ($customer_info) {
			$this->load->language('mail/gdpr_approve');

			if ($this->config->get('config_logo')) {
				$data['logo'] = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
			} else {
				$data['logo'] = '';
			}

			$data['text_hello'] = sprintf($this->language->get('text_hello'), html_entity_decode($customer_info['firstname'], ENT_QUOTES, 'UTF-8'));
			$data['text_limit'] = sprintf($this->language->get('text_limit'), $this->config->get('config_gdpr_limit'));
			$data['text_a'] = sprintf($this->language->get('text_a'), $this->config->get('config_gdpr_limit'));

			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($this->load->view('mail/gdpr_approve', $data));
			$mail->send();
		}
	}

	// admin/model/customer/gdpr/denyGdpr
	public function deny(&$route, &$args, &$output) {
		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($args[0]);

		if ($customer_info) {
			$this->load->language('mail/gdpr_deny');

			if ($this->config->get('config_logo')) {
				$data['logo'] = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
			} else {
				$data['logo'] = '';
			}

			$data['text_hello'] = sprintf($this->language->get('text_hello'), html_entity_decode($customer_info['firstname'], ENT_QUOTES, 'UTF-8'));

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$data['contact'] = $store_info['url'] . 'index.php?route=information/contact';
			} else {
				$data['contact'] = HTTPS_CATALOG . 'index.php?route=information/contact';
			}

			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($this->load->view('mail/gdpr_deny', $data));
			$mail->send();
		}
	}

	// admin/model/customer/gdpr/deleteGdpr
	public function delete(&$route, &$args, &$output) {
		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($args[0]);

		if ($customer_info && $this->request->get['route'] == '') {
			$this->load->language('mail/gdpr_delete');

			if ($this->config->get('config_logo')) {
				$data['logo'] = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
			} else {
				$data['logo'] = '';
			}

			$data['text_hello'] = sprintf($this->language->get('text_hello'), html_entity_decode($customer_info['firstname'], ENT_QUOTES, 'UTF-8'));

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$data['contact'] = $store_info['url'] . 'index.php?route=information/contact';
			} else {
				$data['contact'] = HTTPS_CATALOG . 'index.php?route=information/contact';
			}

			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($this->load->view('mail/gdpr_delete', $data));
			$mail->send();
		}
	}

	// catalog/model/customer/gdpr/addGdpr
	//public function data(&$route, &$args, &$output) {
	public function export() {
		$args[0] = 3;

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($args[0]);

		if ($customer_info) {
			$this->load->language('mail/gdpr_export');

			if ($this->config->get('config_logo')) {
				$data['logo'] = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
			} else {
				$data['logo'] = '';
			}

			$data['text_hello'] = sprintf($this->language->get('text_hello'), html_entity_decode($customer_info['firstname'], ENT_QUOTES, 'UTF-8'));

			$data['firstname'] = $this->customer->getFirstName();
			$data['lastname'] = $this->customer->getLastName();
			$data['email'] = $this->customer->getEmail();
			$data['telephone'] = $this->customer->getTelephone();

			// Addresses
			$data['addresses'] = array();

			$this->load->model('customer/customer');

			$results = $this->model_customer_customer->getAddresses($customer_id);

			foreach ($results as $result) {
				$address = array(
					'firstname' => $result['firstname'],
					'lastname'  => $result['lastname'],
					'address_1' => $result['address_1'],
					'address_2' => $result['address_2'],
					'city'      => $result['city'],
					'postcode'  => $result['postcode'],
					'country'   => $result['country'],
					'zone'      => $result['zone']
				);

				if (!in_array($address, $data['addresses'])) {
					$data['addresses'][] = $address;
				}
			}

			// Order Addresses
			$this->load->model('sale/order');

			$results = $this->model_sale_order->getOrders($customer_id);

			foreach ($results as $result) {
				$order_info = $this->model_sale_order->getOrder($result['order_id']);

				$address = array(
					'firstname'  => $order_info['payment_firstname'],
					'lastname'   => $order_info['payment_lastname'],
					'address_1'  => $order_info['payment_address_1'],
					'address_2'  => $order_info['payment_address_2'],
					'city'       => $order_info['payment_city'],
					'postcode'   => $order_info['payment_postcode'],
					'country'    => $order_info['payment_country'],
					'zone'       => $order_info['payment_zone']
				);

				if (!in_array($address, $data['addresses'])) {
					$data['addresses'][] = $address;
				}

				$address = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'country'   => $order_info['shipping_country'],
					'zone'      => $order_info['shipping_zone']
				);

				if (!in_array($address, $data['addresses'])) {
					$data['addresses'][] = $address;
				}
			}

			// Ip's
			$data['ips'] = array();

			$this->load->model('customer/customer');

			$results = $this->model_customer_customer->getIps($customer_id);

			foreach ($results as $result) {
				$data['ips'][] = array(
					'ip'         => $result['ip'],
					'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
				);
			}

			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			echo $this->load->view('mail/gdpr_export', $data);

			/*
			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
			$mail->setHtml($this->load->view('mail/gdpr', $data));
			$mail->send();
			*/
		}
	}
}

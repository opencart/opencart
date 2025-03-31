<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Order
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Order extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * catalog/model/checkout/order.addHistory/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args): void {
		if (isset($args[0])) {
			$order_id = $args[0];
		} else {
			$order_id = 0;
		}

		if (isset($args[1])) {
			$order_status_id = $args[1];
		} else {
			$order_status_id = 0;
		}

		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}

		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}

		// We need to grab the old order status ID
		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			// If the order status returns 0, then it becomes greater than 0. Therefore, we send the default html email
			if (!$order_info['order_status_id'] && $order_status_id) {
				$this->add($order_info, $order_status_id, $comment, $notify);
			}

			// If the order status does not return 0, we send the update as a text email
			if ($order_info['order_status_id'] && $order_status_id && $notify) {
				$this->history($order_info, $order_status_id, $comment, $notify);
			}
		}
	}

	/**
	 * Add
	 *
	 * @param array<string, mixed> $order_info
	 * @param int                  $order_status_id
	 * @param string               $comment
	 * @param bool                 $notify
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function add(array $order_info, int $order_status_id, string $comment, bool $notify): void {
		// Check for any downloadable products
		$download_status = false;

		$order_products = $this->model_checkout_order->getProducts($order_info['order_id']);

		foreach ($order_products as $order_product) {
			// Check if there are any linked downloads
			$product_download_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_to_download` WHERE `product_id` = '" . (int)$order_product['product_id'] . "'");

			if ($product_download_query->row['total']) {
				$download_status = true;
			}
		}

		// Store
		$store_logo = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		if (!defined('HTTP_CATALOG')) {
			$store_url = HTTP_SERVER;
		} else {
			$store_url = HTTP_CATALOG;
		}

		// Setting
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($order_info['store_id']);

		if ($store_info) {
			$this->load->model('setting/setting');

			$store_logo = html_entity_decode($this->model_setting_setting->getValue('config_logo', $store_info['store_id']), ENT_QUOTES, 'UTF-8');
			$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
			$store_url = $store_info['url'];
		}

		// Send the email in the correct language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($order_info['language_id']);

		if ($language_info) {
			$language_code = $language_info['code'];
		} else {
			$language_code = $this->config->get('config_language');
		}

		// Load the language for any mails using a different country code and prefixing it so it does not pollute the main data pool.
		$this->load->language('default', 'mail', $language_code);
		$this->load->language('mail/order_add', 'mail', $language_code);

		// Add language vars to the template folder
		$results = $this->language->all('mail');

		foreach ($results as $key => $value) {
			$data[$key] = $value;
		}

		$subject = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);

		// Image
		$this->load->model('tool/image');

		if (is_file(DIR_IMAGE . $store_logo)) {
			$data['logo'] = $store_url . 'image/' . $store_logo;
		} else {
			$data['logo'] = '';
		}

		$data['title'] = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);

		$data['text_greeting'] = sprintf($this->language->get('mail_text_greeting'), $order_info['store_name']);

		$data['store'] = $store_name;
		$data['store_url'] = $order_info['store_url'];

		$data['customer_id'] = $order_info['customer_id'];
		$data['link'] = $order_info['store_url'] . 'index.php?route=account/order.info&order_id=' . $order_info['order_id'];

		if ($download_status) {
			$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
		} else {
			$data['download'] = '';
		}

		$data['order_id'] = $order_info['order_id'];
		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
		$data['payment_method'] = $order_info['payment_method']['name'] ?? '';
		$data['shipping_method'] = $order_info['shipping_method']['name'] ?? '';
		$data['email'] = $order_info['email'];
		$data['telephone'] = $order_info['telephone'];
		$data['ip'] = $order_info['ip'];

		$order_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$order_info['language_id'] . "'");

		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = '';
		}

		$data['comment'] = nl2br($order_info['comment']);

		// Payment Address
		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = [
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		];

		$replace = [
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'zone_code' => $order_info['payment_zone_code'],
			'country'   => $order_info['payment_country']
		];

		$pattern_1 = [
			"\r\n",
			"\r",
			"\n"
		];

		$pattern_2 = [
			"/\\s\\s+/",
			"/\r\r+/",
			"/\n\n+/"
		];

		$data['payment_address'] = str_replace($pattern_1, '<br/>', preg_replace($pattern_2, '<br/>', trim(str_replace($find, $replace, $format))));

		// Shipping Address
		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$find = [
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{zone_code}',
			'{country}'
		];

		$replace = [
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'zone_code' => $order_info['shipping_zone_code'],
			'country'   => $order_info['shipping_country']
		];

		$data['shipping_address'] = str_replace($pattern_1, '<br/>', preg_replace($pattern_2, '<br/>', trim(str_replace($find, $replace, $format))));

		// Upload
		$this->load->model('tool/upload');

		// Products
		$data['products'] = [];

		foreach ($order_products as $order_product) {
			$option_data = [];

			$order_options = $this->model_checkout_order->getOptions($order_info['order_id'], $order_product['order_product_id']);

			foreach ($order_options as $order_option) {
				if ($order_option['type'] != 'file') {
					$value = $order_option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = ['value' => (oc_strlen($value) > 20 ? oc_substr($value, 0, 20) . '..' : $value)] + $order_option;
			}

			$description = '';

			// Order
			$this->load->model('checkout/order');

			$subscription_info = $this->model_checkout_order->getSubscription($order_info['order_id'], $order_product['order_product_id']);

			if ($subscription_info) {
				if ($subscription_info['trial_status']) {
					$trial_price = $this->currency->format($subscription_info['trial_price'] + ($this->config->get('config_tax') ? $subscription_info['trial_tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
					$trial_cycle = $subscription_info['trial_cycle'];
					$trial_frequency = $this->language->get('text_' . $subscription_info['trial_frequency']);
					$trial_duration = $subscription_info['trial_duration'];

					$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
				}

				$price = $this->currency->format($subscription_info['price'] + ($this->config->get('config_tax') ? $subscription_info['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']);
				$cycle = $subscription_info['cycle'];
				$frequency = $this->language->get('text_' . $subscription_info['frequency']);
				$duration = $subscription_info['duration'];

				if ($duration) {
					$description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
				} else {
					$description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
				}
			}

			$data['products'][] = [
				'option'       => $option_data,
				'subscription' => $description,
				'price'        => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'        => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
			] + $order_product;
		}

		// Order Totals
		$data['totals'] = [];

		$order_totals = $this->model_checkout_order->getTotals($order_info['order_id']);

		foreach ($order_totals as $order_total) {
			$data['totals'][] = ['text' => $this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value'])] + $order_total;
		}

		// Setting
		$this->load->model('setting/setting');

		$from = $this->model_setting_setting->getValue('config_email', $order_info['store_id']);

		if (!$from) {
			$from = $this->config->get('config_email');
		}

		if ($this->config->get('config_mail_engine')) {
			$mail_option = [
				'parameter'     => $this->config->get('config_mail_parameter'),
				'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
				'smtp_username' => $this->config->get('config_mail_smtp_username'),
				'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
				'smtp_port'     => $this->config->get('config_mail_smtp_port'),
				'smtp_timeout'  => $this->config->get('config_mail_smtp_timeout')
			];

			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);
			$mail->setTo($order_info['email']);
			$mail->setFrom($from);
			$mail->setSender($store_name);
			$mail->setSubject($subject);
			$mail->setHtml($this->load->view('mail/order_add', $data));
			$mail->send();
		}
	}

	/**
	 * History
	 *
	 * catalog/model/checkout/order.addHistory/before
	 *
	 * @param array<string, mixed> $order_info
	 * @param int                  $order_status_id
	 * @param string               $comment
	 * @param bool                 $notify
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function history(array $order_info, int $order_status_id, string $comment, bool $notify): void {
		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		if (!defined('HTTP_CATALOG')) {
			$store_url = HTTP_SERVER;
		} else {
			$store_url = HTTP_CATALOG;
		}

		// Setting
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($order_info['store_id']);

		if ($store_info) {
			$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
			$store_url = $store_info['url'];
		}

		// Send the email in the correct language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($order_info['language_id']);

		if ($language_info) {
			$language_code = $language_info['code'];
		} else {
			$language_code = $this->config->get('config_language');
		}

		// Load the language for any mails using a different country code and prefixing it so it does not pollute the main data pool.
		$this->load->language('default', 'mail', $language_code);
		$this->load->language('mail/order_edit', 'mail', $language_code);

		// Add language vars to the template folder
		$results = $this->language->all('mail');

		foreach ($results as $key => $value) {
			$data[$key] = $value;
		}

		$subject = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);

		$data['order_id'] = $order_info['order_id'];
		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

		$order_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$order_info['language_id'] . "'");

		if ($order_status_query->num_rows) {
			$data['order_status'] = $order_status_query->row['name'];
		} else {
			$data['order_status'] = '';
		}

		if ($order_info['customer_id']) {
			$data['link'] = $order_info['store_url'] . 'index.php?route=account/order.info&order_id=' . $order_info['order_id'];
		} else {
			$data['link'] = '';
		}

		$data['comment'] = strip_tags($comment);

		// Store
		$data['store'] = $store_name;
		$data['store_url'] = $store_url;

		$this->load->model('setting/setting');

		$from = $this->model_setting_setting->getValue('config_email', $order_info['store_id']);

		if (!$from) {
			$from = $this->config->get('config_email');
		}

		if ($this->config->get('config_mail_engine')) {
			$mail_option = [
				'parameter'     => $this->config->get('config_mail_parameter'),
				'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
				'smtp_username' => $this->config->get('config_mail_smtp_username'),
				'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
				'smtp_port'     => $this->config->get('config_mail_smtp_port'),
				'smtp_timeout'  => $this->config->get('config_mail_smtp_timeout')
			];

			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);
			$mail->setTo($order_info['email']);
			$mail->setFrom($from);
			$mail->setSender($store_name);
			$mail->setSubject($subject);
			$mail->setHtml($this->load->view('mail/order_history', $data));
			$mail->send();
		}
	}

	/**
	 * Alert
	 *
	 * catalog/model/checkout/order.addHistory/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function alert(string &$route, array &$args): void {
		if (isset($args[0])) {
			$order_id = $args[0];
		} else {
			$order_id = 0;
		}

		if (isset($args[1])) {
			$order_status_id = $args[1];
		} else {
			$order_status_id = 0;
		}

		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}

		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info && !$order_info['order_status_id'] && $order_status_id && in_array('order', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/order_alert');

			$subject = html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $order_info['order_id']), ENT_QUOTES, 'UTF-8');

			$data['order_id'] = $order_info['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			$order_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

			if ($order_status_query->num_rows) {
				$data['order_status'] = $order_status_query->row['name'];
			} else {
				$data['order_status'] = '';
			}

			// Upload
			$this->load->model('tool/upload');

			$data['products'] = [];

			$order_products = $this->model_checkout_order->getProducts($order_id);

			foreach ($order_products as $order_product) {
				$option_data = [];

				$order_options = $this->model_checkout_order->getOptions($order_info['order_id'], $order_product['order_product_id']);

				foreach ($order_options as $order_option) {
					if ($order_option['type'] != 'file') {
						$value = $order_option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = ['value' => (oc_strlen($value) > 20 ? oc_substr($value, 0, 20) . '..' : $value)] + $order_option;
				}

				$description = '';

				$subscription_info = $this->model_checkout_order->getSubscription($order_info['order_id'], $order_product['order_product_id']);

				if ($subscription_info) {
					if ($subscription_info['trial_status']) {
						$trial_price = $this->currency->format($subscription_info['trial_price'] + ($this->config->get('config_tax') ? $subscription_info['trial_tax'] : 0), $this->session->data['currency']);
						$trial_cycle = $subscription_info['trial_cycle'];
						$trial_frequency = $this->language->get('text_' . $subscription_info['trial_frequency']);
						$trial_duration = $subscription_info['trial_duration'];

						$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
					}

					$price = $this->currency->format($subscription_info['price'] + ($this->config->get('config_tax') ? $subscription_info['tax'] : 0), $this->session->data['currency']);
					$cycle = $subscription_info['cycle'];
					$frequency = $this->language->get('text_' . $subscription_info['frequency']);
					$duration = $subscription_info['duration'];

					if ($duration) {
						$description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
					} else {
						$description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
					}
				}

				$data['products'][] = [
					'option'       => $option_data,
					'subscription' => $description,
					'total'        => html_entity_decode($this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? $order_product['tax'] * $order_product['quantity'] : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
				] + $order_product;
			}

			$data['totals'] = [];

			$order_totals = $this->model_checkout_order->getTotals($order_id);

			foreach ($order_totals as $order_total) {
				$data['totals'][] = ['value' => html_entity_decode($this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')] + $order_total;
			}

			$data['comment'] = nl2br($order_info['comment']);

			$data['store'] = html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8');
			$data['store_url'] = $order_info['store_url'];

			if ($this->config->get('config_mail_engine')) {
				$mail_option = [
					'parameter'     => $this->config->get('config_mail_parameter'),
					'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
					'smtp_username' => $this->config->get('config_mail_smtp_username'),
					'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
					'smtp_port'     => $this->config->get('config_mail_smtp_port'),
					'smtp_timeout'  => $this->config->get('config_mail_smtp_timeout')
				];

				$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setHtml($this->load->view('mail/order_alert', $data));
				$mail->send();

				// Send to additional alert emails
				$emails = explode(',', (string)$this->config->get('config_mail_alert_email'));

				foreach ($emails as $email) {
					if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$mail->setTo(trim($email));
						$mail->send();
					}
				}
			}
		}
	}
}

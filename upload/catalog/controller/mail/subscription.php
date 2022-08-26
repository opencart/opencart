<?php
namespace Opencart\Catalog\Controller\Mail;
use \Opencart\System\Helper as Helper;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(string &$route, array &$args, &$output): void {
		/*
		if (isset($args[0])) {
			$subscription_id = $args[0];
		} else {
			$subscription_id = 0;
		}

		if (isset($args[1]['subscription'])) {
			$subscription = $args[1]['subscription'];
		} else {
			$subscription = [];
		}
/
		$subscription['order_product_id']
		$subscription['customer_id']
		$subscription['order_id']
		$subscription['subscription_plan_id']
		$subscription['name']
		$subscription['description']
		$subscription['trial_price']
		$subscription['trial_frequency']
		$subscription['trial_cycle']
		$subscription['trial_duration' ]
		$subscription['trial_status' ]
		$subscription['price' ]
		$subscription['frequency' ]
		$subscription['cycle' ]
		$subscription['duration' ]
		$subscription['remaining' ]
		$subscription['date_next']
		$subscription['status'	]

		// We need to grab the old order status ID
		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {





			// If order status is 0 then becomes greater than 0 send main html email
			if (!$order_info['order_status_id'] && $order_status_id) {
				$this->add($order_info, $order_status_id, $comment, $notify);
			}

			// If order status is not 0 then send update text email
			if ($order_info['order_status_id'] && $order_status_id && $notify) {
				$this->edit($order_info, $order_status_id, $comment, $notify);
			}



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

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($order_info['store_id']);

			if ($store_info) {
				$this->load->model('setting/setting');

				$store_logo = html_entity_decode($this->model_setting_setting->getValue('config_logo', $store_info['store_id']), ENT_QUOTES, 'UTF-8');
				$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
				$store_url = $store_info['url'];
			} else {
				$store_logo = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
				$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
				$store_url = HTTP_SERVER;
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_info['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			// Load the language for any mails using a different country code and prefixing it so it does not pollute the main data pool.
			$this->language->load($language_code, 'mail', $language_code);
			$this->language->load('mail/order_add', 'mail', $language_code);

			// Add language vars to the template folder
			$results = $this->language->all('mail');

			foreach ($results as $key => $value) {
				$data[$key] = $value;
			}

			$subject = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);

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
			$data['link'] = $order_info['store_url'] . 'index.php?route=account/order|info&order_id=' . $order_info['order_id'];

			if ($download_status) {
				$data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
			} else {
				$data['download'] = '';
			}

			$data['order_id'] = $order_info['order_id'];
			$data['date_added'] = date($this->language->get('mail_date_format_short'), strtotime($order_info['date_added']));
			$data['payment_method'] = $order_info['payment_method'];
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['ip'] = $order_info['ip'];

			$order_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$order_info['language_id'] . "'");

			if ($order_status_query->num_rows) {
				$data['order_status'] = $order_status_query->row['name'];
			} else {
				$data['order_status'] = '';
			}

			if ($comment && $notify) {
				$data['comment'] = nl2br($comment);
			} else {
				$data['comment'] = '';
			}

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

			$data['payment_address'] = str_replace(["\r\n", "\r", "\n"], '<br/>', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br/>', trim(str_replace($find, $replace, $format))));

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

			$data['shipping_address'] = str_replace(["\r\n", "\r", "\n"], '<br/>', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br/>', trim(str_replace($find, $replace, $format))));

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

					$option_data[] = [
						'name'  => $order_option['name'],
						'value' => (Helper\Utf8\strlen($value) > 20 ? Helper\Utf8\substr($value, 0, 20) . '..' : $value)
					];
				}

				$data['products'][] = [
					'name'     => $order_product['name'],
					'model'    => $order_product['model'],
					'option'   => $option_data,
					'quantity' => $order_product['quantity'],
					'price'    => $this->currency->format($order_product['price'] + ($this->config->get('config_tax') ? $order_product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				];
			}

			// Vouchers
			$data['vouchers'] = [];

			$order_vouchers = $this->model_checkout_order->getVouchers($order_info['order_id']);

			foreach ($order_vouchers as $order_voucher) {
				$data['vouchers'][] = [
					'description' => $order_voucher['description'],
					'amount'      => $this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
				];
			}

			// Order Totals
			$data['totals'] = [];

			$order_totals = $this->model_checkout_order->getTotals($order_info['order_id']);

			foreach ($order_totals as $order_total) {
				$data['totals'][] = [
					'title' => $order_total['title'],
					'text'  => $this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']),
				];
			}

			$this->load->model('setting/setting');

			$from = $this->model_setting_setting->getValue('config_email', $order_info['store_id']);

			if (!$from) {
				$from = $this->config->get('config_email');
			}

			if ($this->config->get('config_mail_engine')) {
				$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($order_info['email']);
				$mail->setFrom($from);
				$mail->setSender($store_name);
				$mail->setSubject($subject);
				$mail->setHtml($this->load->view('mail/order_invoice', $data));
				$mail->send();
			}
		}
		*/
	}
}
